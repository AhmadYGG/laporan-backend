@extends('layouts.admin')

@section('title', 'Daftar Laporan - Sistem Laporan')

@section('main-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Daftar Laporan</h1>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($reports as $index => $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ ($reports->currentPage() - 1) * $reports->perPage() + $index + 1 }}</td>
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
                                    <span class="text-gray-400 text-xs">{{ $report->location }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ Str::limit($report->description ?? '-', 60) }}
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

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reports->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-5xl text-gray-300 mb-4 block"></i>
            <div class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Laporan</div>
            <p class="text-gray-500">Tidak ada laporan yang tersedia</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.location-cell').forEach(function(el) {
        const location = el.dataset.location;
        if (location && location.includes(',')) {
            const [lat, lng] = location.split(',');
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
                .then(res => res.json())
                .then(data => {
                    const addr = data.address;
                    const shortAddr = [addr.road, addr.village || addr.suburb, addr.city || addr.town || addr.county]
                        .filter(Boolean).join(', ') || data.display_name;
                    el.innerHTML = `<span title="${data.display_name}">${shortAddr.substring(0, 40)}${shortAddr.length > 40 ? '...' : ''}</span>`;
                })
                .catch(() => {
                    el.textContent = location;
                });
        } else {
            el.textContent = location || '-';
        }
    });
});
</script>
@endsection
