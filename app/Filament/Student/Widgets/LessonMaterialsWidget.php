<?php

namespace App\Filament\Student\Widgets;

use App\Models\LessonMaterial;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Filament\Actions\Action;

class LessonMaterialsWidget extends BaseWidget
{
    protected static ?string $heading = 'Shared Course Handouts & Lecture Materials';
    
    // Set column span to half on large screens
   

    public static function shouldRegister(): bool
    {
        // If the current URL path contains 'student-task-executions', hide it!
        return Request::is('*enrolled-class-hub*');
    }

    public static function canView(): bool
    {
        // Disables the widget from showing up automatically on the main Dashboard view
        if (Route::is('filament.student.pages.dashboard')) {
            return false;
        }

        return true;
    }
    
    public function table(Table $table): Table
    {
        // Finds the class context based on current page route parameters
        $studentTaskExecutionId = request()->route('record');

        return $table
            ->query(
                LessonMaterial::query()
                    ->whereHas('classSchedule.schoolClass.users', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->latest()
            )
            ->columns([
                TextColumn::make('lecture_title_topic')
                    ->label('Topic / Lesson Content Heading')
                    ->wrap()
                    ->weight('semibold')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Uploaded Timestamp')
                    ->dateTime('M d Y, H:i')
                    ->timezone('Asia/Phnom_Penh')
                    ->colors(['gray']),
                TextColumn::make('resource_attachment_path')
                    ->label('File Status')
                    ->formatStateUsing(fn ($state) => $state ? '📄 Document Attached' : '❌ Empty')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
                ])
            ->filters([])
            ->headerActions([])
            ->actions([
                // ✅ Native action streaming files cleanly without path discovery bugs
                Action::make('download')
                    ->label('📥Download file lesson')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->button()
                    ->color('indigo')
                    ->visible(fn (LessonMaterial $record) => !empty($record->resource_attachment_path))
                    ->action(function (LessonMaterial $record) {
                        $path = $record->resource_attachment_path;

                        // Check fallback local structures if default storage disc defaults fail
                        if (!Storage::disk('public')->exists($path) && Storage::exists($path)) {
                            return Storage::download($path);
                        }

                        if (Storage::disk('public')->exists($path)) {
                            return Storage::disk('public')->Storage::download($path);
                        }

                        // Send user crisp notification if missing entirely
                        \Filament\Notifications\Notification::make()
                            ->title('File Not Found')
                            ->body('The physical document cannot be found in storage directories.')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}