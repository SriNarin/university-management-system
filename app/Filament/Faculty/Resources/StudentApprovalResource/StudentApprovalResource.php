<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\StudentApprovalResource\Pages;
use App\Models\ClassUser; 
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

// 🌟 Safe structural layout components
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn; // Using standard selection columns instead of actions
use Filament\Tables\Filters\SelectFilter; 
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class StudentApprovalResource extends Resource
{
    protected static ?string $model = ClassUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;
    protected static \UnitEnum|string|null $navigationGroup = 'Students & Result Management';
    protected static ?string $pluralModelLabel = 'Student Enrollment Approvals';

    public static function canAccess(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        $userRole = strtolower(Auth::user()->role);
        return in_array($userRole, ['admin', 'faculty', 'faculty manager', 'faculty_manager']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student')
                    ->color('danger')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->searchable()
                    ->weight('bold')
                    ->color('warning'),
                 TextColumn::make('schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->searchable()
                    ->weight('bold')
                    ->color('info'),
                TextColumn::make('schoolClass.academicStructure.academic_level')
                    ->label('Academic Level')
                    ->searchable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('schoolClass.academicStructure.generation')
                    ->label('Generation')
                    ->searchable()
                    ->weight('bold')
                    ->color('warning'),

                TextColumn::make('schoolClass.academicStructure.year_progress')
                    ->label('Year Progress')
                    ->searchable()
                    ->weight('bold')
                    ->color('info'),

                TextColumn::make('schoolClass.class_code')
                    ->label('Class Code')
                    ->searchable()
                    ->color('danger')
                    ->weight('bold')
                    ->badge(),

                TextColumn::make('schoolClass.shift')
                    ->label('Shift')
                    ->searchable()
                    ->weight('bold')
                    ->color('warning'),

                TextColumn::make('schoolClass.room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->weight('bold')
                    ->color('info'),

                
                TextColumn::make('enrollment_type')
                    ->label('Student Enrollment Type')
                    ->badge(),

                // 🌟 THE NEW WAY: An inline interactive dropdown status column
                SelectColumn::make('approval_status')
                    ->label('Approval Status')
                    ->options([
                        'pending' => '⏳ Pending',
                        'approved' => '✅ Approved',
                        'rejected' => '❌ Rejected',
                    ])
                    ->selectablePlaceholder(false)
                    ->afterStateUpdated(function ($record, $state) {
                        // Automatically logs who changed the status right when they click it
                        $record->update([
                            'approved_by_manager_id' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Status Updated Successfully')
                            ->body("Enrollment status changed to " . ucfirst($state))
                            ->success()
                            ->send();
                    }),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                   ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('approval_status')
                    ->label('Approval Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),        
            ])
            ->actions([
                // Completely empty to guarantee zero compiler crashes
            ])
            ->bulkActions([
                // Completely empty to guarantee zero compiler crashes
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentApprovals::route('/'),
        ];
    }
}