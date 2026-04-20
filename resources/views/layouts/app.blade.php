<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clubhouse') — Clubhouse</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        mono: ['"DM Mono"', 'monospace'],
                        display: ['"Syne"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0f0f0f;
            --surface: #171717;
            --border: #262626;
            --hover: #1f1f1f;
            --accent: #e8ff47;
            --muted: #6b6b6b;
            --text: #e5e5e5;
        }
        body { background: var(--bg); color: var(--text); }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }

        .nav-link { display: flex; align-items: center; gap: 10px; padding: 7px 12px; border-radius: 4px; font-size: 0.875rem; color: var(--muted); transition: color 0.15s, background 0.15s; }
        .nav-link:hover { color: white; background: var(--hover); }
        .nav-link.active { color: white; background: var(--hover); }
        .nav-link.active .dot { display: inline-block; width: 5px; height: 5px; background: var(--accent); border-radius: 50%; }

        .btn-primary { background: var(--accent); color: #000; font-weight: 600; padding: 7px 16px; border-radius: 4px; font-size: 0.875rem; transition: opacity 0.15s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { opacity: 0.85; }
        .btn-ghost { color: var(--muted); border: 1px solid var(--border); padding: 7px 14px; border-radius: 4px; font-size: 0.875rem; transition: color 0.15s, border-color 0.15s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-ghost:hover { color: white; border-color: #444; }
        .btn-danger { color: #f87171; border: 1px solid #3f1515; padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; transition: background 0.15s; }
        .btn-danger:hover { background: #230a0a; }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; }
        .input { width: 100%; background: var(--bg); border: 1px solid var(--border); color: var(--text); border-radius: 4px; padding: 8px 12px; font-size: 0.875rem; transition: border-color 0.15s; font-family: 'DM Sans', sans-serif; }
        .input:focus { outline: none; border-color: #444; }
        .label { display: block; font-size: 0.75rem; color: var(--muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; font-family: 'DM Mono', monospace; }

        .badge { font-family: 'DM Mono', monospace; font-size: 0.65rem; letter-spacing: 0.05em; text-transform: uppercase; padding: 2px 7px; border-radius: 2px; }
        .badge-president { background: #2a1f00; color: #e8ff47; }
        .badge-member    { background: #1a1a1a; color: #888; }
        .badge-treasurer { background: #001a2a; color: #67c0f0; }
        .badge-secretary { background: #1a002a; color: #c084fc; }
        .badge-admin     { background: #2a0000; color: #f87171; }
        .badge-student   { background: #001a10; color: #4ade80; }

        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 20px 24px; }

        main { animation: fadeUp 0.18s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        .flash-ok  { background: #0d1f06; border: 1px solid #2a5010; color: #86efac; border-radius: 4px; padding: 10px 16px; font-size: 0.875rem; margin-bottom: 20px; }
        .flash-err { background: #1f0606; border: 1px solid #501010; color: #fca5a5; border-radius: 4px; padding: 10px 16px; font-size: 0.875rem; margin-bottom: 20px; }

        select.input option { background: #1a1a1a; }

        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        thead th { text-align: left; font-family: 'DM Mono', monospace; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); padding: 0 16px 10px; border-bottom: 1px solid var(--border); }
        tbody td { padding: 12px 16px; border-bottom: 1px solid #1a1a1a; color: #ccc; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--hover); }
    </style>
</head>
<body class="h-full font-sans antialiased">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside style="width:220px;min-width:220px;background:var(--surface);border-right:1px solid var(--border)" class="flex flex-col">
        <div style="padding:18px 20px;border-bottom:1px solid var(--border)">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span style="width:28px;height:28px;background:var(--accent);border-radius:3px" class="flex items-center justify-center">
                    <svg style="width:16px;height:16px;color:black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                </span>
                <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:white;letter-spacing:-0.02em">Clubhouse</span>
            </a>
        </div>

        <nav style="padding:12px 10px;flex:1">
            @php
                $seg = request()->segment(1) ?? '';
                $isDash = request()->routeIs('dashboard');
            @endphp

            <a href="{{ route('dashboard') }}" class="nav-link {{ $isDash ? 'active' : '' }}">
                <span class="dot" style="{{ $isDash ? '' : 'display:none' }}"></span>
                <svg style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('clubs.index') }}" class="nav-link {{ $seg === 'clubs' ? 'active' : '' }}">
                <span class="dot" style="{{ $seg === 'clubs' ? '' : 'display:none' }}"></span>
                <svg style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Clubs
            </a>
            <a href="{{ route('students.index') }}" class="nav-link {{ $seg === 'students' ? 'active' : '' }}">
                <span class="dot" style="{{ $seg === 'students' ? '' : 'display:none' }}"></span>
                <svg style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Students
            </a>
            <a href="{{ route('events.index') }}" class="nav-link {{ $seg === 'events' ? 'active' : '' }}">
                <span class="dot" style="{{ $seg === 'events' ? '' : 'display:none' }}"></span>
                <svg style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Events
            </a>
        </nav>

        <div style="border-top:1px solid var(--border);padding:14px 16px">
            <div class="flex items-center gap-3" style="margin-bottom:10px">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--border);display:flex;align-items:center;justify-content:center;font-family:'DM Mono',monospace;font-size:0.75rem;color:var(--accent);font-weight:500">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <p style="font-size:0.8rem;color:white;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ Auth::user()->name }}</p>
                    <p style="font-size:0.7rem;color:var(--muted);font-family:'DM Mono',monospace">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="font-size:0.75rem;color:var(--muted);background:none;border:none;cursor:pointer;padding:0;transition:color 0.15s" onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--muted)'">
                    Sign out →
                </button>
            </form>
        </div>
    </aside>

    {{-- Content --}}
    <div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:auto">
        <header style="height:56px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 32px;flex-shrink:0">
            <h1 style="font-family:'Syne',sans-serif;font-weight:600;font-size:0.95rem;color:white">@yield('heading', 'Dashboard')</h1>
            <div class="flex items-center gap-3">@yield('header-actions')</div>
        </header>

        <main style="flex:1;padding:28px 32px">
            @if(session('success'))
                <div class="flash-ok">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-err">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="flash-err">
                    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>