@extends('layouts.app')

@section('title', 'Daftar Laporan - Sistem Laporan')

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
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daftar Laporan Saya</h1>
                <p class="text-gray-600 mt-2">Kelola dan pantau semua laporan yang telah Anda buat</p>
            </div>
            <a href="{{ route('reports.create') }}" class="px-6 py-3 bg-gradient-primary text-white font-semibold rounded-lg shadow-primary hover:shadow-primary-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Buat Laporan Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Reports Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($reports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($reports as $index => $report)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $report->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $report->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $report->location }}</td>
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
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('reports.show', $report->id) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            @if($report->status === 'pending')
                                                <a href="{{ route('reports.edit', $report->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4 block"></i>
                    <div class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Laporan</div>
                    <p class="text-gray-500 mb-6">Anda belum membuat laporan apapun. Mulai buat laporan pertama Anda sekarang!</p>
                    <a href="{{ route('reports.create') }}" class="inline-flex px-6 py-3 bg-gradient-primary text-white font-medium rounded-lg shadow-primary hover:shadow-primary-lg transition-all gap-2">
                        <i class="fas fa-plus"></i> Buat Laporan Sekarang
                    </a>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
