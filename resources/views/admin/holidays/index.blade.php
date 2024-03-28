@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daftar Hari Libur</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Daftar Hari Libur
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('messages.alerts')
                <div class="modal fade" id="addholiday" tabindex="-1" role="dialog" aria-labelledby="addtaskLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addtaskLabel">Tambah Hari Libur</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.holidays.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="">Nama</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                                        @error('name')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lebih Dari Sehari ?</label>
                                        <select name="multiple-days" class="form-control" onchange="showInput()">
                                            <option value="no">Tidak</option>
                                            <option value="yes">Ya</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="single-date">
                                        <label for="">Seleksi Tanggal </label>
                                        <input type="text" name="date" id="date1" class="form-control">
                                    </div>
                                    <div class="form-group hide-input" id="multiple-date">
                                        <label for="">Rentang Tanggal</label>
                                        <input type="text" name="date_range" id="date2" class="form-control">
                                        @error('date_range')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Hari Libur</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button class="btn btn-primary" data-target="#addholiday" data-toggle="modal">
                                <i class="fas fa-plus"></i>
                                Tambah Hari Libur
                            </button>
                        </div>
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($holidays as $index => $holiday)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $holiday->name }}</td>
                                    <td>
                                        {{ date('d M Y',strtotime($holiday->start_date)) }}
                                        @if($holiday->end_date)
                                        - {{ date('d M Y',strtotime($holiday->end_date)) }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.holidays.edit', $holiday->id) }}"
                                            class="btn btn-flat btn-warning">Edit</a>
                                        <button class="btn btn-flat btn-danger" data-toggle="modal"
                                            data-target="#deleteModalCenter{{ $index+1 }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @for ($i = 1; $i < $holidays->count()+1; $i++)
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

                                                <form
                                                    action="{{ route('admin.holidays.delete', $holidays->get($i-1)->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn flat btn-danger ml-1">Ya</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('extra-js')
<script>
    $(document).ready(function(){
    $('#dataTable').DataTable({
        responsive:true,
        autoWidth: false,
    });
});
</script>

<script>
    $(document).ready(function() {
        $('#date1').daterangepicker({
            "showDropdowns": true,
            "singleDatePicker": true,
            "locale": {
                "format": "DD-MM-YYYY",
            }
        });
        $('#date2').daterangepicker({
            "showDropdowns": true,
            "locale": {
                "format": "DD-MM-YYYY",
            }
        });

    });

    function showInput() {
        $('#single-date').toggleClass('hide-input');
        $('#multiple-date').toggleClass('hide-input');
    }
</script>
@endsection