<aside class="sidebar">
    <div class="sidebar-header">
        <h3>PT. BUS GO SUMUT</h3>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home nav-icon"></i> Home
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.bus.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.bus.index') }}">
                <i class="fas fa-bus nav-icon"></i> Bus
            </a>
        </li>
        {{-- Tambahkan item menu lain di sini dengan format yang sama --}}
        <li class="nav-item {{ Route::is('admin.admins.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ Route('admin.admins.index')}}"> <i class="fas fa-users-cog nav-icon"></i> Data Admin Sistem </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"> <i class="fas fa-calendar-alt nav-icon"></i> Data Keberangkatan </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"> <i class="fas fa-clipboard-list nav-icon"></i> Data Pemesanan </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"> <i class="fas fa-file-alt nav-icon"></i> Laporan </a>
        </li>
        <li class="nav-item mt-auto"> {{-- mt-auto untuk mendorong logout ke bawah --}}
            <a class="nav-link" href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt nav-icon"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>