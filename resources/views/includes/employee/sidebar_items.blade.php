<li class="nav-item">
    <a href="{{ route('employee.events') }}" class="nav-link">
        <i style="color: white;opacity:1" class="nav-icon fa fa-calendar"></i>
        <p style="color: white;opacity: 1;">
            Events
        </p>
    </a>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color: white; opacity: 1;" class="nav-icon fa fa-calendar-check-o"></i>
        <p style="color: white;opacity:1">
            Absensi
            <i style="color: white; opacity: 1;" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('employee.attendance.create') }}" class="nav-link">
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Absensi Hari ini</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('employee.attendance.index') }}" class="nav-link">
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Daftar Absensi</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color: white; opacity: 1;" class="nav-icon fa fa-calendar-minus-o"></i>
        <p style="color: white;opacity:1">
            Cuti
            <i style="color: white; opacity: 1;" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('employee.leaves.create') }}" class="nav-link">
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Ajukan Cuti</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('employee.leaves.index') }}" class="nav-link">
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Daftar Cuti</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color: white; opacity: 1;" class="nav-icon fa fa-calendar-minus-o"></i>
        <p style="color: white;opacity:1">
            Task
            <i style="color: white; opacity: 1;" class="fas fa-angle-left right"></i>
            {{-- <span class="badge badge-info right">2</span> --}}
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('employee.task') }}" class="nav-link">
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Daftar Task</p>
            </a>
            <a href="{{ route('employee.task.history') }}" class="nav-link">
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">History Task</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('employee.reimbursements.index') }}" class="nav-link">
        <i style="color: white;opacity:1" class="nav-icon fa fa-exchange"></i>
        <p style="color: white;opacity: 1;">
            Reimbursements
        </p>
    </a>
</li>
<!-- <li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color: white; opacity: 1;" class="nav-icon fa fa-calendar-minus-o"></i>
        <p style="color: white;opacity:1">
            Expenses
            <i style="color: white; opacity: 1;" class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">2</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a
            href="{{ route('employee.expenses.create') }}"
                class="nav-link"
            >
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Claim Expense</p>
            </a>
        </li>
        <li class="nav-item">
            <a
            href="{{ route('employee.expenses.index') }}"
                class="nav-link"
            >
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">List of Expenses</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i style="color: white; opacity: 1;" class="nav-icon fa fa-address-card"></i>
        <p style="color: white;opacity:1">
            Self
            <i style="color: white; opacity: 1;" class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">3</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a
                href="{{ route('employee.self.salary_slip') }}"
                class="nav-link"
            >
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Generate Salary slip</p>
            </a>
        </li>
        <li class="nav-item">
            <a
                href="{{ route('employee.self.holidays') }}"
                class="nav-link"
            >
                <i style="color: white; opacity: 1;" class="far fa-circle nav-icon"></i>
                <p style="color: white;opacity:1">Holiday List</p>
            </a>
        </li>
    </ul>
</li> -->