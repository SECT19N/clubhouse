<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {
        return response()->json(Student::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // used for HTML Forms
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|email|unique:students',
            'gender' => 'nullable|in:M,F',
            'date_of_birth' => 'required|date|before:-15 years',
            'graduation_year' => 'required|integer|digits:4',
            'gpa' => 'nullable|numeric|between:0,4',
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): JsonResponse {
        $student->load('clubs');
        return response()->json($student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): JsonResponse {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:60',
            'last_name' => 'sometimes|string|max:60',
            'email' => 'sometimes|email|unique:students,email,' . $student->id,
            'gender' => 'nullable|in:M,F',
            'date_of_birth' => 'sometimes|date|before:-15 years',
            'graduation_year' => 'sometimes|integer|digits:4',
            'gpa' => 'nullable|numeric|between:0,4',
        ]);

        $student->update($validated);
        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): JsonResponse {
        $student->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all clubs a student belongs to.
     */
    public function getClubs(Student $student): JsonResponse {
        $clubs = $student->clubs()->withPivot('role', 'joined_at')->get();
        
        return response()->json([
            'student_id' => $student->id,
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'clubs' => $clubs,
            'total_clubs' => $clubs->count(),
        ]);
    }
}
