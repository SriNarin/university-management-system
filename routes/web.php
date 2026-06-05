<?php
use App\Http\Controllers\TimetablePrintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranscriptPrintController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('public-home');
})->name('home');


Route::get('/office/transcripts/print/{student}/{class}', [TranscriptPrintController::class, 'print'])
     ->name('office.transcript.print')
     ->middleware(['web']);

     // 2. Unified Intelligent Login Processor
Route::post('/unified-login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        // Automatically send them to the correct dashboard path based on their role
        return match ($user->role) {
            'admin'           => redirect('/admin'),
            'faculty_manager' => redirect('/faculty'),
            'study_office'    => redirect('/office'),
            'teacher'         => redirect('/teacher'),
            'student'         => redirect('/student'),
            default           => redirect('/'),
        };
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our university registry records.',
    ])->onlyInput('email');
})->name('unified.login');


Route::get('/faculty/timetable/print/{id}', [TimetablePrintController::class, 'print'])->name('timetable.print');
Route::post('/faculty/timetable/toggle/{id}', [TimetablePrintController::class, 'toggleStatus'])->name('timetable.toggle-status');
Route::get('/student/timetable/print/{classId}', [TimetablePrintController::class, 'printStudentTimetable'])
    ->name('student.timetable.print')
    ->middleware(['auth']);