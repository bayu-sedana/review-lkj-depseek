<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Review LKj') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --navy-900: #0a1a33;
            --navy-800: #0f2547;
            --navy-700: #16325c;
            --ocean-600: #1d4ed8;
            --ocean-500: #2563eb;
            --cyan-400: #22d3ee;
            --cyan-300: #67e8f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
        }

        .auth-heading {
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .auth-label {
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .auth-input {
            font-weight: 500;
        }

        .auth-button {
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .auth-bg {
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(34, 211, 238, 0.18), transparent 60%),
                radial-gradient(900px 500px at 110% 110%, rgba(37, 99, 235, 0.25), transparent 60%),
                linear-gradient(160deg, var(--navy-900) 0%, var(--navy-800) 55%, var(--navy-700) 100%);
        }

        .auth-card {
            background: #ffffff;
            box-shadow:
                0 1px 2px rgba(10, 26, 51, 0.06),
                0 24px 60px -20px rgba(10, 26, 51, 0.45);
        }

        .auth-input:focus {
            outline: none;
            border-color: var(--cyan-400);
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.18);
        }

        .auth-button {
            background: linear-gradient(135deg, var(--ocean-600) 0%, var(--ocean-500) 100%);
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        }

        .auth-button:hover {
            filter: brightness(1.06);
            box-shadow: 0 12px 28px -12px rgba(29, 78, 216, 0.75);
        }

        .auth-button:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body class="auth-bg min-h-screen antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur mb-4">
                    <svg class="w-7 h-7 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M9 15l2 2 4-4"/>
                    </svg>
                </div>
                <h1 class="auth-heading text-2xl sm:text-3xl text-white">
                    Review LKj Satker
                </h1>
                <p class="mt-2 text-sm text-cyan-100/70 font-medium">
                    Sistem Monitoring &amp; Evaluasi Laporan Kinerja
                </p>
            </div>

            <div class="auth-card rounded-2xl p-8 sm:p-10">
                {{ $slot }}
            </div>

            <p class="mt-8 text-center text-xs text-cyan-100/50 font-medium">
                &copy; {{ date('Y') }} Review LKj. Seluruh hak cipta dilindungi.
            </p>
        </div>
    </div>
</body>
</html>
