<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Absen Kunjungan — {{ config('app.name', 'Libranext') }}</title>

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
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">← Kembali ke Login</a>
            </div>
        </header>

        <main class="flex-1 flex flex-col items-center justify-start py-10 px-4">

            <div class="w-full max-w-2xl">

                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Absen Kunjungan</h1>
                    <p class="text-gray-500 mt-1">Cari nama atau kode member untuk melakukan absen.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="relative" x-data="attendanceSearch()">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Member</label>
                        <div class="relative">
                            <input
                                id="search"
                                type="text"
                                x-model="query"
                                @input.debounce.400ms="doSearch()"
                                placeholder="Nama atau kode member..."
                                maxlength="100"
                                autocomplete="off"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10"
                            >
                            <div x-show="loading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 5.373 0 12 0v4c0 2.21 1.79 4 4 4s4-1.79 4-4V0c6.627 0 12 5.373 12 12h-4c0-3.313-2.687-6-6-6s-6 2.687-6 6H4z"></path>
                                </svg>
                            </div>
                        </div>

                        <div x-show="searched && !loading" class="mt-4" style="display: none;">

                            <template x-if="members.length === 0">
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm">Tidak ada member aktif yang sesuai.</p>
                                </div>
                            </template>

                            <template x-if="members.length > 0">
                                <div>
                                    <p class="text-xs text-gray-400 mb-3" x-text="`${members.length} member ditemukan`"></p>
                                    <ul class="space-y-2">
                                        <template x-for="member in members" :key="member.id">
                                            <li>
                                                <a :href="`/attendance/member/${member.id}`"
                                                   class="flex items-center gap-4 p-3 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer">
                                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                        <img :src="member.avatar_url" :alt="member.name" class="w-12 h-12 object-cover rounded-full">
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-medium text-gray-900 truncate" x-text="member.name"></p>
                                                        <p class="text-sm text-gray-500" x-text="member.member_code"></p>
                                                    </div>
                                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>

    <script>
        function attendanceSearch() {
            return {
                query: '',
                members: [],
                loading: false,
                searched: false,

                async doSearch() {
                    if (this.query.trim().length < 1) {
                        this.members = [];
                        this.searched = false;
                        return;
                    }

                    this.loading = true;

                    try {
                        const response = await fetch(`/attendance/search?search=${encodeURIComponent(this.query.trim())}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.members = data.members;
                        } else {
                            this.members = [];
                        }
                    } catch (e) {
                        this.members = [];
                    } finally {
                        this.loading = false;
                        this.searched = true;
                    }
                }
            };
        }
    </script>

</body>
</html>
