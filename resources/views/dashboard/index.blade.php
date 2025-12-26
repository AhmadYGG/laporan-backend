@extends('layouts.app')

@section('title', 'Dashboard - Sistem Laporan')

@section('content')
<div class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-72 bg-gradient-dark text-white p-6 flex flex-col fixed h-screen left-0 top-0 z-50">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-10 pb-6 border-b border-white/10">
            <div class="w-11 h-11 bg-gradient-primary rounded-xl flex items-center justify-center text-2xl shadow-primary">
                📋
            </div>
            <span class="text-lg font-bold">Laporan App</span>
        </div>

        <!-- Menu -->
        <ul class="flex-1 space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-primary text-white font-medium transition-all">
                    <span class="text-lg w-6 text-center">🏠</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <span class="text-lg w-6 text-center">📝</span>
                    <span>Laporan Saya</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reports.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <span class="text-lg w-6 text-center">➕</span>
                    <span>Buat Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <span class="text-lg w-6 text-center">🔔</span>
                    <span>Notifikasi</span>
                </a>
            </li>
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <span class="text-lg w-6 text-center">👥</span>
                    <span>Kelola Pengguna</span>
                </a>
            </li>
            @endif
        </ul>

        <!-- User Info & Logout -->
        <div class="pt-6 border-t border-white/10">
            <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl mb-4">
                <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-white/60 capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-red-500/10 text-red-400 border border-red-500/30 rounded-xl hover:bg-red-500/20 transition-all">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-500">Berikut adalah ringkasan aktivitas laporan Anda</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer">
                <div class="w-12 h-12 bg-primary-500/10 rounded-xl flex items-center justify-center text-2xl mb-4">
                    📋
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-gray-500 text-sm">Total Laporan</div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer">
                <div class="w-12 h-12 bg-yellow-500/10 rounded-xl flex items-center justify-center text-2xl mb-4">
                    ⏳
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-gray-500 text-sm">Menunggu Proses</div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer">
                <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center text-2xl mb-4">
                    ✅
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-gray-500 text-sm">Selesai</div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer">
                <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center text-2xl mb-4">
                    ❌
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-gray-500 text-sm">Ditolak</div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Laporan Terbaru</h2>
                <a href="{{ route('reports.create') }}" class="px-4 py-2 bg-gradient-primary text-white text-sm font-medium rounded-xl shadow-primary hover:shadow-primary-lg hover:-translate-y-0.5 transition-all">
                    ➕ Buat Laporan
                </a>
            </div>
            <div class="p-6">
                <div class="text-center py-12">
                    <div class="text-5xl mb-4">📭</div>
                    <div class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Laporan</div>
                    <p class="text-gray-500 mb-6">Anda belum membuat laporan apapun. Mulai buat laporan pertama Anda!</p>
                    <a href="{{ route('reports.create') }}" class="inline-flex px-6 py-3 bg-gradient-primary text-white font-medium rounded-xl shadow-primary hover:shadow-primary-lg hover:-translate-y-0.5 transition-all">
                        Buat Laporan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
