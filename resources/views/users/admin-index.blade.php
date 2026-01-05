@extends('layouts.admin')

@section('title', 'Data Masyarakat - Sistem Laporan')

@section('main-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Data Masyarakat</h1>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<!-- Search & Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan nama, NIK, atau email/telepon..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if($search ?? false)
                <a href="{{ route('users.index') }}" data-nav-link class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-all">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
        </div>
    </div>

    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email/Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $user->nik }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email_phone }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @else
        <div class="p-12 text-center">
            <i class="fas fa-users text-5xl text-gray-300 mb-4 block"></i>
            <div class="text-lg font-semibold text-gray-700 mb-2">
                @if($search ?? false)
                    Tidak Ditemukan
                @else
                    Belum Ada Data
                @endif
            </div>
            <p class="text-gray-500">
                @if($search ?? false)
                    Tidak ada masyarakat yang sesuai dengan pencarian "{{ $search }}"
                @else
                    Tidak ada masyarakat yang terdaftar
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
