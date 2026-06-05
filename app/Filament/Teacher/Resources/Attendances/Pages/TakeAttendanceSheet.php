<?php

namespace App\Filament\Teacher\Resources\Attendances\Pages;

use App\Filament\Teacher\Resources\Attendances\AttendanceResource;
use App\Models\ClassSchedule;
use App\Models\ClassUser;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\ClassSubject;
use App\Models\AcademicStructure;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Schemas\Fields\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;

class TakeAttendanceSheet extends Page
{
    protected static string $resource = AttendanceResource::class;
    protected  string $view = 'filament.teacher.resources.attendance-resource.pages.take-attendance-sheet';
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'teaching_date' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Class Selection Section')
                    ->schema([
                        Select::make('class_schedule_id')
                            ->label('Choose Assigned Class Section / Course')
                            ->options(fn () => ClassSchedule::where('teacher_id', Auth::id())
                            ->with(['schoolClass' ])
                                ->get()
                               ->mapWithKeys(function ($item) {
                                    // Safe fallback extractions using null-safe operators (?->)
                                    $classCode    = $item->schoolClass?->class_code ?? 'No Class';
                                    $subjectName  = $item->subject_name_en ?? 'No Subject';
                                   
                                    $facultyName  = $item->schoolClass?->academicStructure?->department?->faculty?->name_en ?? 'N/A';
                                    $deptName     = $item->schoolClass?->academicStructure?->department?->name_en ?? 'N/A';
                                    $generation   = $item->schoolClass?->academicStructure?->generation ?? 'N/A';
                                    $academicLvl  = $item->schoolClass?->academicStructure?->academic_level ?? 'N/A';
                                    $yearProgress = $item->schoolClass?->academicStructure?->year_progress ?? 'N/A';
                                    // 🌟 FIX: Access columns directly via the schoolClass object parent instance
                                    $shift        = $item->schoolClass?->shift ?? 'No Shift';
                                    $roomNumber   = $item->schoolClass?->room_number ?? 'N/A';

                                    // Build your complete descriptive label string
                                    $label = "{$classCode} — {$subjectName} — [{$facultyName} - {$deptName}] [ {$generation} ({$academicLvl}) — {$yearProgress}] — {$shift} — Room {$roomNumber}";

                                    return [$item->id => $label];
                                }))
                            ->required()
                            ->live()
                           
                            // 🌟 AUTOMAGICALLY FETCH ROSTER WHEN CLASS IS SELECTED
                            ->afterStateUpdated(function (?string $state,    $set) {
                                if (!$state) {
                                    $set('students_attendance', []);
                                    return;
                                }

                                $schedule = ClassSchedule::find($state);
                                if (!$schedule) return;

                                // Find all approved students enrolled in this exact school class
                                $enrolledStudents = ClassUser::where('school_class_id', $schedule->school_class_id)
                                    ->where('approval_status', 'approved')
                                    ->with('user')
                                    ->get();

                                $attendanceGrid = [];
                                foreach ($enrolledStudents as $enrollment) {
                                    if ($enrollment->user) {
                                        $attendanceGrid[] = [
                                            'student_id' => $enrollment->user->id,
                                            'student_name' => $enrollment->user->name,
                                            'status' => 'present', // Default to present to save the teacher's time!
                                        ];
                                    }
                                }

                                $set('students_attendance', $attendanceGrid);
                            }),
    

                        DatePicker::make('teaching_date')
                            ->label('Session Teaching Date')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Student Attendance List ')
                    ->schema([
                        Repeater::make('students_attendance')
                            ->label('')
                            ->addable(false) // Disable manually adding random inputs
                            ->deletable(false) // Disable deletions
                            ->reorderable(false)
                            ->schema([
                                TextInput::make('student_name')
                                    ->label('Student Name')
                                    ->disabled() // Read-only view text
                                    ->dehydrated(true),

                                

                                Select::make('status')
                                    ->label('attendance status')
                                    ->options([
                                        'present' => '🟢 Present',
                                        'absent' => '🔴 Absent',
                                        'late' => '🟡 Late',
                                        'permission' => '🔵 Permission',
                                    ])
                                    ->required(),
                                TextInput::make('student_id')
                                    ->disabled() 
                                    ->dehydrated(true) //  Explicitly instruct Filament to send this variable back to $formValues
                                    ->extraAttributes(['style' => 'display:none;'])// Keeps it clean and physically invisible to teachers
                                    ->label(''),
                            ])
                            ->columns(2),
                    ])
            ])
            ->statePath('data');
    }

    public function saveAttendanceSheet()
    {
        $formValues = $this->form->getState();

        $scheduleId = $formValues['class_schedule_id'];
        $teachingDate = $formValues['teaching_date'];
        $rosterData = $formValues['students_attendance'] ?? [];

        if (empty($rosterData)) {
            Notification::make()
                ->title('No Students Enrolled in Class Yet')
                ->body('No students found registered inside this class  yet.')
                ->danger()
                ->send();
            return;
        }

        // Mass-insert all tracking entries loop safely
        foreach ($rosterData as $record) {
            Attendance::updateOrCreate(
                [
                    'class_schedule_id' => $scheduleId,
                    'student_id' => $record['student_id'],
                    'teaching_date' => $teachingDate,
                ],
                [
                    'status' => $record['status'],
                ]
            );
        }

        Notification::make()
            ->title('Class Attendance Logged Successfully')
            ->body('The class attendance has been saved to historical tracking students attendance logs.')
            ->success()
            ->send();

        return redirect(AttendanceResource::getUrl('index'));
    }
}