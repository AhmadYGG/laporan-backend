<aside class="w-64 bg-gradient-dark text-white p-6 flex flex-col fixed h-screen left-0 top-0 z-50">
    <!-- Logo -->
    <div class="flex items-center justify-center mb-10 pb-6 border-b border-white/10">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full max-w-[180px] h-auto object-contain">
    </div>

    <!-- Menu -->
    <ul class="flex-1 space-y-2">
        @php
            $currentRoute = request()->route()->getName();
            $menuItems = [
                ['route' => 'dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard'],
                ['route' => 'users.index', 'icon' => 'fa-users', 'label' => 'Data Masyarakat'],
                ['route' => 'reports.index', 'icon' => 'fa-file-alt', 'label' => 'Laporan'],
                ['route' => 'recap.index', 'icon' => 'fa-chart-bar', 'label' => 'Rekap'],
                ['route' => 'logs.index', 'icon' => 'fa-list', 'label' => 'Log'],
            ];
        @endphp

        @foreach($menuItems as $item)
            @php
                $isActive = str_starts_with($currentRoute, explode('.', $item['route'])[0]);
            @endphp
            <li>
                <a href="{{ route($item['route']) }}" 
                   data-nav-link
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                          {{ $isActive 
                             ? 'bg-gradient-primary text-white font-medium' 
                             : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} w-6 text-center"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
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
