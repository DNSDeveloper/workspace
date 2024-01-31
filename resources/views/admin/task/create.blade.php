@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Task</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Halaman Utama</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Add Task
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5 class="text-center mt-2">Add New Task</h5>
                    </div>
                    @include('messages.alerts')
                    <form action="{{ route('admin.task.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="card-body">
                            <fieldset>
                                <div class="form-group">
                                    <label for="">Unit</label>
                                    <select name="unit" id="unit" class="form-control">
                                        <option hidden disabled selected value> -- Choose Unit -- </option>
                                        @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Services</label>
                                    <select name="service" id="service" disabled class="form-control">
                                        <option value="" hidden selected disabled value>-- Select Services --</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="">From</label>
                                            <input type="text" name="user_id" value="{{ auth()->user()->name }}"
                                                class="form-control" readonly>
                                            @error('user_id')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="">To</label>
                                            <select name="employee_id" class="form-control">
                                                <option hidden disabled selected value> -- Pilih Karyawan -- </option>
                                                @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" @if (old('employee')==$employee)
                                                    selected @endif>
                                                    {{ $employee->first_name . ' ' . $employee->last_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                            <div class="text-danger">
                                                Silahkan Pilih Opsi Valid
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Task</label>
                                    <textarea name="task" id="" cols="30" rows="3" class="form-control"></textarea>
                                    @error('task')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="">Category</label>
                                    <select name="category" id="" class="form-control">
                                        <option value="" hidden disabled selected> -- Select Periodik --</option>
                                        <option value="reguler">Reguler</option>
                                        <option value="periodik perminggu">Periodik Perminggu</option>
                                        <option value="periodik perbulan">Periodik Perbulan</option>
                                    </select>
                                </div>

                                <label for="">Prioritas</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_priority" id="exampleRadios1"
                                        value="1" checked>
                                    <label class="form-check-label" for="exampleRadios1">
                                        Ya
                                    </label>
                                    <br>
                                    <input class="form-check-input" type="radio" name="is_priority" id="exampleRadios2"
                                        value="0">
                                    <label class="form-check-label" for="exampleRadios2">
                                        Tidak
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="dob">Deadline</label>
                                    <input type="text" name="deadline" id="dob" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="dob">Note</label>
                                    <textarea name="note" class="form-control" id="" cols="30" rows="5"></textarea>
                                </div>
                            </fieldset>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-flat btn-primary"
                                style="width: 40%; font-size:1.3rem">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- /.content-wrapper -->

@endsection

@section('extra-js')
<script>
    $('#unit').on('change', function () {
                var idUnit = this.value;
                $("#service").html('');
                $.ajax({
                    url: "{{url('api/fetch-service')}}",
                    type: "POST",
                    data: {
                        unit_id: idUnit,
                        _token: '{{csrf_token()}}',
                    },
                    success: function (response) {
                        $('#service').html('<option value="" hidden disabled selected >-- Select Services --</option>');
                        $('#service').removeAttr('disabled')
                        $.each(response, function (key, value) {
                            $("#service").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
</script>

<script>
    $().ready(function() {
        if('{{ old('dob') }}') {
            const dob = moment('{{ old('dob') }}', 'DD-MM-YYYY');
            const join_date = moment('{{ old('join_date') }}', 'DD-MM-YYYY');
            console.log('{{ old('dob') }}')
            $('#dob').daterangepicker({
                "timePicker": true,
                "startDate": dob,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
            $('#join_date').daterangepicker({
                "startDate": join_date,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
        } else {
            $('#dob').daterangepicker({
                "timePicker": true,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY HH:mm"
                }
            });
            $('#join_date').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
        }
        
    });
</script>
@endsection