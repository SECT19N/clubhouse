@extends('layouts.app')
@section('title', 'Students')
@section('heading', 'Students')

@section('header-actions')
    @if(Auth::user()->isAdmin())
        <a href="{{ route('students.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Student
        </a>
        <a href="{{ route('students.export') }}" class="btn-ghost">Export CSV</a>
    @endif
@endsection

@section('content')
<form method="GET" action="{{ route('students.index') }}" style="display:flex;gap:10px;margin-bottom:24px">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…" class="input" style="max-width:300px">
    <input type="number" name="graduation_year" value="{{ request('graduation_year') }}" placeholder="Grad year…" class="input" style="max-width:110px">
    <button type="submit" class="btn-ghost">Filter</button>
    @if(request()->hasAny(['search','graduation_year']))
        <a href="{{ route('students.index') }}" class="btn-ghost">Clear</a>
    @endif
</form>

<div class="card">
    @php
    function sortLink($label, $column, $currentSort, $currentDirection) {
        $direction = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';
        $arrow = '';
        if ($currentSort === $column) {
            $arrow = $currentDirection === 'asc' ? ' ↑' : ' ↓';
        }
        return '<a href="' . request()->fullUrlWithQuery(['sort' => $column, 'direction' => $direction]) . '" style="color:white;text-decoration:none">' . $label . $arrow . '</a>';
    }
    @endphp

    <table>
        <thead class="align-middle h-16">
            <tr>
                <th>Student</th>
                <th>Email</th>
                <th>Gender</th>
                <th>{!! sortLink('Grad Year', 'graduation_year', $sort, $direction) !!}</th>
                <th>{!! sortLink('GPA', 'gpa', $sort, $direction) !!}</th>
                <th>Clubs</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>
                    <a href="{{ route('students.show', $student) }}" style="color:white;text-decoration:none;font-weight:500">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </a>
                </td>
                <td style="font-size:0.8rem;color:#6b6b6b">{{ $student->email }}</td>
                <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ $student->gender ?? '—' }}</td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#6b6b6b">{{ $student->graduation_year }}</td>
                <td>
                    @if($student->gpa !== null)
                        <span style="font-family:'DM Mono',monospace;font-size:0.8rem;color:{{ $student->gpa >= 3.5 ? '#4ade80' : ($student->gpa >= 2.5 ? '#e8ff47' : '#f87171') }}">
                            {{ number_format($student->gpa, 2) }}
                        </span>
                    @else
                        <span style="color:#6b6b6b">—</span>
                    @endif
                </td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#aaa">{{ $student->clubs_count }}</td>
                @if(Auth::user()->isAdmin())
                <td>
                    <div style="display:flex;gap:10px;align-items:center">
                        <a href="{{ route('students.edit', $student) }}" style="font-size:0.75rem;color:#6b6b6b;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b6b6b'">Edit</a>
                        <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this student?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:0.75rem;color:#f87171;padding:0">Delete</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#6b6b6b;padding:32px">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($students->hasPages())
<div style="margin-top:16px;display:flex;align-items:center;gap:6px;font-size:0.8rem">
    @if($students->onFirstPage())
        <span style="color:#3a3a3a;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px">← Prev</span>
    @else
        <a href="{{ $students->previousPageUrl() }}" style="color:#aaa;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#aaa'">← Prev</a>
    @endif
    <span style="color:#6b6b6b;padding:0 6px">Page {{ $students->currentPage() }} of {{ $students->lastPage() }}</span>
    @if($students->hasMorePages())
        <a href="{{ $students->nextPageUrl() }}" style="color:#aaa;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#aaa'">Next →</a>
    @else
        <span style="color:#3a3a3a;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px">Next →</span>
    @endif
</div>
@endif
@endsection