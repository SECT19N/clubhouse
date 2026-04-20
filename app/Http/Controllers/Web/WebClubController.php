<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Student;
use Illuminate\Http\Request;

class WebClubController extends Controller
{
    public function index(Request $request)
    {
        $query = Club::withCount(['students', 'events']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('founded_year')) {
            $query->where('founded_year', $request->founded_year);
        }

        $clubs = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('clubs.index', compact('clubs'));
    }

    public function show(Club $club)
    {
        $club->load('events');
        $members = $club->students()->withPivot('role', 'joined_at')->get();
        $upcomingEvents = $club->events()
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('clubs.show', compact('club', 'members', 'upcomingEvents'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:clubs',
            'room' => 'nullable|string|max:30',
            'founded_year' => 'required|integer|digits:4',
            'president_email' => 'nullable|email',
        ]);

        Club::create($validated);

        return redirect()->route('clubs.index')
            ->with('success', 'Club created successfully.');
    }

    public function edit(Club $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:clubs,name,'.$club->id,
            'room' => 'nullable|string|max:30',
            'founded_year' => 'required|integer|digits:4',
            'president_email' => 'nullable|email',
        ]);

        $club->update($validated);

        return redirect()->route('clubs.show', $club)
            ->with('success', 'Club updated successfully.');
    }

    public function destroy(Club $club)
    {
        $club->delete();

        return redirect()->route('clubs.index')
            ->with('success', 'Club deleted.');
    }

    // --- Member management ---

    public function addStudent(Request $request, Club $club)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'role' => 'nullable|in:member,treasurer,president,secretary',
        ]);

        if ($club->students()->where('student_id', $validated['student_id'])->exists()) {
            return back()->with('error', 'Student is already a member of this club.');
        }

        $club->students()->attach($validated['student_id'], [
            'role' => $validated['role'] ?? 'member',
            'joined_at' => now()->toDateString(),
        ]);

        return back()->with('success', 'Student added to club.');
    }

    public function removeStudent(Club $club, Student $student)
    {
        $club->students()->detach($student->id);

        return back()->with('success', 'Student removed from club.');
    }

    public function updateStudentRole(Request $request, Club $club, Student $student)
    {
        $validated = $request->validate([
            'role' => 'required|in:member,treasurer,president,secretary',
        ]);

        $club->students()->updateExistingPivot($student->id, ['role' => $validated['role']]);

        return back()->with('success', 'Role updated.');
    }
}
