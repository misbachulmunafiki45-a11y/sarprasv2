<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    @php
                        $logoSvg = public_path('assets/admin/logo-smk.svg');
                        $logoPng = public_path('assets/admin/logo-smk.png');
                        $ver = file_exists($logoSvg) ? filemtime($logoSvg) : (file_exists($logoPng) ? filemtime($logoPng) : null);
                        $pngValid = file_exists($logoPng) && function_exists('getimagesize') && @getimagesize($logoPng) !== false;
                    @endphp
                    @if (file_exists($logoSvg))
                        <img src="/assets/admin/logo-smk.svg{{ $ver ? ('?v=' . $ver) : '' }}" alt="Logo Sekolah" style="display:block;width:100%;height:100%;max-width:100%;max-height:100%;object-fit:contain;">
                    @elseif ($pngValid)
                        <img src="/assets/admin/logo-smk.png{{ $ver ? ('?v=' . $ver) : '' }}" alt="Logo Sekolah" style="display:block;width:100%;height:100%;max-width:100%;max-height:100%;object-fit:contain;image-rendering:auto;">
                    @else
                        <i class="fa-solid fa-shield"></i>
                    @endif
                </div>
                <div class="sidebar-brand-text mx-3">ADMIN SARPRAS<sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item {{ request()->is('admin/resident*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.resident.index') }}">
                    <i class="fa-regular fa-address-card"></i>
                    <span>Data Pelapor</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item {{ request()->is('admin/report-category*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.report-category.index') }}">
                    <i class="fa-solid fa-table"></i>
                    <span>Data Kategori</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item {{ request()->is('admin/report*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.report.index') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Data Laporan</span></a>
            </li>


        </ul>
