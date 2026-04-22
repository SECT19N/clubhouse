@extends('layouts.app')
@section('title', $club->name)
@section('heading', $club->name)

@section('header-actions')
    @if(Auth::user()->isAdmin())
        <a href="{{ route('clubs.edit', $club) }}" class="btn-ghost">Edit</a>
    @endif
    <a href="{{ route('clubs.index') }}" class="btn-ghost">← All Clubs</a>
@endsection

@section('content')
{{-- Club meta --}}
<div style="display:flex;gap:16px;margin-bottom:32px;flex-wrap:wrap">
    @if($club->room)
    <div style="background:#171717;border:1px solid #262626;border-radius:4px;padding:6px 14px">
        <span style="font-family:'DM Mono',monospace;font-size:0.7rem;color:#6b6b6b;text-transform:uppercase;letter-spacing:0.05em">Room</span>
        <span style="font-size:0.875rem;color:white;margin-left:10px">{{ $club->room }}</span>
    </div>
    @endif
    <div style="background:#171717;border:1px solid #262626;border-radius:4px;padding:6px 14px">
        <span style="font-family:'DM Mono',monospace;font-size:0.7rem;color:#6b6b6b;text-transform:uppercase;letter-spacing:0.05em">Founded</span>
        <span style="font-size:0.875rem;color:white;margin-left:10px">{{ $club->founded_year }}</span>
    </div>
    @if($club->president_email)
    <div style="background:#171717;border:1px solid #262626;border-radius:4px;padding:6px 14px">
        <span style="font-family:'DM Mono',monospace;font-size:0.7rem;color:#6b6b6b;text-transform:uppercase;letter-spacing:0.05em">President</span>
        <span style="font-size:0.875rem;color:#e8ff47;margin-left:10px">{{ $club->president_email }}</span>
    </div>
    @endif
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
    {{-- Members --}}
    <div class="card" style="padding:0">
        <div style="padding:16px 20px;border-bottom:1px solid #262626;display:flex;align-items:center;justify-content:space-between">
            <p style="font-family:'Syne',sans-serif;font-weight:600;color:white">Members <span style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b;font-weight:400;margin-left:8px">{{ $members->count() }}</span></p>
        </div>

        @if(Auth::user()->isAdmin())
        <div style="padding:14px 20px;border-bottom:1px solid #1a1a1a">
            <form method="POST" action="{{ route('clubs.students.add', $club) }}" style="display:flex;gap:8px">
                @csrf
                <input type="number" name="student_id" placeholder="Student ID" class="input" style="flex:1">
                <select name="role" class="input" style="width:130px;background:#171717">
                    <option value="member">Member</option>
                    <option value="president">President</option>
                    <option value="secretary">Secretary</option>
                    <option value="treasurer">Treasurer</option>
                </select>
                <button type="submit" class="btn-primary" style="white-space:nowrap">Add</button>
            </form>
        </div>
        @endif

        <table>
            <thead><tr><th>Name</th><th>Role</th><th>Joined</th>@if(Auth::user()->isAdmin())<th></th>@endif</tr></thead>
            <tbody>
                @forelse($members as $m)
                <tr>
                    <td>
                        <a href="{{ route('students.show', $m) }}" style="color:white;text-decoration:none;font-size:0.875rem">{{ $m->first_name }} {{ $m->last_name }}</a>
                    </td>
                    <td><span class="badge badge-{{ $m->pivot->role ?? 'member' }}">{{ $m->pivot->role ?? 'member' }}</span></td>
                    <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ $m->pivot->joined_at ?? '—' }}</td>
                    @if(Auth::user()->isAdmin())
                    <td>
                        <form method="POST" action="{{ route('clubs.students.remove', [$club, $m]) }}" onsubmit="return confirm('Remove?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="background:none;border:none;cursor:pointer;font-size:0.75rem;color:#f87171;padding:0">✕</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#6b6b6b;padding:20px">No members yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Upcoming events --}}
    <div class="card" style="padding:0">
        <div style="padding:16px 20px;border-bottom:1px solid #262626;display:flex;align-items:center;justify-content:space-between">
            <p style="font-family:'Syne',sans-serif;font-weight:600;color:white">Upcoming Events</p>
            @if(Auth::user()->isAdmin() || Auth::user()->isClubPresident())
                <a href="{{ route('events.create', ['club_id' => $club->id]) }}" class="btn-primary" style="font-size:0.75rem;padding:5px 12px">+ Event</a>
            @endif
        </div>
        <table>
            <thead><tr><th>Title</th><th>Date</th><th>Venue</th></tr></thead>
            <tbody>
                @forelse($upcomingEvents as $event)
                <tr>
                    <td><a href="{{ route('events.show', $event) }}" style="color:white;text-decoration:none;font-size:0.875rem">{{ $event->title }}</a></td>
                    <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</td>
                    <td style="font-size:0.8rem;color:#6b6b6b">{{ $event->venue ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:#6b6b6b;padding:20px">No upcoming events.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:12px 20px">
            <a href="{{ route('events.index', ['club_id' => $club->id]) }}" style="font-size:0.8rem;color:#e8ff47;text-decoration:none">All events for this club →</a>
        </div>
    </div>
</div>
@endsection