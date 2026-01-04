@extends('layouts.app')

@section('title', 'Rekap Laporan - Sistem Laporan')

@section('content')
<div class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-dark text-white p-6 flex flex-col fixed h-screen left-0 top-0 z-50">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-10 pb-6 border-b border-white/10">
            <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-xl shadow-primary">
                <i class="fas fa-clipboard-list text-white"></i>
            </div>
            <span class="font-bold">Laporan Desa</span>
        </div>

        <!-- Menu -->
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
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all">
                    <i class="fas fa-file-alt w-6 text-center"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('recap.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gradient-primary text-white font-medium transition-all">
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

        <!-- User Info & Logout -->
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
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Rekap</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter & Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tanggal</label>
                    <div class="flex gap-2">
                        <input type="date" id="startDate" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <span class="text-gray-500 py-2">-</span>
                        <input type="date" id="endDate" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button onclick="filterReports()" class="flex-1 md:flex-none px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <button onclick="exportExcel()" class="flex-1 md:flex-none px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all">
                        <i class="fas fa-download"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Tabel Data Laporan</h2>
            </div>

            @if($reports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelapor</th>
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
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $report->user->name }}</td>
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
                                        <a href="{{ route('reports.show', $report->id) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                            Detail
                                        </a>
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
                    <p class="text-gray-500">Tidak ada data laporan yang sesuai dengan filter</p>
                </div>
            @endif
        </div>
    </main>
</div>

<script>
function filterReports() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    let url = '{{ route("recap.index") }}';
    const params = new URLSearchParams();
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    window.location.href = url;
}

function exportExcel() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    let url = '{{ route("recap.export") }}';
    const params = new URLSearchParams();
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    window.location.href = url;
}
</script>
@endsection
