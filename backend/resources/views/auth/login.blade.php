<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Pendaftaran Pajak</title>
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
<body class="bg-[#080d1a] text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="max-w-md w-full">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 rounded-xl bg-gradient-to-tr from-amber-500 to-yellow-400 items-center justify-center font-bold text-slate-950 shadow-lg shadow-amber-500/20 text-lg mb-3">
                PJK
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-100">E-Pendaftaran Pajak</h2>
            <p class="text-xs text-slate-400 mt-1">Halaman khusus Administrator Pajak</p>
        </div>

        <!-- Login Card -->
        <div class="bg-[#0f172a] border border-slate-800 rounded-2xl shadow-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold text-slate-200 mb-6">Masuk ke Dashboard</h3>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                @if($errors->any())
                    <div class="p-3 rounded-lg border border-rose-500/20 bg-rose-500/10 text-rose-300 text-xs">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@pajak.com" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-amber-500 cursor-pointer">
                    <label for="remember" class="ml-2 text-xs text-slate-400 cursor-pointer select-none">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 rounded-lg shadow-md shadow-amber-500/10 transition-all hover:scale-[1.01] mt-2">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>
