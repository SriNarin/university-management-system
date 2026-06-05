<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomActivityLogResource\Pages\CreateCustomActivityLog;
use App\Filament\Resources\CustomActivityLogResource\Pages\EditCustomActivityLog;
use App\Filament\Resources\CustomActivityLogResource\Pages\ListCustomActivityLogs;
use App\Filament\Resources\CustomActivityLogResource\Pages\ViewCustomActivityLog;
use App\Models\CustomActivityLog;
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

// CORRECT FILAMENT TABLES COLUMNS & FILTERS IMPORTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// CORRECT FILAMENT ACTIONS IMPORTS
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class CustomActivityLogResource extends Resource
{
    protected static ?string $model = CustomActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $pluralModelLabel = 'Users roles Activity Logs ';

    public static function canAccess(): bool
    {
       $user = \Illuminate\Support\Facades\Auth::user();

        return $user && \Illuminate\Support\Str::lower($user->role) === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('System Internal Activity Audit Record')
                ->schema([
                    Select::make('user_id')
                        ->label('Triggered By User Account')
                        ->options(User::pluck('name', 'id'))
                        ->disabled()
                        ->placeholder('System Engine / Database Seeder Process'),

                    TextInput::make('actor_role_context')
                        ->label('Captured Role Context')
                        ->disabled(),

                    TextInput::make('action_performed')
                        ->label('CRUD Action Type')
                        ->disabled(),

                    TextInput::make('target_resource_type')
                        ->label('Target Resource Impacted')
                        ->disabled(),

                    TextInput::make('logged_payload_summary')
                        ->label('Detailed Audit Trail Payload Summary')
                        ->disabled()
                        ->columnSpanFull(),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Operator Profile')
                    ->searchable()
                    ->sortable()
                    ->default('System Automated Process'),

                // Maps to your exact actor_role_context column field
                TextColumn::make('actor_role_context')
                    ->label('Actor Role')
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'faculty_manager',
                        'info' => 'study_office',
                        'success' => 'teacher',
                        'primary' => 'student',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucfirst($state)))
                    ->sortable()
                    ->searchable(),

                // Maps to your exact action_performed column field
                TextColumn::make('action_performed')
                    ->label('Operation Triggered')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ])
                    ->sortable(),

                // Maps to your exact target_resource_type column field
                TextColumn::make('target_resource_type')
                    ->label('Affected Resource Module')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                // Maps to your exact logged_payload_summary column field
                TextColumn::make('logged_payload_summary')
                    ->label('Audit Narrative Summary')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                   ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('actor_role_context')
                    ->label('Filter Operator Role')
                    ->options([
                        'admin' => 'Administrators',
                        'faculty_manager' => 'Faculty Managers',
                        'study_office' => 'School Study Office',
                        'teacher' => 'Teachers',
                        'student' => 'Students',
                    ]),
                SelectFilter::make('action_performed')
                    ->label('Filter CRUD Event')
                    ->options([
                        'created' => 'Create Operations',
                        'updated' => 'Update Operations',
                        'deleted' => 'Delete Operations',
                    ])
                
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
            'index' => ListCustomActivityLogs::route('/'),
            'create' => CreateCustomActivityLog::route('/create'),
            'edit' => EditCustomActivityLog::route('/{record}/edit'),
            'view' => ViewCustomActivityLog::route('/{record}'),
        ];
    }
}