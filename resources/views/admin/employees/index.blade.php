@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daftar Karyawan</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Daftar Karyawan
                    </li>
                </ol>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container">
        @include('messages.alerts')
        <div class="card card-primary">
            <div class="card-header">
                <div class="card-title text-center">
                    Karyawan
                </div>

            </div>
            <div class="card-body">
                @if ($employees->count())
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Department</th>
                            <th>Jabatan</th>
                            <th>Tanggal Bergabung</th>
                            <th>WFO</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $index => $employee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employee->first_name.' '.$employee->last_name }}</td>
                            <td>{{ $employee->user->username }}</td>
                            <td>{{ $employee->department->name }}</td>
                            <td>{{ $employee->position->name }}</td>
                            <td>{{ $employee->join_date->format('d M, Y') }}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" data-id="{{ $employee->id }}" class="wfo-checkbox"
                                        id="wfo_{{ $employee->id }}" {{ $employee->is_wfo === 1 ? 'checked':'' }}/>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <a href="{{ route('admin.employees.profile', $employee->id) }}"
                                    class="btn btn-flat btn-info">Lihat Profil</a>
                                <button class="btn btn-flat btn-danger" data-toggle="modal"
                                    data-target="#deleteModalCenter{{ $index + 1 }}">Hapus Karyawan</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @for ($i = 1; $i < $employees->count()+1; $i++)
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModalCenter{{ $i }}" tabindex="-1" role="dialog"
                        aria-labelledby="deleteModalCenterTitle1{{ $i }}" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h5 style="text-align: center !important">Yakin ingin dihapus?</h5>
                                    </div>
                                    <div class="card-body text-center d-flex" style="justify-content: center">

                                        <button type="button" class="btn flat btn-secondary"
                                            data-dismiss="modal">Tidak</button>

                                        <form action="{{ route('admin.employees.delete', $employees->get($i-1)->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn flat btn-danger ml-1">Ya</button>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <small>Aksi ini tidak bisa dilakukan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->
                    @endfor
                    @else
                    <div class="alert alert-info text-center" style="width:50%; margin: 0 auto">
                        <h4>Tidak Ada Data</h4>
                    </div>
                    @endif

            </div>
        </div>
        <!-- general form elements -->

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection
@section('extra-js')

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive:true,
            autoWidth: false,
        });

        $('.wfo-checkbox').change(function() {
            var id = $(this).data('id');
            var wfo = $(this).prop('checked') ? 1 : 0; // pastikan nilai 0 atau 1 untuk boolean
            var url = "{{ route('admin.employees.toogleWfo', ':id') }}".replace(':id', id);
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: token,
                    is_wfo: wfo // sesuaikan dengan nama parameter di controller
                },
                success: function(data) {
                    console.log(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>
@endsection