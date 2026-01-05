@extends('layouts.app')

@section('title', 'Login - Sistem Laporan')

@section('content')
<!-- Header -->
<header class="bg-gradient-to-r from-slate-900 to-slate-800 py-4 px-6 shadow-lg">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain">
</header>

<!-- Main Content -->
<main class="min-h-[calc(100vh-56px)] bg-gradient-to-br from-slate-100 via-slate-50 to-white flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        
        <!-- Title -->
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Masuk</h1>
        <p class="text-sm text-slate-600 mb-8">
            Kamu belum punya akun? <a href="{{ route('register') }}" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors">BUAT AKUN</a>
        </p>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 text-sm mb-5 rounded-r-lg">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 text-sm mb-5 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 text-sm mb-5 rounded-r-lg">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <!-- Email/Username -->
            <div class="mb-4">
                <input 
                    type="text" 
                    name="email" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="Email/username"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <!-- Password -->
            <div class="mb-6">
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="Password"
                    required
                >
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-slate-900 to-slate-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-xl hover:shadow-slate-900/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                Log in
            </button>
        </form>
    </div>
</main>
@endsection
