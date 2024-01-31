<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color:white; opacity:1" class="nav-icon fa fa-unlock-alt"></i>
        <p style="color:white; opacity:1">
            Data Master
            <i style="color:white; opacity:1" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.services.index') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Service</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.positions.index') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Position</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color:white; opacity:1" class="nav-icon fa fa-calendar-check-o"></i>
        <p style="color:white;opacity:1">
            Employees
            <i style="color:white; opacity:1" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">3</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.employees.create') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Add Employee</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.employees.index') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">List Employee</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.employees.attendance') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Absent Employee</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color:white; opacity:1" class="nav-icon fa fa-unlock-alt"></i>
        <p style="color:white;opacity:1">
            List Leaves Employee
            <i style="color:white; opacity:1" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.leaves.index') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Leaves</p>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a
                href="{{ route('admin.expenses.index') }}"
                class="nav-link"
            >
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Expenses</p>
            </a>
        </li> -->
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color:white; opacity:1" class="nav-icon fa fa-tasks"></i>
        <p style="color:white;opacity:1">
            Task Management
            <i style="color:white; opacity:1" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.task.index') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">All Task</p>
            </a>
            <a href="{{ route('admin.task.create') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">Add Task</p>
            </a>
            <a href="{{ route('admin.task.history') }}" class="nav-link">
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p style="color:white;opacity:1">History Task</p>
            </a>
        </li>
    </ul>
</li>

<!-- <li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color:white; opacity:1" class="nav-icon fa fa-calendar-minus-o"></i>
        <p>
            Hari Libur
            <i style="color:white; opacity:1" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a
                href="{{ route('admin.holidays.create') }}"
                class="nav-link"
            >
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p>Tambah Hari Libur</p>
            </a>
        </li>
        <li class="nav-item">
            <a
                href="{{ route('admin.holidays.index') }}"
                class="nav-link"
            >
                <i style="color:white; opacity:1" class="far fa-circle nav-icon"></i>
                <p>Daftar Hari Libur</p>
            </a>
        </li>
    </ul>
</li> -->