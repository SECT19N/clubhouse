@extends('layouts.app')
@section('title', 'Edit Event')
@section('heading', 'Edit Event')
@section('header-actions')
    <a href="{{ route('events.show', $event) }}" class="btn-ghost">← Back</a>
@endsection

@section('content')
<div style="max-width:580px">
    <div class="card" style="padding:28px">
        <form method="POST" action="{{ route('events.update', $event) }}">
            @csrf @method('PUT')
            <div style="margin-bottom:16px">
                <label class="label">Club *</label>
                <select name="club_id" class="input" style="background:#171717" required>
                    <option value="">— Select a club —</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}" {{ old('club_id', $event->club_id) == $club->id ? 'selected' : '' }}>
                            {{ $club->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:16px">
                <label class="label">Event Title *</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" class="input" required>
            </div>
            <div style="margin-bottom:16px">
                <label class="label">Description</label>
                <textarea name="description" class="input" rows="4">{{ old('description', $event->description) }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
                <div>
                    <label class="label">Start Time *</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}" class="input" required>
                </div>
                <div>
                    <label class="label">End Time</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i') : '') }}" class="input">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:28px">
                <div>
                    <label class="label">Venue</label>
                    <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" class="input">
                </div>
                <div>
                    <label class="label">Expected Audience</label>
                    <input type="number" name="expected_audience" value="{{ old('expected_audience', $event->expected_audience) }}" class="input" min="5" max="1000">
                </div>
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('events.show', $event) }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection