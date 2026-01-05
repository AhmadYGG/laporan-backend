@extends('layouts.app')

@section('title', 'Daftar Laporan - Silapso')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #modalMap { height: 400px; border-radius: 0.5rem; z-index: 1; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h1 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;"><span style="color: #6b4eff;">SIL</span>APSO</h1>
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
                                            <a href="{{ route('reports.show', $report->id) }}" class="px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($report->status === 'pending')
                                                <a href="{{ route('reports.edit', $report->id) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all" title="Hapus">
                                                        <i class="fas fa-trash"></i>
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

<!-- Map Modal -->
<div id="mapModal" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Lokasi Laporan</h3>
            <button onclick="closeMapModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <div id="modalMap"></div>
            <p id="modalAddress" class="mt-3 text-sm text-gray-600"></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let modalMap, modalMarker;

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.location-cell').forEach(function(el) {
        const location = el.dataset.location;
        if (location && location.includes(',')) {
            const [lat, lng] = location.split(',');
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const addr = data.address;
                    const shortAddr = [addr.road, addr.village || addr.suburb, addr.city || addr.town || addr.county]
                        .filter(Boolean).join(', ') || data.display_name;
                    el.innerHTML = `<span title="${data.display_name}">${shortAddr.substring(0, 50)}${shortAddr.length > 50 ? '...' : ''}</span>`;
                })
                .catch(() => {
                    el.textContent = location;
                });
        } else {
            el.textContent = location || '-';
        }
    });
});

function openGoogleMaps(lat, lng) {
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
}

function openMapModal(lat, lng, title) {
    document.getElementById('mapModal').classList.remove('hidden');
    document.getElementById('mapModal').classList.add('flex');
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalAddress').textContent = 'Memuat alamat...';

    setTimeout(() => {
        if (!modalMap) {
            modalMap = L.map('modalMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(modalMap);
        } else {
            modalMap.setView([lat, lng], 15);
        }

        if (modalMarker) modalMap.removeLayer(modalMarker);
        modalMarker = L.marker([lat, lng]).addTo(modalMap);
        modalMap.invalidateSize();

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalAddress').textContent = data.display_name || `${lat}, ${lng}`;
            })
            .catch(() => {
                document.getElementById('modalAddress').textContent = `${lat}, ${lng}`;
            });
    }, 100);
}

function closeMapModal() {
    document.getElementById('mapModal').classList.add('hidden');
    document.getElementById('mapModal').classList.remove('flex');
}

document.getElementById('mapModal').addEventListener('click', function(e) {
    if (e.target === this) closeMapModal();
});

</script>
@endsection
