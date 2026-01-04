@extends('layouts.app')

@section('title', 'Buat Laporan - Sistem Laporan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-xl shadow-primary">
                    <i class="fas fa-clipboard-list text-white"></i>
                </div>
                <span class="text-lg font-bold text-gray-900">Laporan Desa</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <i class="fas fa-history"></i> Riwayat
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
    <main class="max-w-4xl mx-auto px-6 py-12">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Layanan Pengaduan Fasilitas</h1>
            <p class="text-xl text-gray-600 mb-2">Umum Rusak Desa Soso</p>
            <p class="text-gray-500">Sampaikan laporan Anda langsung kepada instansi pemerintah berwenang</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-red-900 mb-2">Terjadi Kesalahan</h3>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            @csrf

            <!-- Lokasi -->
            <div class="mb-8">
                <label for="location" class="block text-sm font-semibold text-gray-900 mb-3">
                    <i class="fas fa-map-marker-alt text-primary-600 mr-2"></i>Lokasi
                </label>
                <input 
                    type="text" 
                    id="location" 
                    name="location" 
                    placeholder="Masukkan lokasi fasilitas yang rusak"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('location') border-red-500 @enderror"
                    value="{{ old('location') }}"
                    required
                >
                @error('location')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ringkasan Singkat (Judul) -->
            <div class="mb-8">
                <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">
                    <i class="fas fa-heading text-primary-600 mr-2"></i>Ringkasan Singkat
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    placeholder="Contoh: Jalan Rusak Parah di Depan Kantor Desa"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('title') border-red-500 @enderror"
                    value="{{ old('title') }}"
                    required
                >
                @error('title')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-8">
                <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">
                    <i class="fas fa-align-left text-primary-600 mr-2"></i>Deskripsi
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="6"
                    placeholder="Jelaskan secara detail kondisi fasilitas yang rusak, dampak yang ditimbulkan, dan informasi penting lainnya..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none @error('description') border-red-500 @enderror"
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Gambar -->
            <div class="mb-8">
                <label for="photo" class="block text-sm font-semibold text-gray-900 mb-3">
                    <i class="fas fa-image text-primary-600 mr-2"></i>Upload Gambar
                </label>
                <div class="relative">
                    <input 
                        type="file" 
                        id="photo" 
                        name="photo" 
                        accept="image/*"
                        class="hidden"
                        onchange="previewImage(event)"
                    >
                    <label for="photo" class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 hover:bg-primary-50 transition-all @error('photo') border-red-500 @enderror">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3 block"></i>
                            <p class="text-gray-600 font-medium">Klik untuk upload atau drag & drop</p>
                            <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG, GIF (Max 5MB)</p>
                        </div>
                    </label>
                    @error('photo')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Gambar -->
                <div id="imagePreview" class="mt-4 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                    <div class="relative inline-block">
                        <img id="previewImg" src="" alt="Preview" class="max-w-xs h-auto rounded-lg border border-gray-200">
                        <button type="button" onclick="removeImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-8 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="px-12 py-3 bg-gradient-primary text-white font-semibold rounded-lg shadow-primary hover:shadow-primary-lg hover:-translate-y-0.5 transition-all flex items-center gap-2"
                >
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex gap-4">
                <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Informasi Penting</h3>
                    <ul class="text-blue-800 text-sm space-y-1">
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Pastikan informasi yang Anda berikan akurat dan lengkap</li>
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Sertakan foto yang jelas untuk memudahkan penanganan</li>
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Laporan Anda akan diproses dalam waktu 1-3 hari kerja</li>
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Anda dapat memantau status laporan di menu Riwayat</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('photo').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}
</script>
@endsection
