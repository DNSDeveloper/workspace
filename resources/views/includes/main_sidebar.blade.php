<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style = "z-index: 1040 !important; background-color: #4A71B7;">
    <!-- Brand Logo -->
    <a 
    @can('admin-access')
        href="{{ route('admin.index') }}"
    @endcan
    @can('employee-access')
        href="{{ route('employee.index') }}"
    @endcan
    class="brand-link text-center">
        {{-- <img
            src="/dist/img/AdminLTELogo.png"
            alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: 0.8;"
        /> --}}
        <span class="brand-text font-weight-bold ">Website Absensi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (Auth::user()->employee)
                <img
                    src="{{ asset( Auth::user()->employee->photo) }}"
                    class="img-circle elevation-2"
                    alt="User Image"
                />
                @else
                <img
                    src="{{ asset( Auth::user()->profile) }}"
                    class="img-circle elevation-2"
                    alt="User Image"
                />
                @endif
                
            </div>
            <div class="info">
                <a href="#" class="d-block" style="color: white; opacity:1">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                @can('admin-access')

                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i style="color: white;opacity:1" class="nav-icon fas fa-tachometer-alt"></i>
                        <p style="color: white;opacity: 1;">
                                Dashboard Admin
                            
                        </p>
                    </a>
                </li>
                @include('includes.admin.sidebar_items')
                @endcan
                @can('employee-access')
                <li class="nav-item">
                    <a href="{{ route('employee.index') }}" class="nav-link">
                        <i style="color: white;opacity:1" class="nav-icon fas fa-tachometer-alt"></i>
                        <p style="color: white;opacity: 1;">
                                Dashboard Karyawan
                            
                        </p>
                    </a>
                </li>
                @include('includes.employee.sidebar_items')
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
