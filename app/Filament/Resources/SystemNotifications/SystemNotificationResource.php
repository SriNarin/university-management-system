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

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden; 

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// Using table specific action structures for Filament v3
use Filament\Actions\Action as TableAction; 
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

    public static function canAccess(): bool
    {
        return Auth::check();
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admins can see absolutely everything
        if (\Illuminate\Support\Str::lower($user->role) === 'admin') {
            return parent::getEloquentQuery();
        }

        // Clean up scoping so users can read what they sent OR what was sent to them (Individual or Role)
        return parent::getEloquentQuery()->where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere(function ($q) use ($user) {
                    $q->where('recipient_type', 'individual')
                      ->where('receiver', (string) $user->id);
                })
                ->orWhere(function ($q) use ($user) {
                    $q->where('recipient_type', 'role')
                      ->where('receiver', $user->role);
                });
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
                        ->label('Message Here')
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
                    ->color('info')
                    ->weight('bold')
                    ->sortable()
                    ->default('System Alert'),

                TextColumn::make('message_subject')
                    ->label('Subject Heading')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('recipient_type')
                    ->label('Recipient Type')
                    ->badge()
                    ->sortable()
                    ->weight('bold')
                    ->color('warning')
                    ->color(fn ($state) => $state === 'role' ? 'warning' : 'success'),

                TextColumn::make('receiver')
                        ->label('To Recipient')
                        ->searchable()
                        ->badge()
                        ->color('success')
                        ->weight('bold')
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
                    ->weight('bold')
                    ->color('danger')
                    ->dateTime('M d Y, H:i'),
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
                TableAction::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->size('sm')
                    // Hide if the user sent it, or if there is no valid sender to reply to
                    ->hidden(fn ($record) => $record->sender_id === Auth::id() || !$record->sender_id)
                    ->modalHeading(function (SystemNotification $record) {
                        $sender = User::find($record->sender_id);
                        return "Reply to " . ($sender?->name ?? 'Sender');
                    })
                    ->modalWidth('lg')
                    // 🌟 FIX: Use ($canvas) hook parameters to safely capture current row model lifecycle
                    ->mountUsing(function (array &$data, $record): void {
                        if ($record && $record->sender_id) {
                            $sender = User::find($record->sender_id);
                            if ($sender) {
                                $senderName = $sender->name;
                                $senderRole = str_replace('_', ' ', ucfirst($sender->role ?? 'User'));
                                $data['replying_to_name'] = "{$senderName} ({$senderRole})";
                            } else {
                                $data['replying_to_name'] = "Staff Account";
                            }
                            $data['message_subject'] = 'Re: ' . ($record->message_subject ?? 'Notification Update');
                        } else {
                            $data['replying_to_name'] = "Unknown Sender";
                            $data['message_subject'] = 'Re: Notification Update';
                        }
                    })
                    ->form([
                        TextInput::make('replying_to_name')
                            ->label('Replying Direct To')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('message_subject')
                            ->label('Subject Heading')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('message_body')
                            ->label('Your Message Response')
                            ->placeholder('Type your reply here...')
                            ->rows(4)
                            ->required()
                            ->maxLength(5000),
                    ])
                    ->action(function (array $data, SystemNotification $record): void {
                        $replyNotification = SystemNotification::create([
                            'sender_id'       => Auth::id(),
                            'recipient_type'  => 'individual', 
                            'receiver'        => (string) $record->sender_id, // Routes back straight to the record owner
                            'message_subject' => $data['message_subject'],
                            'message_body'    => $data['message_body'],
                            'attachment_path' => null,
                        ]);

                        // Send database notification update
                        static::afterCreate($replyNotification);

                        Notification::make()
                            ->title('Reply Sent Directly to Sender')
                            ->success()
                            ->send();
                    }),

                ViewAction::make()->color('gray')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('info')->icon('heroicon-s-pencil')->size('sm')->visible(fn () => (Auth::user()->role === 'admin' || Auth::user()->role === 'faculty_manager')),
                DeleteAction::make()->color('danger')->icon('heroicon-s-trash')->size('sm')->visible(fn () => (Auth::user()->role === 'admin' || Auth::user()->role === 'faculty_manager')),
            ])
             ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function afterCreate($record): void
    {
        $senderName = Auth::user()->name ?? 'System Alert';
        $titleText = $record->message_subject ?? 'New Message Alert';
        $bodyText = substr($record->message_body, 0, 80) . '...';

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

        if ($record->recipient_type === 'role' && !empty($record->receiver)) {
            $targetGroupUsers = User::where('role', $record->receiver)
                ->where('id', '!=', Auth::id())
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