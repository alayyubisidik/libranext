<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Absen Berhasil — {{ config('app.name', 'Libranext') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <div class="min-h-screen flex flex-col">

        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-2xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ route('login') }}" class="flex items-center gap-2 font-bold text-xl text-blue-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Libranext
                </a>
            </div>
        </header>

        <main class="flex-1 flex flex-col items-center justify-center px-4 py-10">

            <div class="w-full max-w-md text-center">

                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-800">Absen Berhasil!</h1>
                <p class="text-gray-500 mt-2">
                    Selamat datang, <span class="font-semibold text-gray-700">{{ $user->name }}</span>.<br>
                    Kunjungan Anda telah tercatat pada {{ now()->translatedFormat('d F Y, H:i') }}.
                </p>

                <a href="{{ route('attendance.index') }}"
                   class="mt-8 inline-flex items-center gap-2 py-3 px-6 rounded-xl border border-transparent text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Absen Member Lain
                </a>

                <p class="mt-4 text-sm text-gray-400">Otomatis kembali dalam <span id="countdown">5</span> detik...</p>

            </div>
        </main>

    </div>

    <script>
        let seconds = 5;
        const el = document.getElementById('countdown');
        const interval = setInterval(function () {
            seconds--;
            el.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = '{{ route('attendance.index') }}';
            }
        }, 1000);
    </script>

</body>
</html>
