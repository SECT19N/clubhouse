@extends('layouts.app')
@section('title', 'Events')
@section('heading', 'Events')

@section('header-actions')
    @if(Auth::user()->isAdmin() || Auth::user()->isClubPresident())
        <a href="{{ route('events.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Event
        </a>
    @endif
@endsection

@section('content')
{{-- Filters --}}
<form method="GET" action="{{ route('events.index') }}" style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events…" class="input" style="max-width:260px">
    <select name="club_id" class="input" style="max-width:180px;background:#171717">
        <option value="">All Clubs</option>
        @foreach($clubs as $club)
            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
        @endforeach
    </select>
    <select name="filter" class="input" style="max-width:140px;background:#171717">
        <option value="">All Events</option>
        <option value="upcoming" {{ request('filter') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
        <option value="past"     {{ request('filter') === 'past'     ? 'selected' : '' }}>Past</option>
    </select>
    <button type="submit" class="btn-ghost">Filter</button>
    @if(request()->hasAny(['search','club_id','filter']))
        <a href="{{ route('events.index') }}" class="btn-ghost">Clear</a>
    @endif
</form>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Club</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Audience</th>
                <th>Status</th>
                @if(Auth::user()->isAdmin() || Auth::user()->isClubPresident()) <th></th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            @php $isPast = \Carbon\Carbon::parse($event->start_time)->isPast(); @endphp
            <tr>
                <td>
                    <a href="{{ route('events.show', $event) }}" style="color:white;text-decoration:none;font-weight:500">{{ $event->title }}</a>
                </td>
                <td>
                    <a href="{{ route('clubs.show', $event->club) }}" style="font-size:0.8rem;color:#6b6b6b;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b6b6b'">
                        {{ $event->club->name }}
                    </a>
                </td>
                <td style="font-family:'DM Mono',monospace;font-size:0.78rem;color:#aaa">
                    {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}
                    <span style="color:#555;display:block;font-size:0.7rem">{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}</span>
                </td>
                <td style="font-size:0.8rem;color:#6b6b6b">{{ $event->venue ?? '—' }}</td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#aaa">{{ $event->expected_audience ?? '—' }}</td>
                <td>
                    @if($isPast)
                        <span style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.05em;padding:2px 7px;border-radius:2px;background:#1a1a1a;color:#555">Past</span>
                    @else
                        <span style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.05em;padding:2px 7px;border-radius:2px;background:#0d1f06;color:#4ade80">Upcoming</span>
                    @endif
                </td>
                @if(Auth::user()->isAdmin() || Auth::user()->isClubPresident())
                <td>
                    <div style="display:flex;gap:10px;align-items:center">
                        <a href="{{ route('events.edit', $event) }}" style="font-size:0.75rem;color:#6b6b6b;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b6b6b'">Edit</a>
                        <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Delete this event?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:0.75rem;color:#f87171;padding:0">Delete</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#6b6b6b;padding:32px">No events found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($events->hasPages())
<div style="margin-top:16px;display:flex;align-items:center;gap:6px;font-size:0.8rem">
    @if($events->onFirstPage())
        <span style="color:#3a3a3a;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px">← Prev</span>
    @else
        <a href="{{ $events->previousPageUrl() }}" style="color:#aaa;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#aaa'">← Prev</a>
    @endif
    <span style="color:#6b6b6b;padding:0 6px">Page {{ $events->currentPage() }} of {{ $events->lastPage() }}</span>
    @if($events->hasMorePages())
        <a href="{{ $events->nextPageUrl() }}" style="color:#aaa;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px;text-decoration:none" onmouseover="this.style.color='white'" onmouseout="this.style.color='#aaa'">Next →</a>
    @else
        <span style="color:#3a3a3a;padding:5px 10px;border:1px solid #2a2a2a;border-radius:4px">Next →</span>
    @endif
</div>
@endif
@endsection