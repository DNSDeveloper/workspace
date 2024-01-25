@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Absensi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Absensi
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5 class="text-center">Tanggal Absensi</h5>
                    </div>
                    <form action="{{ route('admin.employees.attendance') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="input-group mx-auto" style="width:70%">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" name="date" id="date" class="form-control text-center">
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-flat btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @include('messages.alerts')
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title text-center">
                            @if ($date)
                            Absensi Karyawan berdasarkan rentang tanggal {{ $date }}
                            @else
                            Absensi Karyawan Hari ini
                            @endif
                        </div>

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $employee->first_name . ' ' . $employee->last_name }}
                                    </td>
                                    @if($employee->attendanceToday != null)
                                    <td>{{ $employee->attendanceToday->entry_location }}</td>
                                    <td>{{ $employee->attendanceToday->jam_masuk }}</td>
                                    <td>{{ $employee->attendanceToday->jam_pulang }}</td>
                                    <td>{{ ucwords($employee->attendanceToday->status) }}</td>
                                    <td>{{ $employee->attendanceToday->keterangan    }}</td>
                                    <td>
                                        <div class="modal fade" id="terlambat{{ $employee->attendanceToday->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="terlambatLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content ">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="terlambatLabel">Keterngan Terlambat</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.employees.attendance.terlambat',$employee->attendanceToday->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="">Keterangan</label>
                                                                    <input type="text" class="form-control" value="{{ $employee->attendanceToday->keterangan }}" placeholder="Keterangan" name="keterangan" required >
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button id="submit" type="submit"
                                                                class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <button data-toggle="modal"
                                            data-target="#terlambat{{ $employee->attendanceToday->id }}" {{
                                            $employee->attendanceToday->status == 'terlambat'? '' : 'hidden' }}
                                            class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                    @else
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('extra-js')

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive:true,
            autoWidth: false,
        });
        $('#date').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": true,
            "locale": {
                "format": "DD-MM-YYYY"
            }
        });
    });
</script>
@endsection