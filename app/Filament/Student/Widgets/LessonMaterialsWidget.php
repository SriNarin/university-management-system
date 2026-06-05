<?php

namespace App\Filament\Student\Widgets;

use App\Models\LessonMaterial;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

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
                TextColumn::make('topic_heading')
                    ->label('Topic / Lesson Content Heading')
                    ->wrap()
                    ->weight('semibold')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Uploaded Timestamp')
                    ->dateTime('M d Y, H:i')
                    ->timezone('Asia/Phnom_Penh')
                    ->colors(['gray']),
                TextColumn::make('file_path')
                    ->label('Download Material')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '<span style="color: #9ca3af;">No Attachment</span>';
                        }
                        
                        // Generates a plain web link directly inside the row cell
                        return new HtmlString('
                            <a href="' . asset('storage/' . $state) . '" target="_blank" download style="color: #4f46e5; text-decoration: underline; font-weight: bold;">
                                📥 Download File
                            </a>
                        ');
                    }),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([]);
    }
}