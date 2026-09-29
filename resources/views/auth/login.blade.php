<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk Sistem - {{ setting('shop_name', 'WarungDigital') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f8fafc;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
    <div class="text-center mb-8">
      <div
        class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-4 shadow-lg shadow-emerald-500/20">
        <i data-lucide="lock" class="w-7 h-7"></i>
      </div>
      <h1 class="text-2xl font-extrabold text-slate-900">Masuk Akun Pengguna</h1>
      <p class="text-xs text-slate-400 mt-1">Sistem Manajemen Warung & POS Kasir Internal</p>
    </div>

    @if($errors->any())
    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
      {{ $errors->first() }}
    </div>
    @endif

    @if(session('success'))
    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
      {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Email Pengguna</label>
        <div class="relative">
          <i data-lucide="mail" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
          <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@warung.com"
            class="w-full text-xs pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-emerald-500 focus:ring-1 focus:ring-emerald-500">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
        <div class="relative">
          <i data-lucide="key" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
          <input type="password" name="password" required placeholder="••••••••"
            class="w-full text-xs pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-emerald-500 focus:ring-1 focus:ring-emerald-500">
        </div>
      </div>

      <div class="flex items-center justify-between pt-1">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 rounded border-slate-300">
          <span class="text-xs text-slate-500 font-medium">Ingat Saya</span>
        </label>
        <a href="{{ route('home') }}" class="text-xs font-bold text-emerald-600 hover:underline">Kembali ke Depan</a>
      </div>

      <button type="submit"
        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2">
        <i data-lucide="log-in" class="w-4 h-4"></i> Masuk ke Dashboard
      </button>
    </form>

    <div class="mt-8 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
      <div class="font-bold text-slate-700 mb-1">Akun Percobaan Bawaan:</div>
      <div>👑 <b>Admin:</b> admin@warung.com / admin123</div>
      <div>💼 <b>Karyawan:</b> kasir@warung.com / kasir123</div>
    </div>
  </div>

  <script>
    lucide.createIcons();
  </script>
</body>

</html>