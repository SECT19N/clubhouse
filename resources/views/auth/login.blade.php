<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Clubhouse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&family=DM+Mono&display=swap" rel="stylesheet">
    <style>
        body { background:#0f0f0f; color:#e5e5e5; font-family:'DM Sans',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .card { background:#171717; border:1px solid #262626; border-radius:8px; width:100%; max-width:380px; padding:36px; }
        .input { width:100%; background:#0f0f0f; border:1px solid #2a2a2a; color:#e5e5e5; border-radius:4px; padding:9px 12px; font-size:0.875rem; font-family:'DM Sans',sans-serif; transition:border-color 0.15s; box-sizing:border-box; }
        .input:focus { outline:none; border-color:#444; }
        .label { display:block; font-size:0.7rem; color:#6b6b6b; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.06em; font-family:'DM Mono',monospace; }
        .btn { width:100%; background:#e8ff47; color:#000; font-weight:600; padding:10px; border-radius:4px; font-size:0.875rem; cursor:pointer; border:none; font-family:'DM Sans',sans-serif; transition:opacity 0.15s; }
        .btn:hover { opacity:0.85; }
        .err { background:#1f0606; border:1px solid #501010; color:#fca5a5; border-radius:4px; padding:10px 14px; font-size:0.8rem; margin-bottom:16px; }
    </style>
</head>
<body>
<div class="card">
    <div style="margin-bottom:28px">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
            <span style="width:26px;height:26px;background:#e8ff47;border-radius:3px;display:flex;align-items:center;justify-content:center">
                <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="black" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            </span>
            <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:white">Clubhouse</span>
        </div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.4rem;color:white;margin:0">Sign in</h2>
        <p style="color:#6b6b6b;font-size:0.8rem;margin-top:4px">Welcome back</p>
    </div>

    @if($errors->any())
        <div class="err">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div style="margin-bottom:16px">
            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input" placeholder="you@example.com" required autofocus>
        </div>
        <div style="margin-bottom:24px">
            <label class="label">Password</label>
            <input type="password" name="password" class="input" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Sign in</button>
    </form>

    <p style="text-align:center;margin-top:20px;font-size:0.8rem;color:#6b6b6b">
        No account? <a href="{{ route('register') }}" style="color:#e8ff47;text-decoration:none">Register</a>
    </p>
</div>
</body>
</html>