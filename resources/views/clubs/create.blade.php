@extends('layouts.app')
@section('title', 'New Club')
@section('heading', 'New Club')
@section('header-actions')
    <a href="{{ route('clubs.index') }}" class="btn-ghost">← Back</a>
@endsection

@section('content')
<div style="max-width:520px">
    <div class="card" style="padding:28px">
        <form method="POST" action="{{ route('clubs.store') }}">
            @csrf
            <div style="margin-bottom:18px">
                <label class="label">Club Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
                <div>
                    <label class="label">Room</label>
                    <input type="text" name="room" value="{{ old('room') }}" class="input" placeholder="e.g. B-204">
                </div>
                <div>
                    <label class="label">Founded Year *</label>
                    <input type="number" name="founded_year" value="{{ old('founded_year', date('Y')) }}" class="input" required min="1900" max="{{ date('Y') }}">
                </div>
            </div>
            <div style="margin-bottom:28px">
                <label class="label">President Email</label>
                <input type="email" name="president_email" value="{{ old('president_email') }}" class="input" placeholder="president@example.com">
            </div>
            <button type="submit" class="btn-primary">Create Club</button>
        </form>
    </div>
</div>
@endsection