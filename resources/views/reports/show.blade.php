@extends('layouts.app')

@section('title', 'Detail Laporan - Silapso')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap { height: 250px; border-radius: 0.5rem; z-index: 1; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h1 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;"><span style="color: #6b4eff;">SIL</span>APSO</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <i class="fas fa-arrow-left"></i> Kembali
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
    <main class="max-w-4xl mx-auto px-6 py-8">
        <!-- Back Button -->
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-6">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <!-- Report Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-primary text-white p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $report->title }}</h1>
                        <p class="text-white/80">Dibuat pada {{ $report->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold
                        @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($report->status === 'in_progress') bg-blue-100 text-blue-800
                        @elseif($report->status === 'done') bg-green-100 text-green-800
                        @elseif($report->status === 'rejected') bg-red-100 text-red-800
                        @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Lokasi -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-primary-600"></i> Lokasi
                    </h2>
                    <p id="locationAddress" class="text-gray-700 mb-4">
                        <i class="fas fa-spinner fa-spin text-gray-400"></i> Memuat alamat...
                    </p>
                    
                    @if($report->latitude && $report->longitude)
                        <div id="locationMap" class="mb-3"></div>
                        <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm">
                            <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                        </a>
                    @endif
                </div>

                <!-- Deskripsi -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-align-left text-primary-600"></i> Deskripsi
                    </h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $report->description }}</p>
                </div>

                <!-- Foto -->
                @if($report->photo_path)
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-image text-primary-600"></i> Foto
                        </h2>
                        <img src="{{ asset('storage/' . $report->photo_path) }}" alt="{{ $report->title }}" class="max-w-md h-auto rounded-lg border border-gray-200">
                    </div>
                @endif

                <!-- Info Pelapor -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-user text-primary-600"></i> Informasi Pelapor
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Nama</p>
                            <p class="text-gray-900 font-medium">{{ $report->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Kontak</p>
                            <p class="text-gray-900 font-medium">{{ $report->user->email_phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">NIK</p>
                            <p class="text-gray-900 font-medium">{{ $report->user->nik }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Status -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-primary-600"></i> Riwayat Status
                    </h2>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full"></div>
                                <div class="w-0.5 h-12 bg-gray-300 mt-2"></div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Laporan Dibuat</p>
                                <p class="text-gray-600 text-sm">{{ $report->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>

                        @forelse($report->logs as $log)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 bg-primary-600 rounded-full"></div>
                                    @if(!$loop->last)
                                        <div class="w-0.5 h-12 bg-gray-300 mt-2"></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Status Diubah: {{ ucfirst(str_replace('_', ' ', $log->new_status)) }}</p>
                                    <p class="text-gray-600 text-sm">{{ $log->created_at->format('d F Y H:i') }} oleh {{ $log->changedBy->name }}</p>
                                    @if($log->notes)
                                        <p class="text-gray-700 text-sm mt-2">{{ $log->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Belum ada perubahan status</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex gap-3">
                @if($report->status === 'pending')
                    <a href="{{ route('reports.edit', $report->id) }}" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Laporan
                    </a>
                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-all flex items-center gap-2">
                            <i class="fas fa-trash"></i> Hapus Laporan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const location = "{{ $report->location }}";
    
    if (location && location.includes(',')) {
        const [lat, lng] = location.split(',').map(Number);
        
        // Initialize map
        const map = L.map('locationMap').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        
        // Get address
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('locationAddress').textContent = data.display_name || `${lat}, ${lng}`;
            })
            .catch(() => {
                document.getElementById('locationAddress').textContent = `${lat}, ${lng}`;
            });
    } else {
        document.getElementById('locationAddress').textContent = location || '-';
    }
});
</script>
@endsection
