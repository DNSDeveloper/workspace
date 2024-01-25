@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detail Task</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}">Dashboard Karyawan</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Detail Task
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="modal fade" id="updatetask{{ $task->id }}" tabindex="-1"
            role="dialog" aria-labelledby="updatetaskLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updatetaskLabel">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('employee.task.update',$task->id ) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="">Status</label>
                                <select class="form-control" name="status" id="status" onchange="toggleReportTask(this)">
                                    <option value="" hidden disabled selected value>--
                                        Choose Status --</option>
                                    <option value="on progress" {{ $task->status == 'on progress' ? 'hidden' : '' }}>On Progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            <div id="report-{{ $task->id }}">
                                <div class="form-group mb-3">
                                    <label for="">Attachment</label>
                                    <input type="file" name="file" class="form-control" id="">
                                </div>
                                    
                                <div class="form-group mb-3">
                                    <label for="">Report</label>
                                    <textarea name="report" class="form-control" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            @include('messages.alerts')
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs d-flex justify-content-around" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Task</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Subtask</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <h4 class="mt-3">Task</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>
                                            From
                                        </b>
                                    </td>
                                    <td>{{ $task->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>
                                            Task
                                        </b>
                                    </td>
                                    <td>{{ $task->task }}</td>
                                </tr>
                                <tr>
                                    <td><b>
                                            Unit
                                        </b>
                                    </td>
                                    <td> {{ $task->unit->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>
                                            Service
                                        </b>
                                    </td>
                                    <td>{{ $task->service->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>
                                            Status
                                        </b>
                                    </td>
                                    <td> @if($task->status === 'open')
                                        <span class="badge badge-primary">{{ ucwords($task->status) }}</span>
                                        @elseif($task->status === 'on progress')
                                        <span class="badge badge-warning">{{ ucwords($task->status) }}</span>
                                        @elseif($task->status === 'done')
                                        <span class="badge badge-success">{{ ucwords($task->status) }}</span>
                                        @elseif($task->status === 'cancel')
                                        <span class="badge badge-danger">{{ ucwords($task->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>
                                            Deadline
                                        </b>
                                    </td>
                                    <td>{{ $task->deadline }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>
                                            Solved
                                        </b>
                                    </td>
                                    <td>{{ $task->completed_time == null ? '-' : $task->completed_time }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>
                                            Percentage
                                        </b>
                                    </td>
                                    <td>@if(($task->completed_time != null) &&
                                       ( date('Y-m-d H:i:s',strtotime($task->completed_time)) < 
                                       date('Y-m-d H:i:s',strtotime($task->deadline))))
                                            <div class="badge badge-success">
                                                110%
                                            </div>
                                            @elseif(($task->completed_time != null) &&
                                            (date('Y-m-d H:i:s',strtotime($task->completed_time)) ==
                                            date('Y-m-d H:i:s', strtotime($task->deadline))))
                                            <div class="badge badge-primary">
                                                100%
                                            </div>
                                            @elseif(($task->completed_time != null) &&
                                            (date('Y-m-d H:i:s',strtotime($task->completed_time) )>
                                            date('Y-m-d H:i:s',strtotime($task->deadline)) ))
                                            <div class="badge badge-warning">
                                                80%
                                            </div>
                                            @else
                                            <div class="badge badge-danger">
                                                0%
                                            </div>
                                            @endif</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>
                                            Note
                                        </b>
                                    </td>
                                    <td>{{ $task->note }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>
                                            Attach Done
                                        </b>
                                        
                                    </td>
                                    <td><a {{ $task->attach_done == null ? 'hidden' : '' }} href="{{
                                            asset($task->attach_done) }}">Click Here</a></td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>
                                            Report Done
                                        </b>
                                        
                                    </td>
                                    <td>{{ $task->report_done == null ? '-': $task->report_done }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @if($task->status != 'done')                            
                        <button class="btn btn-success float-right" data-toggle="modal" data-target="#updatetask{{ $task->id }}"> <i class="fas fa-edit"></i> Update</button>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <h4 class="mt-3">Subtask</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th>#</th>
                                    <th>To</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Example</th>
                                    <th>Attach Done</th>
                                    <th>Report Done</th>
                                </thead>
                                <tbody>
                                    @if($task->subtasks->count() > 0)
                                    @foreach ($task->subtasks as $subtask)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subtask->employee->first_name }}</td>
                                        <td>{{ $subtask->description }}</td>
                                        <td>
                                            <div class="row">
                                                Deadline :
                                                {{ $subtask->deadline }}
                                            </div>
                                            <div class="row">
                                                Solved :
                                                {{ $subtask->completed_time }}
                                            </div>
                                            <div class="row">
                                                Status :
                                                @if(($subtask->completed_time != null) &&
                                                date('Y-m-d H:i:s',strtotime($subtask->completed_time))
                                                < date('Y-m-d H:i:s',strtotime($subtask->deadline)))
                                                    <div class="badge badge-success">
                                                        110%
                                                    </div>
                                                    @elseif(($subtask->completed_time != null) &&
                                                    date('Y-m-d H:i:s',strtotime($subtask->completed_time) ==
                                                    date('Y-m-d H:i:s',strtotime($subtask->deadline)) ))
                                                    <div class="badge badge-primary">
                                                        100%
                                                    </div>
                                                    @elseif(($subtask->completed_time != null) &&
                                                    date('Y-m-d H:i:s',strtotime($subtask->completed_time) >
                                                    date('Y-m-d H:i:s',strtotime($subtask->deadline)) ))
                                                    <div class="badge badge-warning">
                                                        90%
                                                    </div>
                                                    @else
                                                    <div class="badge badge-danger">
                                                        0%
                                                    </div>
                                                    @endif
                                            </div>
                                        <td>@if($subtask->status === 'open')
                                            <span class="badge badge-primary">{{ ucwords($subtask->status) }}</span>
                                            @elseif($subtask->status === 'on progress')
                                            <span class="badge badge-warning">{{ ucwords($subtask->status) }}</span>
                                            @elseif($subtask->status === 'done')
                                            <span class="badge badge-success">{{ ucwords($subtask->status) }}</span>
                                            @elseif($subtask->status === 'cancel')
                                            <span class="badge badge-danger">{{ ucwords($subtask->status) }}</span>
                                            @endif
                                        </td>
                                        <td><a target="_blank" href="{{ asset($subtask->file) }}" {{ $subtask->file ==
                                                null ? 'hidden' : '' }}>Click Here</a></td>
                                        <td><a {{ $subtask->attach_done == null ? 'hidden' : '' }} target="_blank" href="{{ asset($subtask->attach_done) }}">Click Here</a></td>
                                        <td>{{ $subtask->report_done }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                        <td colspan="8">Not Yet Subtask</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('extra-js')
<script>
    function toggleReportTask(selectElement) {
        var task = selectElement.closest('.modal').id.replace('updatetask', '');

        // Dapatkan elemen select
        var statusSelect = selectElement;
        // Dapatkan elemen report-subtask
        var reportTask = document.getElementById('report-' + task);

        // Periksa nilai yang dipilih pada elemen select
        if (statusSelect.value === 'done') {
            // Jika nilai 'done', tampilkan report-subtask
            reportTask.style.display = 'block';
        } else {
            // Jika nilai 'on progress' atau nilai lainnya, sembunyikan report-subtask
            reportTask.style.display = 'none';
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true
            , autoWidth: false
        , });
    });
    $('#date_range').daterangepicker({
        "timePicker": true
        , "singleDatePicker": true
        , "showDropdowns": true
        , "locale": {
            "format": "DD-MM-YYYY HH:mm"
        }
    });

</script>
@endsection
