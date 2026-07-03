<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pendaftaran Wajib Pajak')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#080d1a',
                        },
                        gold: {
                            500: '#d97706',
                            600: '#b45309',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-[#080d1a] text-slate-100 min-h-screen flex flex-col antialiased">
    <!-- Header/Nav -->
    <nav class="border-b border-slate-800 bg-[#0f172a]/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-9 w-9 rounded-lg bg-gradient-to-tr from-amber-500 to-yellow-400 flex items-center justify-center font-bold text-slate-950 shadow-lg shadow-amber-500/20">
                    PJK
                </div>
                <span class="font-semibold text-lg bg-gradient-to-r from-amber-200 to-white bg-clip-text text-transparent">E-Pendaftaran Pajak</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('registrations.index') }}" class="text-sm font-medium text-slate-400 hover:text-slate-200 transition-colors">Daftar Pendaftar</a>
                @auth
                    <span class="text-slate-600">|</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-rose-400 hover:text-rose-300 transition-colors">Keluar</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-300 text-sm flex items-center space-x-2">
                <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-[#080d1a] py-6 text-center text-xs text-slate-600">
        <p>&copy; {{ date('Y') }} E-Pendaftaran Pajak. UAS PPB C030324116.</p>
    </footer>
</body>
</html>
