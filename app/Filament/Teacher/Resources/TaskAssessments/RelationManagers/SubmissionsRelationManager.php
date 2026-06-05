<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn; // 🌟 Form input straight inside the table row
use Illuminate\Support\HtmlString;
use Filament\Schemas\schema;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions'; 

    protected static ?string $recordTitleAttribute = 'id';

    // Disabling the separate create/edit form views since we are editing inline
    public function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submission Date')
                    ->dateTime('M d, Y h:i A')
                    ->timezone('Asia/Phnom_Penh'),

                // 📂 Clickable link to download files directly from the table row
                TextColumn::make('attachment_file_path')
                    ->label('Student Attachment')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return 'No file (QCM/Text)';
                        }
                        return new HtmlString('
                            <a href="' . asset('storage/' . $state) . '" target="_blank" download style="color: #4f46e5; text-decoration: underline; font-weight: bold;">
                                📥 Download File
                            </a>
                        ');
                    }),

                TextColumn::make('submission_notes')
                    ->label('Student Notes')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->submission_notes),

                // 📝 Teacher types the score directly here. Filament saves it automatically!
                TextInputColumn::make('secured_score')
                    ->label('Score')
                    ->type('number')
                    ->rules(['numeric', 'min:0']),

                // 📝 Teacher types the grade letter directly here. Saves automatically!
                TextInputColumn::make('grade_letter')
                    ->label('Grade Letter')
                    ->rules(['max:2']),

                // 📝 Teacher types brief notes directly here. Saves automatically!
                TextInputColumn::make('teacher_feedback')
                    ->label('Feedback Remarks'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([]); // ❌ COMPLETELY EMPTY. No EditAction or row buttons used.
    }
}