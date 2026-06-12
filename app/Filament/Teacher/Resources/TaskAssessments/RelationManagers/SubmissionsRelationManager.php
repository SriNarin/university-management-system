<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Actions\Action; // Correct import for Table Actions
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions'; 

    protected static ?string $recordTitleAttribute = 'id';

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
                    ->color('info')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submission Date')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->timezone('Asia/Phnom_Penh'),

                // 📂 Status Display Indicator
                

               

                TextInputColumn::make('secured_score')
                    ->label('Score')
                    ->type('number')
                    ->sortable()
                    ->weight('bold')
                    ->searchable()
                    ->color('danger')
                    ->rules(['numeric', 'min:0']),

                TextInputColumn::make('grade_letter')
                    ->label('Grade Letter')
                    ->sortable()
                    ->weight('bold')
                    ->searchable()
                    ->color('info')
                    ->rules(['max:2']),

                TextInputColumn::make('teacher_feedback')
                    ->label('Feedback Remarks'),
                    
                 TextColumn::make('submission_notes')
                    ->label('Student Notes')
                    ->limit(20)
                    ->weight('bold')
                    ->tooltip(fn ($record) => $record->submission_notes),
                TextColumn::make('attachment_file_path')
                    ->label('Student Attachment')
                    ->formatStateUsing(fn ($state) => $state ? '📄 File Submitted' : 'No file (QCM/Text)')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->weight('bold'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
               
                   
                // This action downloads files directly through Laravel's backend storage vault
                Action::make('download')
                    ->label('Download Student Submission file')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->visible(fn ($record) => !empty($record->attachment_file_path))
                    ->action(function ($record): StreamedResponse | null {
                        // Check if file exists on the default storage disk (or specify 'public' if needed)
                        if (!Storage::exists($record->attachment_file_path)) {
                            \Filament\Notifications\Notification::make()
                                ->title('File not found on server')
                                ->body('The physical file could not be found in the storage vault.')
                                ->danger()
                                ->send();
                            return null;
                        }

                        // Stream the file down directly without needing a public URL link
                        return Storage::download($record->attachment_file_path);
                    }),
            ]);
    }
}