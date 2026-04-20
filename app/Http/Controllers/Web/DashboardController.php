<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Event;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isStudent()) {
            return $this->studentDashboard($user);
        }

        if ($user->isClubPresident()) {
            return $this->presidentDashboard($user);
        }

        return view('dashboard', ['stats' => []]);
    }

    private function adminDashboard()
    {
        $stats = [
            'total_clubs' => Club::count(),
            'total_students' => Student::count(),
            'total_events' => Event::count(),
            'total_users' => User::count(),
        ];

        $recentClubs = Club::withCount('students')->latest()->take(5)->get();
        $upcomingEvents = Event::with('club')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentClubs', 'upcomingEvents'));
    }

    private function studentDashboard(User $user)
    {
        $student = $user->student;
        $myClubs = collect();
        $upcomingEvents = collect();

        if ($student) {
            $myClubs = $student->clubs()->withPivot('role', 'joined_at')->get();
            $clubIds = $myClubs->pluck('id');
            $upcomingEvents = Event::with('club')
                ->whereIn('club_id', $clubIds)
                ->where('start_time', '>', now())
                ->orderBy('start_time')
                ->take(5)
                ->get();
        }

        $stats = [
            'my_clubs' => $myClubs->count(),
            'upcoming' => $upcomingEvents->count(),
        ];

        return view('dashboard', compact('stats', 'myClubs', 'upcomingEvents'));
    }

    private function presidentDashboard(User $user)
    {
        // Find the club where this user's email matches president_email
        $club = Club::where('president_email', $user->email)->first();

        $members = collect();
        $upcomingEvents = collect();

        if ($club) {
            $members = $club->students()->withPivot('role', 'joined_at')->get();
            $upcomingEvents = $club->events()
                ->where('start_time', '>', now())
                ->orderBy('start_time')
                ->take(5)
                ->get();
        }

        $stats = [
            'club_members' => $members->count(),
            'upcoming' => $upcomingEvents->count(),
        ];

        return view('dashboard', compact('stats', 'club', 'members', 'upcomingEvents'));
    }
}
