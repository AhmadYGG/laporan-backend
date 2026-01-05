@extends('layouts.app')

@section('title', 'Daftar - Sistem Laporan')

@section('content')
<!-- Header -->
<header class="bg-gradient-to-r from-slate-900 to-slate-800 py-4 px-6 shadow-lg">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain">
</header>

<!-- Main Content -->
<main class="min-h-[calc(100vh-56px)] bg-gradient-to-br from-slate-100 via-slate-50 to-white flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-sm">
        
        <!-- Title -->
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Daftar</h1>
        <p class="text-sm text-slate-600 mb-8">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors">MASUK</a>
        </p>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 text-sm mb-5 rounded-r-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 text-sm mb-5 rounded-r-lg">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <!-- NIK -->
            <div class="mb-4">
                <input 
                    type="text" 
                    name="nik" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="NIK (Nomor Induk Kependudukan)"
                    value="{{ old('nik') }}"
                    maxlength="50"
                    required
                >
            </div>

            <!-- Email/Phone -->
            <div class="mb-4">
                <input 
                    type="text" 
                    name="email_phone" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="Email/No. Telepon"
                    value="{{ old('email_phone') }}"
                    required
                >
            </div>

            <!-- Name -->
            <div class="mb-4">
                <input 
                    type="text" 
                    name="name" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="Nama Lengkap"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <!-- Password -->
            <div class="mb-4">
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="Password"
                    required
                >
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-slate-900 focus:ring-0 transition-colors"
                    placeholder="Konfirmasi Password"
                    required
                >
            </div>

            <!-- Register Button -->
            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-slate-900 to-slate-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-xl hover:shadow-slate-900/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                Daftar
            </button>
        </form>
    </div>
</main>
@endsection
