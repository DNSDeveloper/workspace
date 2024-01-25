@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Position</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Data Position
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
    <div class="container-fluid">
        @include('messages.alerts')
        <div class="modal fade" id="addservice" tabindex="-1" role="dialog" aria-labelledby="addserviceLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addserviceLabel">Add Position</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.positions.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" id="" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title text-center">
                            Positions
                        </div>
                    </div>
                    <div class="float-right m-3">
                        <button class="btn btn-primary float-right" data-toggle="modal" data-target="#addservice">
                            Add Position <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @foreach ($positions as $position)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $position->name }}</td>
                                <td>
                                    <div class="modal fade" id="updateposition{{ $position->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="updatepositionLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content ">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updatepositionLabel">Add Service</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.positions.update', $position->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label for="">Name</label>
                                                            <input type="text" value="{{ $position->name }}" name="name" class="form-control" id=""
                                                                required>
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
                                    <button class="btn btn-success" data-target="#updateposition{{ $position->id }}" data-toggle="modal"> <i class="fas fa-edit"></i> </button>
                                </td>
                            </tr>
                            @endforeach
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
    });
</script>
@endsection