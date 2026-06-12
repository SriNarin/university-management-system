<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Announcement\Pages\CreateAnnouncement;
use App\Filament\Resources\Announcement\Pages\EditAnnouncement;
use App\Filament\Resources\Announcement\Pages\ListAnnouncements;
use App\Filament\Resources\Announcement\Pages\ViewAnnouncement;
use App\Models\Announcement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; 
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

// CORRECT FILAMENT SCHEMAS FIELDS IMPORTS 
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

// CORRECT FILAMENT TABLES COLUMNS & FILTERS IMPORTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;


// CORRECT FILAMENT ACTIONS IMPORTS
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Forms\Form;




class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static \UnitEnum|string|null $navigationGroup = 'Notifications & Announcements';

    public static function canAccess(): bool
    {
        return Auth::check();
    }

    public static function canCreate(): bool
    {
        $role = Str::lower(Auth::user()?->role ?? '');
        return $role === 'admin' || $role === 'faculty_manager';
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $role = Str::lower(Auth::user()?->role ?? '');
        return $role === 'admin' || $role === 'faculty_manager';
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $role = Str::lower(Auth::user()?->role ?? '');
        return $role === 'admin' || $role === 'faculty_manager';
    }

    /**
     * ADVANCED QUERY LAYER: Intercepts and filters data listings based on actor role.
     * - Admins see everything (including hidden drafts).
     * - Managers see everything.
     * - School Office, Teachers, and Students only see active, visible announcements targeted to them.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        $role = Str::lower($user?->role ?? '');

        // Master Overview controls for management roles
        if ($role === 'admin' || $role === 'faculty_manager') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('is_visible', true)
            ->where(function ($query) use ($user) {
                // Returns true if target_roles column is null, empty, or contains their current role string
                $query->whereNull('target_roles')
                      ->orWhereJsonContains('target_roles', $user->role);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('University Broadcast Notice Information')
                ->schema([
                    TextInput::make('title')
                        ->label('Announcement Title Header')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('content')
                        ->label('Announcement Content')
                        ->nullable()
                        ->rows(5)
                        ->maxLength(10000)
                        ->columnSpanFull(),

                    Select::make('target_roles')
                        ->label('Target Recipient Roles Scope')
                        ->placeholder('Public Announcement (Broadcast to Everyone)')
                        ->options([
                            'admin' => 'Administrators Only',
                            'faculty_manager' => 'Faculty Managers Only',
                            'study_office' => 'School Study Office Only',
                            'teacher' => 'Academic Teachers Only',
                            'student' => 'Enrolled Students Only',
                        ])
                        ->multiple() // <-- Handles array parsing automatically
                        ->searchable()
                        ->columnSpanFull(),

                    FileUpload::make('banner_image_path')
                        ->label('Notice Banner Display Image')
                        ->directory('announcement-banners')
                        ->image()
                        ->imageEditor()
                        ->preserveFilenames()
                        ->maxSize(5120)
                        ->columnSpanFull(),

                    Toggle::make('is_pinned_to_top')
                        ->label('Pin notice to Dashboard Top Banner')
                        ->default(false)
                        ->inline(false),

                    Toggle::make('is_visible')
                        ->label('Publish Visibility Status (Active)')
                        ->default(true)
                        ->inline(false),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
       
        return $table
            ->columns([
                ImageColumn::make('banner_image_path')
                    ->label('Banner')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-announcement.png')),

                TextColumn::make('title')
                    ->label('Notice Title Heading')
                    ->searchable()
                    ->weight('bold')
                    ->wrap()
                    ->sortable(),

                // Multi-tag array output column
                TextColumn::make('target_roles')
                    ->label('Audience')
                    ->badge()
                    ->weight('bold')
                    ->color('info')
                    ->separator(',')
                    ->default('📢 Public Notice')
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucfirst($state))),
                IconColumn::make('is_pinned_to_top')
                    ->label('Pinned')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->trueIcon('heroicon-m-bell')   // <-- UPDATED: Uses the valid Medium/Solid push-pin icon
                    ->falseIcon('heroicon-o-minus') // Keeps the standard outline minus sign
                    ->sortable(),

                IconColumn::make('is_visible')
                    ->label('Visible Status')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->weight('bold')
                    ->color('danger ')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('is_pinned_to_top', 'desc')
            ->filters([
                TernaryFilter::make('is_pinned_to_top')
                    ->label('Filter Pinned Notices'),
                    
                TernaryFilter::make('is_visible')
                    ->label('Filter Visibility')
                    ->trueLabel('Live Published Only')
                    ->falseLabel('Drafts / Hidden Only'),
            ])
            ->actions([
                ViewAction::make()->color('gray')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('info')->icon('heroicon-s-pencil')->size('sm'),
                DeleteAction::make()->color('danger')->icon('heroicon-s-trash')->size('sm'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnnouncements::route('/'),
            'create' => CreateAnnouncement::route('/create'),
            'edit' => EditAnnouncement::route('/{record}/edit'),
            'view' => ViewAnnouncement::route('/{record}'),
        ];
    }
}