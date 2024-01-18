@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">History Task</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}">Dashboard Karyawan</a>
                    </li>
                    <li class="breadcrumb-item active">
                        History Task
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title text-center">
                            History Task
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Unit</th>
                                    <th>Task</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->user->name }}</td>
                                    <td>{{ $task->unit->name }}</td>
                                    <td>{{ $task->task }}</td>
                                    <td>{{ $task->deadline }}</td>
                                    <td>
                                        @if($task->status === 'open')
                                        <span class="badge badge-primary">{{ ucwords($task->status) }}</span>
                                        @elseif($task->status === 'on progress')
                                        <span class="badge badge-warning">{{ ucwords($task->status) }}</span>
                                        @elseif($task->status === 'done')
                                        <span class="badge badge-success">{{ ucwords($task->status) }}</span>
                                        @elseif($task->status === 'cancel')
                                        <span class="badge badge-danger">{{ ucwords($task->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('employee.task.detail', $task->id) }}" class="btn btn-warning">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @foreach ($subtasks as $subtask)
                                <tr>
                                    <td>
                                        {{ $subtask->task->employee->first_name }}
                                    </td>
                                    <td>
                                        {{ $subtask->task->unit->name }}
                                    </td>
                                    <td>
                                        {{ $subtask->task->task }} : {{ $subtask->description }}
                                    </td>
                                    <td>
                                        {{ $subtask->deadline }}
                                    </td>
                                    <td>
                                        @if($subtask->status === 'open')
                                        <span class="badge badge-primary">{{ ucwords($subtask->status) }}</span>
                                        @elseif($subtask->status === 'on progress')
                                        <span class="badge badge-warning">{{ ucwords($subtask->status) }}</span>
                                        @elseif($subtask->status === 'done')
                                        <span class="badge badge-success">{{ ucwords($subtask->status) }}</span>
                                        @elseif($subtask->status === 'cancel')
                                        <span class="badge badge-danger">{{ ucwords($subtask->status) }}</span>
                                        @endif
                                    </td>
                                    <td></td>
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