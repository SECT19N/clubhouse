@extends('layouts.app')
@section('title', $student->first_name . ' ' . $student->last_name)
@section('heading', $student->first_name . ' ' . $student->last_name)

@section('header-actions')
    @if(Auth::user()->isAdmin())
        <a href="{{ route('students.edit', $student) }}" class="btn-ghost">Edit</a>
    @endif
    <a href="{{ route('students.index') }}" class="btn-ghost">← All Students</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:280px 1fr;gap:24px;align-items:start">

    {{-- Profile card --}}
    <div class="card" style="padding:24px">
        <div style="width:56px;height:56px;border-radius:50%;background:#1f1f1f;border:1px solid #333;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
            <span style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:700;color:#e8ff47">
                {{ strtoupper(substr($student->first_name, 0, 1)) }}
            </span>
        </div>

        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.1rem;color:white;margin-bottom:4px">
            {{ $student->first_name }} {{ $student->last_name }}
        </h2>
        <p style="font-size:0.8rem;color:#6b6b6b;margin-bottom:20px">{{ $student->email }}</p>

        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach([
                ['label' => 'Gender',       'value' => $student->gender ?? '—'],
                ['label' => 'Date of Birth', 'value' => $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : '—'],
                ['label' => 'Grad Year',    'value' => $student->graduation_year],
                ['label' => 'GPA',          'value' => $student->gpa !== null ? number_format($student->gpa, 2) : '—'],
            ] as $field)
            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #1e1e1e">
                <span style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b">{{ $field['label'] }}</span>
                <span style="font-size:0.875rem;color:white;font-family:'DM Mono',monospace">{{ $field['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Clubs --}}
    <div class="card" style="padding:0">
        <div style="padding:16px 20px;border-bottom:1px solid #262626">
            <p style="font-family:'Syne',sans-serif;font-weight:600;color:white">
                Club Memberships
                <span style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b;font-weight:400;margin-left:8px">{{ $clubs->count() }}</span>
            </p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Club</th>
                    <th>Role</th>
                    <th>Room</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clubs as $club)
                <tr>
                    <td>
                        <a href="{{ route('clubs.show', $club) }}" style="color:white;text-decoration:none;font-weight:500">{{ $club->name }}</a>
                    </td>
                    <td><span class="badge badge-{{ $club->pivot->role ?? 'member' }}">{{ $club->pivot->role ?? 'member' }}</span></td>
                    <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ $club->room ?? '—' }}</td>
                    <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ $club->pivot->joined_at ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#6b6b6b;padding:32px">
                        Not a member of any clubs yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection