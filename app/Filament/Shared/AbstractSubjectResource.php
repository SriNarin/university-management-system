<?php

namespace App\Filament\Shared;


use App\Models\Subject;
use App\Models\Department;

use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

// Form Components
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

// Table Components
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;


abstract class AbstractSubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $recordTitleAttribute = 'title_en';

     public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Subject Management')
                ->description('Register university subjects and map them to their corresponding academic departments.')
                ->schema([
                    
                    // 1. SELECT DEPARTMENT
                    Select::make('department_id')
                        ->label('Assigned Department')
                        ->relationship('department', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),

                    // 2. SUBJECT CODE
                    TextInput::make('subject_code')
                        ->label('Subject Unique Code')
                        ->placeholder('e.g., CS-201, IT-304')
                        ->nullable()
                        ->maxLength(255),

                    // 3. CREDITS WEIGHT
                    TextInput::make('credits')
                        ->label('Course Credits')
                        ->numeric()
                        ->default(3)
                        ->required(),

                    // 4. ENGLISH TITLE
                    TextInput::make('title_en')
                        ->label('Subject Title (English)')
                        ->placeholder('e.g., Advanced Web Development')
                        ->nullable()
                        ->maxLength(255),

                    // 5. KHMER TITLE
                    TextInput::make('title_kh')
                        ->label('Subject Title (Khmer)')
                        ->placeholder('e.g., ការអភិវឌ្ឍគេហទំព័រកម្រិតខ្ពស់')
                        ->nullable()
                        ->maxLength(255),

                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Ref ID')->sortable(),

                TextColumn::make('subject_code')
                    ->label('Subject Code')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->weight('bold')
                    ->color('warning'),

                TextColumn::make('title_en')
                    ->label('Subject Title (EN)')
                    ->searchable()
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('title_kh')
                    ->label('Subject Title (KH)')
                    ->sortable()
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('department.name_en')
                    ->label('Department')
                    ->searchable()
                    ->color('info')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('credits')
                    ->label('Subject Credits ')
                    ->badge()
                    ->color('danger')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->weight('bold')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('department_id')
                    ->label('Filter By Department')
                    ->relationship('department', 'name_en')
                    ->searchable(),
                    
            ])
            ->actions([

                ViewAction::make()->color('info')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('danger')->icon('heroicon-s-pencil')->size('sm'),
                DeleteAction::make(),
            ]);
    }
}