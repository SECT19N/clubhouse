@extends('layouts.app')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
@php $user = Auth::user(); @endphp

{{-- Admin dashboard --}}
@if($user->isAdmin())
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px">
    @foreach([
        ['label'=>'Clubs',    'value'=>$stats['total_clubs'],    'color'=>'#e8ff47'],
        ['label'=>'Students', 'value'=>$stats['total_students'], 'color'=>'#67c0f0'],
        ['label'=>'Events',   'value'=>$stats['total_events'],   'color'=>'#c084fc'],
        ['label'=>'Users',    'value'=>$stats['total_users'],    'color'=>'#4ade80'],
    ] as $s)
    <div class="stat-card">
        <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#6b6b6b;margin-bottom:8px">{{ $s['label'] }}</p>
        <p style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:700;color:{{ $s['color'] }}">{{ $s['value'] }}</p>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card" style="padding:20px">
        <p style="font-family:'Syne',sans-serif;font-weight:600;color:white;margin-bottom:16px">Recent Clubs</p>
        <table>
            <thead><tr><th>Name</th><th>Members</th><th>Founded</th></tr></thead>
            <tbody>
                @forelse($recentClubs as $club)
                <tr>
                    <td><a href="{{ route('clubs.show', $club) }}" style="color:white;text-decoration:none;font-weight:500">{{ $club->name }}</a></td>
                    <td style="color:#6b6b6b">{{ $club->students_count }}</td>
                    <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ $club->founded_year }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="color:#6b6b6b;text-align:center;padding:20px">No clubs yet</td></tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('clubs.index') }}" style="display:block;margin-top:14px;font-size:0.8rem;color:#e8ff47;text-decoration:none">View all clubs →</a>
    </div>

    <div class="card" style="padding:20px">
        <p style="font-family:'Syne',sans-serif;font-weight:600;color:white;margin-bottom:16px">Upcoming Events</p>
        <table>
            <thead><tr><th>Title</th><th>Club</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($upcomingEvents as $event)
                <tr>
                    <td><a href="{{ route('events.show', $event) }}" style="color:white;text-decoration:none;font-weight:500">{{ $event->title }}</a></td>
                    <td style="color:#6b6b6b;font-size:0.8rem">{{ $event->club->name }}</td>
                    <td style="font-family:'DM Mono',monospace;font-size:0.75rem;color:#6b6b6b">{{ \Carbon\Carbon::parse($event->start_time)->format('M d') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="color:#6b6b6b;text-align:center;padding:20px">No upcoming events</td></tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('events.index') }}" style="display:block;margin-top:14px;font-size:0.8rem;color:#e8ff47;text-decoration:none">View all events →</a>
    </div>
</div>

{{-- Student dashboard --}}
@elseif($user->isStudent())
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:32px;max-width:400px">
    <div class="stat-card">
        <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#6b6b6b;margin-bottom:8px">My Clubs</p>
        <p style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:700;color:#e8ff47">{{ $stats['my_clubs'] }}</p>
    </div>
    <div class="stat-card">
        <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#6b6b6b;margin-bottom:8px">Upcoming</p>
        <p style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:700;color:#67c0f0">{{ $stats['upcoming'] }}</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card" style="padding:20px">
        <p style="font-family:'Syne',sans-serif;font-weight:600;color:white;margin-bottom:16px">My Clubs</p>
        @forelse($myClubs as $club)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #1a1a1a">
            <a href="{{ route('clubs.show', $club) }}" style="color:white;text-decoration:none;font-size:0.875rem;font-weight:500">{{ $club->name }}</a>
            <span class="badge badge-{{ $club->pivot->role ?? 'member' }}">{{ $club->pivot->role ?? 'member' }}</span>
        </div>
        @empty
        <p style="color:#6b6b6b;font-size:0.875rem">You haven't joined any clubs yet.</p>
        @endforelse
    </div>

    <div class="card" style="padding:20px">
        <p style="font-family:'Syne',sans-serif;font-weight:600;color:white;margin-bottom:16px">Upcoming Events</p>
        @forelse($upcomingEvents as $event)
        <div style="padding:10px 0;border-bottom:1px solid #1a1a1a">
            <a href="{{ route('events.show', $event) }}" style="color:white;text-decoration:none;font-size:0.875rem;font-weight:500;display:block">{{ $event->title }}</a>
            <p style="color:#6b6b6b;font-size:0.75rem;margin-top:2px;font-family:'DM Mono',monospace">{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }} · {{ $event->club->name }}</p>
        </div>
        @empty
        <p style="color:#6b6b6b;font-size:0.875rem">No upcoming events.</p>
        @endforelse
    </div>
</div>

{{-- President dashboard --}}
@elseif($user->isClubPresident())
@if(isset($club) && $club)
<div style="margin-bottom:24px">
    <p style="font-family:'DM Mono',monospace;font-size:0.7rem;color:#6b6b6b;text-transform:uppercase;letter-spacing:0.06em">Managing</p>
    <h2 style="font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:700;color:white;margin-top:4px">{{ $club->name }}</h2>
</div>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:32px;max-width:400px">
    <div class="stat-card">
        <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#6b6b6b;margin-bottom:8px">Members</p>
        <p style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:700;color:#e8ff47">{{ $stats['club_members'] }}</p>
    </div>
    <div class="stat-card">
        <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:#6b6b6b;margin-bottom:8px">Upcoming</p>
        <p style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:700;color:#67c0f0">{{ $stats['upcoming'] }}</p>
    </div>
</div>

<div style="display:flex;gap:12px">
    <a href="{{ route('clubs.show', $club) }}" class="btn-primary">View Club</a>
    <a href="{{ route('events.create') }}" class="btn-ghost">New Event</a>
</div>
@else
<p style="color:#6b6b6b">No club assigned to your account yet. Ask an admin to set your email as club president.</p>
@endif
@endif
@endsection