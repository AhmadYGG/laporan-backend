<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Laporan')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#667eea',
                            600: '#5b21b6',
                            700: '#764ba2',
                            800: '#4c1d95',
                            900: '#3c1a8c',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-dark { background: linear-gradient(135deg, #232526 0%, #414345 100%); }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        #modalMap { height: 400px; border-radius: 0.5rem; z-index: 1; }
        
        /* Page transition */
        #main-content {
            transition: opacity 0.15s ease-in-out;
        }
        #main-content.loading {
            opacity: 0.5;
        }
    </style>
    
    @yield('styles')
</head>
<body class="font-inter bg-gray-50 text-gray-900 min-h-screen">
    <div class="min-h-screen flex bg-gray-50">
        <!-- Sidebar -->
        @include('partials.admin-sidebar')

        <!-- Main Content -->
        <main id="main-content" class="flex-1 ml-64 p-8">
            @yield('main-content')
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

    <!-- Photo Modal -->
    <div id="photoModal" class="fixed inset-0 bg-black/80 z-[9999] hidden items-center justify-center">
        <div class="relative max-w-4xl max-h-[90vh] mx-4">
            <button onclick="closePhotoModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[85vh] rounded-lg cursor-zoom-in" onclick="toggleZoom(this)">
            <p id="photoTitle" class="text-white text-center mt-3"></p>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // SPA-like Navigation
        const AdminNav = {
            init() {
                this.bindNavLinks();
                window.addEventListener('popstate', (e) => this.handlePopState(e));
            },

            bindNavLinks() {
                document.querySelectorAll('[data-nav-link]').forEach(link => {
                    link.removeEventListener('click', this.handleNavClick);
                    link.addEventListener('click', (e) => this.handleNavClick(e));
                });
            },

            handleNavClick(e) {
                e.preventDefault();
                const url = e.currentTarget.href;
                AdminNav.navigateTo(url);
            },

            async navigateTo(url, pushState = true) {
                const mainContent = document.getElementById('main-content');
                mainContent.classList.add('loading');

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-PJAX': 'true'
                        }
                    });

                    if (!response.ok) throw new Error('Network error');

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract main content
                    const newContent = doc.getElementById('main-content');
                    if (newContent) {
                        mainContent.innerHTML = newContent.innerHTML;
                    }

                    // Update active sidebar link
                    this.updateActiveLink(url);

                    // Update page title
                    const newTitle = doc.querySelector('title');
                    if (newTitle) {
                        document.title = newTitle.textContent;
                    }

                    // Push to history
                    if (pushState) {
                        history.pushState({ url }, '', url);
                    }

                    // Re-bind nav links in new content
                    this.bindNavLinks();
                    
                    // Execute inline scripts
                    this.executeScripts(mainContent);

                } catch (error) {
                    console.error('Navigation error:', error);
                    window.location.href = url;
                } finally {
                    mainContent.classList.remove('loading');
                }
            },

            updateActiveLink(url) {
                const path = new URL(url).pathname;
                document.querySelectorAll('[data-nav-link]').forEach(link => {
                    const linkPath = new URL(link.href).pathname;
                    const isActive = path === linkPath || (linkPath !== '/' && path.startsWith(linkPath));
                    
                    if (isActive) {
                        link.classList.remove('text-white/70', 'hover:bg-white/10', 'hover:text-white');
                        link.classList.add('bg-gradient-primary', 'text-white', 'font-medium');
                    } else {
                        link.classList.add('text-white/70', 'hover:bg-white/10', 'hover:text-white');
                        link.classList.remove('bg-gradient-primary', 'text-white', 'font-medium');
                    }
                });
            },

            handlePopState(e) {
                if (e.state && e.state.url) {
                    this.navigateTo(e.state.url, false);
                }
            },

            executeScripts(container) {
                container.querySelectorAll('script').forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => {
                        newScript.setAttribute(attr.name, attr.value);
                    });
                    newScript.textContent = oldScript.textContent;
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }
        };

        document.addEventListener('DOMContentLoaded', () => AdminNav.init());

        // Map Modal Functions
        let modalMap, modalMarker;

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

        document.getElementById('mapModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeMapModal();
        });

        // Photo Modal Functions
        function openPhotoModal(src, title) {
            document.getElementById('modalPhoto').src = src;
            document.getElementById('modalPhoto').alt = title;
            document.getElementById('photoTitle').textContent = title;
            document.getElementById('photoModal').classList.remove('hidden');
            document.getElementById('photoModal').classList.add('flex');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
            document.getElementById('photoModal').classList.remove('flex');
            document.getElementById('modalPhoto').classList.remove('scale-150');
        }

        function toggleZoom(img) {
            img.classList.toggle('scale-150');
            img.classList.toggle('cursor-zoom-out');
            img.classList.toggle('cursor-zoom-in');
        }

        document.getElementById('photoModal')?.addEventListener('click', function(e) {
            if (e.target === this) closePhotoModal();
        });
    </script>
    
    @yield('scripts')
</body>
</html>
