@extends('layouts.app')
@section('title', 'Clubs')
@section('heading', 'Clubs')

@section('header-actions')
    @if(Auth::user()->isAdmin())
        <a href="{{ route('clubs.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Club
        </a>
    @endif
@endsection

@section('content')
{{-- Search --}}
<form method="GET" action="{{ route('clubs.index') }}" style="display:flex;gap:10px;margin-bottom:24px">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clubs…" class="input" style="max-width:280px">
    <input type="number" name="founded_year" value="{{ request('founded_year') }}" placeholder="Year…" class="input" style="max-width:100px">
    <button type="submit" class="btn-ghost">Filter</button>
    @if(request()->hasAny(['search','founded_year']))
        <a href="{{ route('clubs.index') }}" class="btn-ghost">Clear</a>
    @endif
</form>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Room</th>
                <th>Founded</th>
                <th>President</th>
                <th>Members</th>
                <th>Events</th>
                @if(Auth::user()->isAdmin()) <th></th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($clubs as $club)
            <tr>
                <td>
                    <a href="{{ route('clubs.show', $club) }}" style="color:white;text-decoration:none;font-weight:500">{{ $club->name }}</a>
                </td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#6b6b6b">{{ $club->room ?? '—' }}</td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#6b6b6b">{{ $club->founded_year }}</td>
                <td style="font-size:0.8rem;color:#6b6b6b">{{ $club->president_email ?? '—' }}</td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#aaa">{{ $club->students_count }}</td>
                <td style="font-family:'DM Mono',monospace;font-size:0.8rem;color:#aaa">{{ $club->events_count }}</td>
                @if(Auth::user()->isAdmin())
                <td>
                    <div style="display:flex;gap:8px;align-items:center">
                        <a href="{{ route('clubs.edit', $club) }}" style="font-size:0.75rem;color:#6b6b6b;text-decoration:none;transition:color 0.15s" onmouseover="this.style.color='white'" onmouseout="this.style.color='#6b6b6b'">Edit</a>
                        <form method="POST" action="{{ route('clubs.destroy', $club) }}" onsubmit="return confirm('Delete this club?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="background:none;border:none;cursor:pointer;padding:0">Delete</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#6b6b6b;padding:32px">No clubs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($clubs->hasPages())
<div style="margin-top:20px;display:flex;gap:4px">
    {{ $clubs->links('vendor.pagination.simple-tailwind') }}
</div>
@endif
@endsection