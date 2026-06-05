<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemNotificationResource\Pages\CreateSystemNotification;
use App\Filament\Resources\SystemNotificationResource\Pages\EditSystemNotification;
use App\Filament\Resources\SystemNotificationResource\Pages\ListSystemNotifications;
use App\Filament\Resources\SystemNotificationResource\Pages\ViewSystemNotification;
use App\Models\SystemNotification;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; 
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

// CORRECT FILAMENT SCHEMAS FIELDS IMPORTS 
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

// CORRECT FILAMENT TABLES COLUMNS & FILTERS IMPORTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// CORRECT FILAMENT ACTIONS IMPORTS
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;


class SystemNotificationResource extends Resource
{
    protected static ?string $model = SystemNotification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

    protected static \UnitEnum|string|null $navigationGroup = 'Notifications & Announcements';

    /**
     * EVERYONE can access this resource to view, send, and check alerts 
     * specific to their user scope dashboard permissions.
     */
    public static function canAccess(): bool
    {
        return Auth::check();
    }

    /**
     * Limits the dashboard datatable grid list based on the user's role.
     * - Admins see ALL sent and received message trails.
     * - Faculty, Office, Teachers, and Students see messages addressed specifically 
     * to them individually, or sent to their matching Role Group.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admins maintain full master database overview control
        if (\Illuminate\Support\Str::lower($user->role) === 'admin') {
            return parent::getEloquentQuery();
        }

        // Restrict dashboards to intercept messages meant for them or broadcasted to their role group
        return parent::getEloquentQuery()
            ->where('sender_id', $user->id) // Messages they sent out
            ->orWhere(function ($query) use ($user) {
                $query->where('recipient_type', 'individual')
                    ->where('receiver', (string) $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('recipient_type', 'role') 
                    ->where('receiver', $user->role);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Compose System Alert & Dashboard Message Dispatcher')
                ->schema([
                   Select::make('sender_id')
                        ->label('Message Sender')
                        ->options(User::pluck('name', 'id'))
                        ->default(Auth::id()) 
                        ->disabled()            
                        ->dehydrated()          
                        ->required(),

                    Select::make('recipient_type')
                        ->label('Message To')
                        ->options([
                            'role' => 'Broadcast to Whole User Role Group',
                            'individual' => 'Send to Specific Individual Account',
                        ])
                        ->required(),

                    Select::make('receiver')
                        ->label('Target Receiver')
                        ->options(function () {
                            $roles = [
                                'admin' => 'All Administrators Group',
                                'faculty_manager' => 'All Faculty Managers Group',
                                'study_office' => 'All School Study Office Group',
                                'teacher' => 'All Academic Teachers Group',
                                'student' => 'All Enrolled Students Group',
                            ];
                            
                            $users = User::pluck('name', 'id')->toArray();
                            
                            return [
                                'Role Broadcast Target Groups' => $roles,
                                'Individual User Target Profiles' => $users
                            ];
                        })
                        ->searchable()
                        ->required(),

                    TextInput::make('message_subject')
                        ->label('Notification Subject Heading')
                        ->nullable()
                        ->maxLength(255),

                    TextInput::make('message_body')
                        ->label('Write Your Message Here')
                        ->required()
                        ->maxLength(5000),

                    FileUpload::make('attachment_path')
                        ->label('Upload Local Document Attachment / Image Picture')
                        ->directory('system-alerts-attachments')
                        ->preserveFilenames()
                        ->maxSize(10240) 
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender.name')
                    ->label('From Sender')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message_subject')
                    ->label('Subject Heading')
                    ->searchable(),

                TextColumn::make('recipient_type')
                    ->label('Recipient Type')
                    ->badge()
                    ->color(fn ($state) => $state === 'role' ? 'warning' : 'success'),

                TextColumn::make('receiver')
                        ->label('To Recipient')
                        ->searchable()
                        ->badge()
                        ->color('blue')
                        ->formatStateUsing(function ($state, $record) {
                            if ($record->recipient_type === 'role') {
                                return str_replace('_', ' ', ucfirst($state)) . 's Group';
                            }

                            if (is_numeric($state)) {
                                return User::find($state)?->name ?? "User ID: {$state}";
                            }

                            return str_replace('_', ' ', ucfirst($state));
                        }),

                TextColumn::make('attachment_path')
                    ->label('Document')
                    ->formatStateUsing(fn ($state) => $state ? '📎 Attached' : 'None')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->sortable()
                    ->dateTime('M d Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('recipient_type')
                    ->label('Filter Dispatch Scope')
                    ->options([
                        'role' => 'Role Group Alerts',
                        'individual' => 'Direct Inbox Messages',
                    ])
            ])
            ->actions([
                ViewAction::make()->color('gray')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('info')->icon('heroicon-s-pencil')->size('sm')->visible(fn ($record) => fn () => (Auth::user()->role === 'admin' || Auth::user()->role === 'faculty_manager')),
                DeleteAction::make()->color('danger')->icon('heroicon-s-trash')->size('sm')->visible(fn ($record) => fn () => (Auth::user()->role === 'admin' || Auth::user()->role === 'faculty_manager')),
            ])
             ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * 🌟 NEW PROPERTY HOOK: Intercepts the created notification and dispatches 
     * it directly into the target user's Filament database notification center tray.
     */
    public static function afterCreate($record): void
    {
        $senderName = Auth::user()->name ?? 'System Alert';
        $titleText = $record->message_subject ?? 'New Message Alert';
        $bodyText = substr($record->message_body, 0, 80) . '...';

        // Case A: Sending notification to an Individual target account
        if ($record->recipient_type === 'individual' && is_numeric($record->receiver)) {
            $targetUser = User::find($record->receiver);
            if ($targetUser) {
                Notification::make()
                    ->title($titleText)
                    ->body("From {$senderName}: {$bodyText}")
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->iconColor('success')
                    ->sendToDatabase($targetUser);
            }
        }

        // Case B: Broadking notification to an entire Role group track
        if ($record->recipient_type === 'role' && !empty($record->receiver)) {
            // Find all users matching that exact group context role string
            $targetGroupUsers = User::where('role', $record->receiver)
                ->where('id', '!=', Auth::id()) // Don't trigger popup for yourself
                ->get();

            foreach ($targetGroupUsers as $groupUser) {
                Notification::make()
                    ->title($titleText)
                    ->body("Group Alert from {$senderName}: {$bodyText}")
                    ->icon('heroicon-o-megaphone')
                    ->iconColor('warning')
                    ->sendToDatabase($groupUser);
            }
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSystemNotifications::route('/'),
            'create' => CreateSystemNotification::route('/create'),
            'edit' => EditSystemNotification::route('/{record}/edit'),
            'view' => ViewSystemNotification::route('/{record}'),
        ];
    }
}