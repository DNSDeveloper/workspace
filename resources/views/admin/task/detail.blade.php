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
        <div class="card">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs d-flex justify-content-around" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home"
                            role="tab" aria-controls="nav-home" aria-selected="true">Task</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                            role="tab" aria-controls="nav-profile" aria-selected="false">Subtask</a>
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
                                    <td>From</td>
                                    <td>{{ $task->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Task</td>
                                    <td>{{ $task->task }}</td>
                                </tr>
                                <tr>
                                    <td>Unit</td>
                                    <td> {{ $task->unit->name }}</td>
                                </tr>
                                <tr>
                                    <td>Service</td>
                                    <td>{{ $task->service->name }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
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
                                    <td>Deadline</td>
                                    <td>{{ $task->deadline }}</td>
                                </tr>
                                <tr>
                                    <td>Solved</td>
                                    <td>{{ $task->completed_time == null ? '-' : $task->completed_time }}</td>
                                </tr>
                                <tr>
                                    <td>Percentage</td>
                                    <td>@if(($task->completed_time != null) &&
                                        date('Y-m-d H:i:s',strtotime($task->completed_time)) < date('Y-m-d
                                            H:i:s',strtotime($task->deadline)))
                                            <div class="badge badge-success">
                                                110%
                                            </div>
                                            @elseif(($task->completed_time != null) &&
                                            date('Y-m-d H:i:s',strtotime($task->completed_time) ==
                                            date('Y-m-d H:i:s', strtotime($task->deadline))))
                                            <div class="badge badge-primary">
                                                100%
                                            </div>
                                            @elseif(($task->completed_time != null) &&
                                            date('Y-m-d H:i:s',strtotime($task->completed_time) >
                                            date('Y-m-d H:i:s',strtotime($task->deadline)) ))
                                            <div class="badge badge-warning">
                                                90%
                                            </div>
                                            @else
                                            <div class="badge badge-danger">
                                                0%
                                            </div>
                                            @endif</td>
                                </tr>
                                <tr>
                                    <td>Note</td>
                                    <td>{{ $task->note }}</td>
                                </tr>
                                <tr>
                                    <td>Attach Done</td>
                                    <td><a {{ $task->attach_done == null ? 'hidden' : '' }} href="{{
                                            asset($task->attach_done) }}">Click Here</a></td>
                                </tr>
                                <tr>
                                    <td>Report Done</td>
                                    <td>{{ $task->report_done == null ? '-': $task->report_done }}</td>
                                </tr>
                            </tbody>
                        </table>
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
                                        <td><a {{ $subtask->attach_done == null ? 'hidden' : '' }} target="_blank"
                                                href="{{ asset($subtask->attach_done) }}">Click Here</a></td>
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
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive:true,
            autoWidth: false,
        });
    });
    $('#date_range').daterangepicker({
                "timePicker": true,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY HH:mm"
                }
            });
</script>
@endsection