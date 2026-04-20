@extends('layouts.app')
@section('title', $event->title)
@section('heading', $event->title)

@section('header-actions')
    @if(Auth::user()->isAdmin() || Auth::user()->isClubPresident())
        <a href="{{ route('events.edit', $event) }}" class="btn-ghost">Edit</a>
    @endif
    <a href="{{ route('events.index') }}" class="btn-ghost">← All Events</a>
@endsection

@section('content')
@php
    $start   = \Carbon\Carbon::parse($event->start_time);
    $end     = $event->end_time ? \Carbon\Carbon::parse($event->end_time) : null;
    $isPast  = $start->isPast();
@endphp

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">

    {{-- Main info --}}
    <div>
        {{-- Status + club --}}
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:20px">
            @if($isPast)
                <span style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.05em;padding:3px 9px;border-radius:2px;background:#1a1a1a;color:#555">Past</span>
            @else
                <span style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.05em;padding:3px 9px;border-radius:2px;background:#0d1f06;color:#4ade80">Upcoming</span>
            @endif
            <a href="{{ route('clubs.show', $event->club) }}" style="font-size:0.8rem;color:#e8ff47;text-decoration:none">{{ $event->club->name }}</a>
        </div>

        {{-- Description --}}
        @if($event->description)
        <div class="card" style="padding:24px;margin-bottom:20px">
            <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:12px">Description</p>
            <p style="font-size:0.9rem;color:#ccc;line-height:1.7;white-space:pre-wrap">{{ $event->description }}</p>
        </div>
        @endif

        @if(Auth::user()->isAdmin())
        <div style="margin-top:16px">
            <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Delete this event permanently?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">Delete Event</button>
            </form>
        </div>
        @endif
    </div>

    {{-- Sidebar details --}}
    <div class="card" style="padding:20px">
        <p style="font-family:'Syne',sans-serif;font-weight:600;color:white;margin-bottom:18px">Event Details</p>

        <div style="display:flex;flex-direction:column;gap:14px">
            <div>
                <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:4px">Start</p>
                <p style="font-size:0.875rem;color:white;font-family:'DM Mono',monospace">{{ $start->format('M d, Y') }}</p>
                <p style="font-size:0.8rem;color:#6b6b6b;font-family:'DM Mono',monospace">{{ $start->format('H:i') }}</p>
            </div>

            @if($end)
            <div>
                <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:4px">End</p>
                <p style="font-size:0.875rem;color:white;font-family:'DM Mono',monospace">{{ $end->format('M d, Y') }}</p>
                <p style="font-size:0.8rem;color:#6b6b6b;font-family:'DM Mono',monospace">{{ $end->format('H:i') }}</p>
            </div>
            @endif

            @if($event->venue)
            <div style="padding-top:14px;border-top:1px solid #1e1e1e">
                <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:4px">Venue</p>
                <p style="font-size:0.875rem;color:white">{{ $event->venue }}</p>
            </div>
            @endif

            @if($event->expected_audience)
            <div style="padding-top:14px;border-top:1px solid #1e1e1e">
                <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:4px">Expected Audience</p>
                <p style="font-size:1.4rem;font-family:'Syne',sans-serif;font-weight:700;color:#e8ff47">{{ $event->expected_audience }}</p>
            </div>
            @endif

            <div style="padding-top:14px;border-top:1px solid #1e1e1e">
                <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:4px">Organised by</p>
                <a href="{{ route('clubs.show', $event->club) }}" style="font-size:0.875rem;color:#e8ff47;text-decoration:none;font-weight:500">{{ $event->club->name }}</a>
            </div>

            @if(!$isPast && $end)
            <div style="padding-top:14px;border-top:1px solid #1e1e1e">
                <p style="font-family:'DM Mono',monospace;font-size:0.65rem;text-transform:uppercase;letter-spacing:0.06em;color:#6b6b6b;margin-bottom:4px">Duration</p>
                <p style="font-size:0.875rem;color:white;font-family:'DM Mono',monospace">{{ $start->diffForHumans($end, true) }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection