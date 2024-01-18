@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">List Task</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}">Dashboard Karyawan</a>
                    </li>
                    <li class="breadcrumb-item active">
                        List Task
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
                            List Task
                        </div>
                    </div>

                    @include('messages.alerts')

                    <!-- Modal -->
                    <div class="modal fade" id="addtask" tabindex="-1" role="dialog" aria-labelledby="addtaskLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addtaskLabel">Add Subtask</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('employee.task.store' ) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="">Task</label>
                                            <select class="form-control" name="task_id" id="">
                                                <option value="" hidden disabled selected value>-- Choose Task --
                                                </option>
                                                @foreach ($tasks as $task_modal)
                                                <option value="{{ $task_modal->id }}">{{ $task_modal->task}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Employee</label>
                                            <select class="form-control" name="employee_id" id="">
                                                <option value="" hidden disabled selected value>-- Choose Employee --
                                                </option>
                                                @foreach ( $employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->first_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Deadline</label>
                                            <input type="text" name="deadline" id="date_range" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="description" id="" cols="30" rows="3"
                                                class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">File</label>
                                            <input type="file" name="file" class="form-control" id="">
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

                    <div class="float-right m-3">
                        <button class="btn btn-primary float-right" {{ $tasks->count() > 0 ? '' : 'disabled' }} data-toggle="modal" data-target="#addtask">
                            Add Subtask <i class="fas fa-plus"></i>
                        </button>
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
                                        <!-- Modal -->
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
                                                                <select class="form-control" name="status" id="">
                                                                    <option value="" hidden disabled selected value>--
                                                                        Choose Status --</option>
                                                                    <option value="on progress" {{ $task->status == 'on progress' ? 'hidden' : '' }}>On Progress</option>
                                                                    <option value="done">Done</option>
                                                                </select>
                                                            </div>
                                                            
                                                            @if ($task->status == 'on progress')
                                                            <div class="form-group mb-3">
                                                                <label for="">Attachment</label>
                                                                <input type="file" name="file" class="form-control" id="">
                                                            </div>
                                                                
                                                            <div class="form-group mb-3">
                                                                <label for="">Report</label>
                                                                <textarea name="report" class="form-control" id="" cols="30" rows="3"></textarea>
                                                            </div>
                                                            @endif
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
                                        <button {{ $task->status == 'done' ? 'hidden' : '' }} data-toggle="modal" data-target="#updatetask{{ $task->id }}"
                                            class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </button>
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
                                        <br>
                                        <a href="{{ asset($subtask->file) }}" target="_blank" {{ $subtask->file == null ? 'hidden': '' }}>Click Here</a>
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
                                    <td>
                                        <div class="modal fade" id="updatesubtask{{ $subtask->id }}" tabindex="-1"
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
                                                    <form action="{{ route('employee.subtask.update',$subtask->id ) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group mb-3">
                                                                <label for="">Status</label>
                                                                <select class="form-control" name="status" id="">
                                                                    <option value="" hidden disabled selected value>--
                                                                        Choose Status --</option>
                                                                    <option value="on progress" {{ $subtask->status == 'on progress' ? 'hidden' : '' }}>On Progress</option>
                                                                    <option value="done">Done</option>
                                                                </select>
                                                            </div>
                                                            @if ($subtask->status == 'on progress')
                                                            <div class="form-group mb-3">
                                                                <label for="">Attachment</label>
                                                                <input type="file" name="file" class="form-control" id="">
                                                            </div>
                                                                
                                                            <div class="form-group mb-3">
                                                                <label for="">Report</label>
                                                                <textarea name="report" class="form-control" id="" cols="30" rows="3"></textarea>
                                                            </div>
                                                            @endif
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
                                        <button data-toggle="modal" data-target="#updatesubtask{{ $subtask->id }}"
                                            class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
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