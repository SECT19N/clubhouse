@extends('layouts.app')
@section('title', 'New Event')
@section('heading', 'New Event')
@section('header-actions')
    <a href="{{ route('events.index') }}" class="btn-ghost">← Back</a>
@endsection

@section('content')
<div style="max-width:580px">
    <div class="card" style="padding:28px">
        <form method="POST" action="{{ route('events.store') }}">
            @csrf
            <div style="margin-bottom:16px">
                <label class="label">Club *</label>
                <select name="club_id" class="input" style="background:#0f0f0f" required>
                    <option value="">— Select a club —</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}" {{ (old('club_id') ?? request('club_id')) == $club->id ? 'selected' : '' }}>
                            {{ $club->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:16px">
                <label class="label">Event Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" class="input" required placeholder="e.g. Annual Science Fair">
            </div>
            <div style="margin-bottom:16px">
                <label class="label">Description</label>
                <textarea name="description" class="input" rows="4" placeholder="What's this event about?">{{ old('description') }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
                <div>
                    <label class="label">Start Time *</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="input" required>
                </div>
                <div>
                    <label class="label">End Time</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="input">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:28px">
                <div>
                    <label class="label">Venue</label>
                    <input type="text" name="venue" value="{{ old('venue') }}" class="input" placeholder="e.g. Main Hall">
                </div>
                <div>
                    <label class="label">Expected Audience</label>
                    <input type="number" name="expected_audience" value="{{ old('expected_audience') }}" class="input" min="5" max="1000" placeholder="50">
                </div>
            </div>
            <button type="submit" class="btn-primary">Create Event</button>
        </form>
    </div>
</div>
@endsection