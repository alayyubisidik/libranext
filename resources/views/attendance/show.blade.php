<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Konfirmasi Absen — {{ config('app.name', 'Libranext') }}</title>

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
                <a href="{{ route('attendance.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">← Kembali ke Pencarian</a>
            </div>
        </header>

        <main class="flex-1 flex flex-col items-center justify-start py-10 px-4">

            <div class="w-full max-w-md">

                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Absen</h1>
                    <p class="text-gray-500 mt-1">Pastikan ini adalah Anda sebelum melakukan absen.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">

                    <div class="flex justify-center mb-5">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
                        @else
                            <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center border-4 border-blue-100">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 mt-1">{{ $user->member_code }}</p>

                    <span class="inline-block mt-3 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                        Aktif
                    </span>

                    <div class="mt-5 text-left space-y-2 border-t border-gray-100 pt-5">
                        @if($user->date_of_birth)
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-gray-500">Tanggal Lahir:</span>
                                <span class="font-medium text-gray-800">{{ $user->date_of_birth->format('d M Y') }}</span>
                            </div>
                        @endif
                        @if($user->address)
                            <div class="flex items-start gap-3 text-sm">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-gray-500 flex-shrink-0">Alamat:</span>
                                <span class="font-medium text-gray-800">{{ $user->address }}</span>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('attendance.store', $user) }}" class="mt-8">
                        @csrf
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Ya, Absen Sekarang
                        </button>
                    </form>

                    <a href="{{ route('attendance.index') }}"
                       class="mt-3 block w-full text-center py-2.5 px-4 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Bukan saya, cari ulang
                    </a>

                </div>

            </div>
        </main>

    </div>

</body>
</html>
