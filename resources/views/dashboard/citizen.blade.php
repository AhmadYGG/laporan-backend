@extends('layouts.app')

@section('title', 'Dashboard - Sistem Laporan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-xl shadow-primary">
                    <i class="fas fa-clipboard-list text-white"></i>
                </div>
                <span class="text-lg font-bold text-gray-900">Laporan Desa</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Layanan Pengaduan</h1>
            <p class="text-gray-600">Fasilitas Umum Rusak Desa Soso</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Laporan -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-medium">Total Laporan</h3>
                    <i class="fas fa-file-alt text-2xl text-primary-600"></i>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-gray-900">{{ $totalReports }}</span>
                    <span class="text-gray-500 text-sm">laporan</span>
                </div>
            </div>

            <!-- Laporan Ditindaklanjuti -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-medium">Laporan Ditindaklanjuti</h3>
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-gray-900">{{ $processedReports }}</span>
                    <span class="text-gray-500 text-sm">laporan</span>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Laporan</h2>
                <a href="{{ route('reports.create') }}" class="px-4 py-2 bg-gradient-primary text-white text-sm font-medium rounded-lg shadow-primary hover:shadow-primary-lg transition-all">
                    <i class="fas fa-plus"></i> Buat Laporan
                </a>
            </div>

            @if($reports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($reports as $index => $report)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $report->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $report->location }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="line-clamp-1">{{ $report->description }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($report->photo_path)
                                            <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                            @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($report->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($report->status === 'done') bg-green-100 text-green-800
                                            @elseif($report->status === 'rejected') bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4 block"></i>
                    <div class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Laporan</div>
                    <p class="text-gray-500 mb-6">Anda belum membuat laporan apapun. Mulai buat laporan pertama Anda!</p>
                    <a href="{{ route('reports.create') }}" class="inline-flex px-6 py-3 bg-gradient-primary text-white font-medium rounded-lg shadow-primary hover:shadow-primary-lg transition-all">
                        Buat Laporan Sekarang
                    </a>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
