<?php

namespace App\Filament\Office\Resources\StudentEnrolls;

use App\Filament\Office\Resources\StudentEnrolls\Pages\CreateStudentEnroll;
use App\Filament\Office\Resources\StudentEnrolls\Pages\EditStudentEnroll;
use App\Filament\Office\Resources\StudentEnrolls\Pages\ListStudentEnrolls;
use App\Filament\Office\Resources\StudentEnrolls\Schemas\StudentEnrollForm;
use App\Filament\Office\Resources\StudentEnrolls\Tables\StudentEnrollsTable;
use App\Models\ClassUser;
use App\Models\SchoolClass;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction; 
 

class StudentEnrollResource extends Resource
{
   protected static ?string $model = ClassUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static \UnitEnum|string|null $navigationGroup = 'Student Management';
    protected static ?string $pluralModelLabel = 'Student Enrollments';

    public static function form(Schema $schema): Schema
    {
       return $schema->components([
            Section::make('Enrollment Section Details')
                ->description('Assign a student profile to a class group sections.')
                ->schema([
                    Select::make('user_id')
                        ->label('Select Student')
                        ->options(User::where('role', 'student')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('school_class_id')
                        ->label('Assign to Class Section')
                        ->preload()
                        ->options(function() {
                            return SchoolClass::with('academicStructure.department')->get()->mapWithKeys(function ($class) {
                                $dept = $class->academicStructure?->department?->name_en ?? 'N/A';
                                $gen = $class->academicStructure?->generation ?? 'N/A';
                                $level = ucfirst($class->academicStructure?->academic_level ?? 'N/A');
                                $yearProg = ucfirst($class->academicStructure?->year_progress ?? 'N/A');
                                $classCode = $class->class_code;
                                $shift = ucfirst($class->shift ?? 'N/A');
                                $room = $class->room_number ?? 'N/A';

                                return [$class->id => "Class: {$class->class_code} — {$dept} [{$level} ({$yearProg}) — {$gen}] — {$shift} — Room {$room}"];
                            });
                        })
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('enrollment_type')
                        ->label('Enrollment Type')
                        ->options([
                            'paid' => 'Standard Paying Tuition Track',
                            'scholarship' => 'Assigned Scholarship Allocation Grant',
                        ])
                        ->live()
                        ->required(),

                    TextInput::make('amount_paid')
                        ->label('Total Tuition Cash Paid ($)')
                        ->numeric()
                        ->default(0.00)
                        ->visible(fn ($get) => $get('enrollment_type') === 'paid')
                        ->required(fn ($get) => $get('enrollment_type') === 'paid'),

                    TextInput::make('scholarship_type')
                        ->label('Scholarship Allocation Title/Percentage')
                        ->placeholder('e.g., 50% Tuition Waiver, 100% Academic Grand')
                        ->visible(fn ($get) => $get('enrollment_type') === 'scholarship')
                        ->required(fn ($get) => $get('enrollment_type') === 'scholarship'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student Name')
                    ->sortable()
                    ->color('danger')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->color('warning')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->color('info')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('schoolClass.academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->color('danger')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('schoolClass.academicStructure.generation')
                    ->label('Generation')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->color('success')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('schoolClass.academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color('warning')
                    ->weight('bold')
                    ->sortable()
                    ->searchable(), 
                TextColumn::make('schoolClass.class_code')
                    ->label('Assigned Class Code')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('info'),
                TextColumn::make('schoolClass.shift')
                    ->label('Shift')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->searchable(),
                TextColumn::make('schoolClass.room_number')
                    ->label('Room Number')
                    ->sortable()
                    ->weight('bold')
                    ->color('info')
                    ->searchable(),

                
                
                TextColumn::make('enrollment_type')
                    ->label('Enrollment Type')
                    ->badge()
                    ->color(fn ($state) => $state === 'paid' ? 'success' : 'warning')
                    ->weight('bold'),

                TextColumn::make('Student Tuition Details')
                    ->label('Student Tuition Details')
                    ->color('danger')
                    ->weight('bold')
                    ->getStateUsing(fn ($record) => $record->enrollment_type === 'paid' ? '$' . number_format($record->amount_paid, 2) : $record->scholarship_type),

                TextColumn::make('approval_status')
                    ->label('Manager Approval Status')
                    ->badge()
                    ->weight('bold')
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('managerApprover.name')
                    ->label('Approved By Manager')
                    ->weight('bold')
                    ->color('info')
                    ->placeholder('Pending Authorization Review...'),
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
                SelectFilter::make('enrollment_type')
                    ->label('Filter by Enrollment Type')
                    ->options([
                        'paid' => 'Standard Paying Tuition Track',
                        'scholarship' => 'Assigned Scholarship Allocation Grant',
                    ])
                    ->searchable()
                    ->preload(),
                SelectFilter::make('approval_status')
                    ->label('Filter by Approval Status')
                    ->options([
                        'pending' => '⏳ Pending',
                        'approved' => '✅ Approved',
                        'rejected' => '❌ Rejected',
                    ])
                    ->searchable()
                    ->preload(),

                SelectFilter::make('school_class_id')
                    ->label('Filter by Class Section')
                    ->options(function() {
                        return SchoolClass::with('academicStructure.department')->get()->mapWithKeys(function ($class) {
                            $dept = $class->academicStructure?->department?->name_en ?? 'N/A';
                            $gen = $class->academicStructure?->generation ?? 'N/A';
                            $level = ucfirst($class->academicStructure?->academic_level ?? 'N/A');
                            $yearProg = ucfirst($class->academicStructure?->year_progress ?? 'N/A');
                            $classCode = $class->class_code;
                            $shift = ucfirst($class->shift ?? 'N/A');
                            $room = $class->room_number ?? 'N/A';

                            return [$class->id => "Class: {$class->class_code} — {$dept} [{$level} ({$yearProg}) — {$gen}] — {$shift} — Room {$room}"];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(),

                
                    
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [

           'index' => Pages\ListStudentEnrollments::route('/'),
            'create' => Pages\CreateStudentEnrollment::route('/create'),
            'view' => Pages\ViewStudentEnrollment::route('/{record}'),
            'edit' => Pages\EditStudentEnrollment::route('/{record}/edit'),
        ];
    }
}
