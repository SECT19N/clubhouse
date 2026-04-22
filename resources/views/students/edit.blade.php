@extends('layouts.app')
@section('title', 'Edit ' . $student->first_name . ' ' . $student->last_name)
@section('heading', 'Edit Student')

@section('header-actions')
    <a href="{{ route('students.show', $student) }}" class="btn-ghost">Cancel</a>
@endsection

@section('content')
<div style="max-width:600px">
    <div class="card" style="padding:24px">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                @error('first_name')<p style="color:#ff6b6b;font-size:0.75rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                @error('last_name')<p style="color:#ff6b6b;font-size:0.75rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">Email</label>
                <input type="email" name="email" value="{{ old('email', $student->email) }}" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                @error('email')<p style="color:#ff6b6b;font-size:0.75rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">Gender</label>
                <select name="gender" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                    <option value="">—</option>
                    <option value="M" {{ old('gender', $student->gender) == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('gender', $student->gender) == 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                @error('date_of_birth')<p style="color:#ff6b6b;font-size:0.75rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">Graduation Year</label>
                <input type="number" name="graduation_year" value="{{ old('graduation_year', $student->graduation_year) }}" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                @error('graduation_year')<p style="color:#ff6b6b;font-size:0.75rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:6px">GPA</label>
                <input type="number" step="0.01" name="gpa" value="{{ old('gpa', $student->gpa) }}" style="width:100%;background:#1f1f1f;border:1px solid #333;color:white;padding:10px 12px;border-radius:6px;font-size:0.875rem">
                @error('gpa')<p style="color:#ff6b6b;font-size:0.75rem;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </div>
</div>
@endsection