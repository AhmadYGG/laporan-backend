@extends('layouts.admin')

@section('title', 'Log Aktivitas - Sistem Laporan')

@section('main-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
</div>

<!-- Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form action="{{ route('logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan judul laporan, admin, atau status..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if($search ?? false)
                <a href="{{ route('logs.index') }}" data-nav-link class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-all">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log
        </div>
    </div>

    @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Laporan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status Baru</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diubah Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($logs as $index => $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                @if($log->report)
                                    <a href="{{ route('reports.show', $log->report_id) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ Str::limit($log->report->title, 30) }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Laporan dihapus</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    @if($log->new_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($log->new_status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($log->new_status === 'done') bg-green-100 text-green-800
                                    @elseif($log->new_status === 'rejected') bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $log->new_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->changedBy->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    @else
        <div class="p-12 text-center">
            <i class="fas fa-list text-5xl text-gray-300 mb-4 block"></i>
            <div class="text-lg font-semibold text-gray-700 mb-2">
                @if($search ?? false)
                    Tidak Ditemukan
                @else
                    Belum Ada Log
                @endif
            </div>
            <p class="text-gray-500">
                @if($search ?? false)
                    Tidak ada log yang sesuai dengan pencarian "{{ $search }}"
                @else
                    Belum ada aktivitas perubahan status laporan
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
