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
                    <div class="modal fade" id="owntask" tabindex="-1" role="dialog" aria-labelledby="addtaskLabel"
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
                                                    <option value="periodik">Periodik</option>
                                                    <option value="reguler">Reguler</option>
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
                                                <input type="text" name="deadline" id="deadline" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="dob">Note</label>
                                                <textarea name="note" class="form-control" id="" cols="30" rows="5"></textarea>
                                            </div>
                                        </fieldset>
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
                                <form action="{{ route('employee.subtask.store' ) }}" method="post" enctype="multipart/form-data">
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

                    @if(auth()->user()->employee->position->name == 'Leader')
                    <div class="float-right m-3">
                        <div class="gap-3">
                            <button class="btn btn-primary float-right" {{ $tasks->count() > 0 ? '' : 'disabled' }} data-toggle="modal" data-target="#addtask">
                                Add Subtask <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-primary float-right mr-3" data-toggle="modal" data-target="#owntask">
                                Add Own Task <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    @endif
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
                                                                <select class="form-control" name="status" id="status-subtask" onchange="toggleReportSubtask(this)">
                                                                    <option value="" hidden disabled selected value>--
                                                                        Choose Status --</option>
                                                                    <option value="on progress" {{ $subtask->status == 'on progress' ? 'hidden' : '' }}>On Progress</option>
                                                                    <option value="done">Done</option>
                                                                </select>
                                                            </div>
                                                            <div id="report-subtask-{{ $subtask->id }}">
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
    function toggleReportSubtask(selectElement) {
        // Dapatkan ID unik subtask dari elemen select
        var subtaskId = selectElement.closest('.modal').id.replace('updatesubtask', '');

        // Dapatkan elemen select
        var statusSelect = selectElement;
        // Dapatkan elemen report-subtask
        var reportSubtask = document.getElementById('report-subtask-' + subtaskId);

        // Periksa nilai yang dipilih pada elemen select
        if (statusSelect.value === 'done') {
            // Jika nilai 'done', tampilkan report-subtask
            reportSubtask.style.display = 'block';
        } else {
            // Jika nilai 'on progress' atau nilai lainnya, sembunyikan report-subtask
            reportSubtask.style.display = 'none';
        }
    }
</script>
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
    $('#deadline').daterangepicker({
                "timePicker": true,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY HH:mm"
                }
            });
</script>
@endsection