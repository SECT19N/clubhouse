<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class WebStudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::withCount('clubs');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                    ->orWhere('last_name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        $students = $query->orderBy('last_name')->paginate(15)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $clubs = $student->clubs()->withPivot('role', 'joined_at')->get();

        return view('students.show', compact('student', 'clubs'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|email|unique:students',
            'gender' => 'nullable|in:M,F',
            'date_of_birth' => 'required|date|before:-15 years',
            'graduation_year' => 'required|integer|digits:4',
            'gpa' => 'nullable|numeric|between:0,4',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $clubs = $student->clubs()->withPivot('role', 'joined_at')->get();

        return view('students.edit', compact('student', 'clubs'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'gender' => 'nullable|in:M,F',
            'date_of_birth' => 'required|date|before:-15 years',
            'graduation_year' => 'required|integer|digits:4',
            'gpa' => 'nullable|numeric|between:0,4',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted.');
    }
}
