@extends('layouts.app')        

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Register Absensi</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.index') }}">Dashboard Karyawan</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Register Absensi
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
            <div class="justify-content-center d-flex">
                <img class="loading" src="{{ asset('loading.gif') }}" alt="">
            </div>
            <div class="absen" style="display: none">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Absensi Hari ini <?php $time=date("H:i:s"); $dt=date("d-M-Y"); echo $dt." ".$time;?>                
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            @include('messages.alerts')
                            @include('messages.absen_mp3')
    
                            <!-- form start -->
                            @if (!$attendance)
                            <form id="form-attendance" role="form" method="post" action="{{ route('employee.attendance.store', $employee->id) }}" enctype="multipart/form-data">
                            @else
                            <form role="form" method="post" action="{{ route('employee.attendance.update', $attendance->id) }}" >
                                @method('PUT')
                            @endif
                                @csrf
                                <div class="card-body">
                                    <?php if(date('h')>=17) { echo "Absensi Ditutup"; } else { ?>
                                    @if (!$attendance)
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="entry_time">Waktu Absensi</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                name="entry_time"
                                                id="entry_time"
                                                placeholder="--:--:--"
                                                disabled
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="entry_location">Lokasi Absensi</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                id="entry_loc"
                                                placeholder="Locaton Loading..."
                                                disabled
                                                />
                                                <input type="text" name="entry_location" name="entry_location"
                                                id="entry_location" hidden>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="entry_ip">IP Address</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                id="entry_ip"
                                                name="entry_ip"
                                                placeholder="X.X.X.X"
                                                disabled
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="entry_time">Waktu Absensi</label>
                                                <input
                                                type="text"
                                                value="{{ $attendance->created_at->format('d-m-Y,  H:i:s') }}"
                                                class="form-control text-center"
                                                name="entry_time"
                                                id="entry_time"
                                                placeholder="--:--:--"
                                                disabled
                                                style="background: #333; color:#f4f4f4"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="entry_location">Lokasi Absensi</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                name="entry_location"
                                                value="{{ $attendance->entry_location }}"
                                                placeholder="..."
                                                disabled
                                                style="background: #333; color:#f4f4f4"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="entry_ip">IP Address</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                id="entry_ip"
                                                value="{{ $attendance->entry_ip }}"
                                                name="entry_ip"
                                                placeholder="X.X.X.X"
                                                disabled
                                                style="background: #333; color:#f4f4f4"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if (!$registered_attendance)
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exit_time">Waktu Selesai</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                name="exit_time"
                                                id="exit_time"
                                                placeholder="--:--:--"
                                                disabled
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exit_location">Lokasi Selesai</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                id="exit_loc"
                                                @if ($attendance)
                                                placeholder="Loading location..."
                                                    
                                                @else
                                                placeholder="..."
                                                    
                                                @endif
                                                disabled
                                                />
                                                <input type="text" name="exit_location"
                                                id="exit_location" hidden>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exit_ip">IP Address</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                id="exit_ip"
                                                name="exit_ip"
                                                placeholder="X.X.X.X"
                                                disabled
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exit_time">Waktu Selesai</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                name="exit_time"
                                                id="exit_time"
                                                value="{{ $attendance->updated_at->format('d-m-Y,  H:i:s') }}"
                                                placeholder="--:--:--"
                                                disabled
                                                style="background: #333; color:#f4f4f4"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exit_location">Lokasi Selesai</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                name="exit_location"
                                                value="{{ $attendance->exit_location }}"
                                                placeholder="..."
                                                disabled
                                                style="background: #333; color:#f4f4f4"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exit_ip">IP Address</label>
                                                <input
                                                type="text"
                                                class="form-control text-center"
                                                id="exit_ip"
                                                name="exit_ip"
                                                value="{{ $attendance->exit_ip }}"
                                                placeholder="X.X.X.X"
                                                disabled
                                                style="background: #333; color:#f4f4f4"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    
                                </div>
                                <!-- /.card-body -->
                                @if (!$registered_attendance)
                                <div class="card-footer" >
                                    {{-- @if (!$attendance && (date('H')>=8 && date('H') <= 12)) --}}
                                    @if (!$attendance)
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Absen Masuk</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12" style="margin: 0;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    width:100%">    
                                                        <div id="my_camera" style="width: 100%"></div>
                                                        </div>
                                                    </div>
                                                    <input type=button class="btn btn-primary" value="Take Snapshot" onClick="take_snapshot()">
                                                    <input  name="image" hidden class="image-tag">
                                                    <div class="col-12">
                                                        <div id="results">Your captured image will appear here...</div>
                                                    </div>
                                                </div>
                                                <input type="text"id="lat"  name="lat" >
                                                <input type="text"id="long" name="long" >
                                                <div class="col-md-12 text-center">
                                                    <button id="submit-masuk" type="submit" onclick="" hidden class="btn btn-success mt-3">Submit</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>    
                                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal">
                                        Absen Masuk
                                    </button>    
                                    {{-- @elseif(date('H') >= 17) --}}
                                    @else
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content ">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Absen Pulang</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="">Task</label>
                                                    <br>
                                                    <select name="task[]" class="select2" id="select2" multiple="multiple" data-placeholder="Select a Task" style="width: 100%;">
                                                        @foreach ($tasks as $task)
                                                            <option value="{{ $task->task }}">{{ $task->task }}</option>
                                                        @endforeach
                                                        @foreach ($subtasks as $subtask)
                                                            <option value="{{ $subtask->task->task }}">{{ $subtask->task->task }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Report</label>
                                                    <textarea name="report" id="summernote"></textarea>
                                                </div>
                                            </div>
                                            <input type="text" hidden name="employee_id" value="{{ $employee->id }}">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(date('H:i') >= '11:30')
                                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal">
                                        Absen Pulang
                                    </button>
                                    @endif
                                    @endif
                                </div>   
                                @endif
                            <?php } ?>
                                
                            </form>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $('.select2').select2({
    placeholder: "-- Select a Tasks --",
  })
</script>
<script>
    setInterval(() => {
        $('.loading').hide()
        $('.absen').show()
    }, 3000);
</script>
<script>
    $(function () {
    // Summernote
        $('#select2').select2()
        $('#summernote').summernote()
    })
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    {{-- <script type="text/javascript">
    ClassicEditor
    .create( document.querySelector( '#report' ) )
    .then( newEditor => {
        editor = newEditor;
    } )
    .catch( error => {
        console.error( error );
    } );

    document.querySelector( '#submit' ).addEventListener( 'click', () => {
        const editorData = editor.getData();
    } );
    </script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

        <script language="JavaScript">
        // console.log('{{ !$attendance }}')
        if('{{ !$attendance }}') {

        Webcam.set({
            width: 450,
            height: 350,
            // dest_width: 640,
            // dest_height: 480,
            image_format: 'png',
            jpeg_quality: 90
        });

        Webcam.attach( '#my_camera' );
        
        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img style="width:100%" src="'+data_uri+'"/>';
                document.getElementById('submit-masuk').removeAttribute('hidden')
            } );
        }

        $("#form-attendance").submit(function () {
            $("#submit-masuk").attr("disabled", true);
            console.log('click')
        });
    }

    </script>
<script>
$(document).ready(function() {
    if ("geolocation" in navigator) {
        console.log("gl available");
        navigator.geolocation.getCurrentPosition(position => {
            var lat =position.coords.latitude;
            var long =position.coords.longitude;
            $('#lat').val(lat);
            $('#long').val(long);
            $.post("/employee/attendance/get-location", 
            {
                lat: position.coords.latitude,
                lon: position.coords.longitude,
                '_token': $('meta[name=csrf-token]').attr('content'),
            }
            , function(data) {
                console.log(!'{{ $registered_attendance }}')
                    $('#entry_loc').val(data);
                    $('#entry_location').val(data);
                    if('{{ $attendance }}') {
                        $('#exit_loc').val(data);
                        $('#exit_location').val(data);
                    }
            });
        }, function() {
            $('#address').val('Denied Permission to retreive location');
        });
    } else {
        $('#address').html("Location not available");
    }
});
</script>
@endsection