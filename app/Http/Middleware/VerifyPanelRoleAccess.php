<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyPanelRoleAccess
{
    public function handle(Request $request, Closure $next, string $requiredRole)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect('/')->with('error', 'Account is deactivated.');
        }

        if ($user->role !== $requiredRole) {
            return match($user->role) {
                'admin' => redirect('/admin'),
                'faculty_manager' => redirect('/faculty'),
                'study_office' => redirect('/office'),
                'teacher' => redirect('/teacher'),
                'student' => redirect('/student'),
                default => redirect('/login'),
            };
        }

        return $next($request);
    }
}