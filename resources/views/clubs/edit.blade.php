@extends('layouts.app')
@section('title', 'Edit ' . $club->name)
@section('heading', 'Edit Club')
@section('header-actions')
    <a href="{{ route('clubs.show', $club) }}" class="btn-ghost">← Back</a>
@endsection

@section('content')
<div style="max-width:520px">
    <div class="card" style="padding:28px">
        <form method="POST" action="{{ route('clubs.update', $club) }}">
            @csrf @method('PUT')
            <div style="margin-bottom:18px">
                <label class="label">Club Name *</label>
                <input type="text" name="name" value="{{ old('name', $club->name) }}" class="input" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
                <div>
                    <label class="label">Room</label>
                    <input type="text" name="room" value="{{ old('room', $club->room) }}" class="input">
                </div>
                <div>
                    <label class="label">Founded Year *</label>
                    <input type="number" name="founded_year" value="{{ old('founded_year', $club->founded_year) }}" class="input" required>
                </div>
            </div>
            <div style="margin-bottom:28px">
                <label class="label">President Email</label>
                <input type="email" name="president_email" value="{{ old('president_email', $club->president_email) }}" class="input">
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('clubs.show', $club) }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection