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
    public function index(Request $request): JsonResponse {
        $query = Student::query();

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by graduation year
        if ($request->has('graduation_year')) {
            $query->where('graduation_year', $request->get('graduation_year'));
        }

        // Filter by gender
        if ($request->has('gender')) {
            $query->where('gender', $request->get('gender'));
        }

        // Filter by GPA range
        if ($request->has('gpa_min')) {
            $query->where('gpa', '>=', $request->get('gpa_min'));
        }
        if ($request->has('gpa_max')) {
            $query->where('gpa', '<=', $request->get('gpa_max'));
        }

        // Include trashed items
        if ($request->has('with_trashed') && $request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        // Only trashed items
        if ($request->has('only_trashed') && $request->boolean('only_trashed')) {
            $query->onlyTrashed();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'last_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['first_name', 'last_name', 'email', 'graduation_year', 'gpa', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $students = $query->paginate($perPage);

        return response()->json($students);
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
     * Restore a soft-deleted student.
     */
    public function restore($id): JsonResponse {
        $student = Student::withTrashed()->findOrFail($id);
        
        if (!$student->trashed()) {
            return response()->json([
                'message' => 'Student is not deleted.',
            ], 400);
        }

        $student->restore();
        
        return response()->json([
            'message' => 'Student restored successfully',
            'student' => $student,
        ]);
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
