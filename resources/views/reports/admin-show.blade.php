@extends('layouts.app')

@section('title', 'Detail Laporan - Sistem Laporan')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap { height: 250px; border-radius: 0.5rem; z-index: 1; }
</style>
@endsection

@section('content')
<div class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-dark text-white p-6 flex flex-col fixed h-screen left-0 top-0 z-50">
        <div class="flex items-center gap-3 mb-10 pb-6 border-b border-white/10">
            <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-xl shadow-primary">
                <i class="fas fa-clipboard-list text-white"></i>
            </div>
            <span class="font-bold">Laporan Desa</span>
        </div>

        <ul class="flex-1 space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <i class="fas fa-home w-6 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span>Data Masyarakat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gradient-primary text-white font-medium transition-all">
                    <i class="fas fa-file-alt w-6 text-center"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('recap.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <i class="fas fa-chart-bar w-6 text-center"></i>
                    <span>Rekap</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <i class="fas fa-list w-6 text-center"></i>
                    <span>Log</span>
                </a>
            </li>
        </ul>

        <div class="pt-6 border-t border-white/10">
            <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg mb-4">
                <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-white/60 capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 bg-red-500/10 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/20 transition-all text-sm font-medium">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-6">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <!-- Report Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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

                <!-- Update Status (Admin Only) -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-edit text-primary-600"></i> Update Status
                    </h2>
                    <form id="updateStatusForm" class="flex flex-col md:flex-row gap-4">
                        <select id="statusSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ $report->status === 'done' ? 'selected' : '' }}>Done</option>
                            <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <input type="text" id="notesInput" placeholder="Catatan (opsional)" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                            Update Status
                        </button>
                    </form>
                    <div id="statusMessage" class="mt-3 hidden"></div>
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
                                    <p class="font-medium text-gray-900">Status: {{ ucfirst(str_replace('_', ' ', $log->new_status)) }}</p>
                                    <p class="text-gray-600 text-sm">{{ $log->created_at->format('d F Y H:i') }} oleh {{ $log->changedBy->name ?? '-' }}</p>
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
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
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

    // Update status form
    document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const status = document.getElementById('statusSelect').value;
        const notes = document.getElementById('notesInput').value;
        const msgEl = document.getElementById('statusMessage');

        fetch('{{ route("reports.update-status", $report->id) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status, notes })
        })
        .then(res => res.json())
        .then(data => {
            msgEl.classList.remove('hidden', 'text-red-600', 'text-green-600');
            if (data.status === 'success') {
                msgEl.classList.add('text-green-600');
                msgEl.textContent = 'Status berhasil diperbarui!';
                setTimeout(() => window.location.reload(), 1000);
            } else {
                msgEl.classList.add('text-red-600');
                msgEl.textContent = data.message || 'Gagal memperbarui status';
            }
        })
        .catch(() => {
            msgEl.classList.remove('hidden');
            msgEl.classList.add('text-red-600');
            msgEl.textContent = 'Terjadi kesalahan';
        });
    });
});
</script>
@endsection
