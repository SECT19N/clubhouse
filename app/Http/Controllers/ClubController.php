<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {
        return response()->json(Club::withCount([
            'students',
            'events'
        ])->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // used for HTML Forms
    }

    /**
     * Store a newly created resource in storage. returns 201 on success
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:clubs',
            'room' => 'nullable|string|max:30',
            'founded_year' => 'required|integer|digits:4',
            'president_email' => 'nullable|email',
        ]);

        $club = Club::create($validated);
        return response()->json($club, 201);
    }

    /**
     * Display the specified resource. route: Api/club/{id}
     */
    public function show(Club $club): JsonResponse {
        $club->load([
            'students',
            'events'
        ]);

        return response()->json($club);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Club $club)
    {
        // used for HTML Forms, provides a pre-filled form to edit.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Club $club): JsonResponse {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:120|unique:clubs,name,' . $club->id,
            'room' => 'nullable|string|max:30',
            'founded_year' => 'sometimes|integer|digits:4',
            'president_email' => 'nullable|email',
        ]);
        
        $club->update($validated);
        return response()->json($club);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Club $club): JsonResponse {
        $club->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all students in a club.
     */
    public function getStudents(Club $club): JsonResponse {
        $students = $club->students()->withPivot('role', 'joined_at')->get();
        
        return response()->json([
            'club_id' => $club->id,
            'club_name' => $club->name,
            'students' => $students,
            'total_students' => $students->count(),
        ]);
    }

    /**
     * Add a student to a club.
     */
    public function addStudent(Request $request, Club $club): JsonResponse {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'role' => 'nullable|string|in:member,treasurer,president,secretary|max:50',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // Check if student is already in the club
        if ($club->students()->where('student_id', $student->id)->exists()) {
            return response()->json([
                'message' => 'Student is already a member of this club.',
            ], 409);
        }

        // Attach student to club with role and joined_at
        $club->students()->attach($student->id, [
            'role' => $validated['role'] ?? 'member',
            'joined_at' => now()->toDateString(),
        ]);

        // Reload the relationship to get pivot data
        $student = $club->students()->where('student_id', $student->id)->first();

        return response()->json([
            'message' => 'Student added to club successfully',
            'student' => $student,
        ], 201);
    }

    /**
     * Remove a student from a club.
     */
    public function removeStudent(Club $club, Student $student): JsonResponse {
        if (!$club->students()->where('student_id', $student->id)->exists()) {
            return response()->json([
                'message' => 'Student is not a member of this club.',
            ], 404);
        }

        $club->students()->detach($student->id);

        return response()->json([
            'message' => 'Student removed from club successfully',
        ], 200);
    }

    /**
     * Update a student's role in a club.
     */
    public function updateStudentRole(Request $request, Club $club, Student $student): JsonResponse {
        $validated = $request->validate([
            'role' => 'required|string|in:member,treasurer,president,secretary|max:50',
        ]);

        if (!$club->students()->where('student_id', $student->id)->exists()) {
            return response()->json([
                'message' => 'Student is not a member of this club.',
            ], 404);
        }

        // Update the pivot table
        $club->students()->updateExistingPivot($student->id, [
            'role' => $validated['role'],
        ]);

        // Reload the relationship to get updated pivot data
        $student = $club->students()->where('student_id', $student->id)->first();

        return response()->json([
            'message' => 'Student role updated successfully',
            'student' => $student,
        ]);
    }
}
