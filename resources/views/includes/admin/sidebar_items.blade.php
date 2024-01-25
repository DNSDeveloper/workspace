<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-unlock-alt"></i>
        <p>
            Data Master
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">2</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.services.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Service</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.positions.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Position</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-calendar-check-o"></i>
        <p>
            Employees
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">3</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.employees.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Employee</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.employees.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>List Employee</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.employees.attendance') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Absent Employee</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-unlock-alt"></i>
        <p>
            List Leaves Employee
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">2</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.leaves.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Leaves</p>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a
                href="{{ route('admin.expenses.index') }}"
                class="nav-link"
            >
                <i class="far fa-circle nav-icon"></i>
                <p>Expenses</p>
            </a>
        </li> -->
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-tasks"></i>
        <p>
            Task Management
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">2</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.task.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>All Task</p>
            </a>
            <a href="{{ route('admin.task.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Task</p>
            </a>
            <a href="{{ route('admin.task.history') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>History Task</p>
            </a>
        </li>
    </ul>
</li>

<!-- <li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-calendar-minus-o"></i>
        <p>
            Hari Libur
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">2</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a
                href="{{ route('admin.holidays.create') }}"
                class="nav-link"
            >
                <i class="far fa-circle nav-icon"></i>
                <p>Tambah Hari Libur</p>
            </a>
        </li>
        <li class="nav-item">
            <a
                href="{{ route('admin.holidays.index') }}"
                class="nav-link"
            >
                <i class="far fa-circle nav-icon"></i>
                <p>Daftar Hari Libur</p>
            </a>
        </li>
    </ul>
</li> -->