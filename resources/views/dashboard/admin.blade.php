@extends('layouts.admin')

@section('title', 'Dashboard Admin - Sistem Laporan')

@section('main-content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat datang, Admin!</h1>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Total Laporan -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-600 font-medium">Total Laporan</h3>
            <i class="fas fa-file-alt text-3xl text-primary-600"></i>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-bold text-gray-900">{{ $totalReports }}</span>
            <span class="text-gray-500 text-sm">laporan</span>
        </div>
    </div>

    <!-- Laporan Selesai -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all cursor-pointer">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-600 font-medium">Laporan Selesai</h3>
            <i class="fas fa-check-circle text-3xl text-green-600"></i>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-bold text-gray-900">{{ $completedReports }}</span>
            <span class="text-gray-500 text-sm">laporan</span>
        </div>
    </div>
</div>

<!-- Recent Reports -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Laporan 24 Jam Terakhir</h2>
    </div>

    @if($recentReports->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentReports as $index => $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $report->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($report->latitude && $report->longitude)
                                    <div class="flex gap-1">
                                        <button onclick="openGoogleMaps({{ $report->latitude }}, {{ $report->longitude }})" class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200" title="Buka di Google Maps">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                        <button onclick="openMapModal({{ $report->latitude }}, {{ $report->longitude }}, '{{ addslashes($report->title) }}')" class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200" title="Lihat di Modal">
                                            <i class="fas fa-map"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">{{ $report->location ?: '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($report->photo_path)
                                    <button onclick="openPhotoModal('{{ asset('storage/' . $report->photo_path) }}', '{{ addslashes($report->title) }}')" class="text-primary-600 hover:text-primary-700 font-medium">
                                        Lihat
                                    </button>
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
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('reports.show', $report->id) }}" class="px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
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
            <p class="text-gray-500">Tidak ada laporan dalam 24 jam terakhir</p>
        </div>
    @endif
</div>
@endsection
