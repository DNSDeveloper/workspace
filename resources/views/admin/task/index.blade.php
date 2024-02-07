@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">All Task</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        All Task
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @include('messages.alerts')

        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-tasks-tab" data-toggle="pill" href="#pills-tasks"
                            role="tab" aria-controls="pills-tasks" aria-selected="false">Task</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-subtask-tab" data-toggle="pill" href="#pills-subtask" role="tab"
                            aria-controls="pills-subtask" aria-selected="false">Subtask</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-confirmed-tab" data-toggle="pill" href="#pills-confirmed"
                            role="tab" aria-controls="pills-confirmed" aria-selected="true">Need Confirmed <span
                                class="badge badge-danger {{ $needConfirmed->count() > 0 ? 'blink' : '' }}">{{
                                $needConfirmed->count() }}</span></a>
                    </li>
                </ul>
                <hr>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade  show active" id="pills-tasks" role="tabpanel"
                        aria-labelledby="pills-tasks-tab">
                        <div class="form-group d-flex gap-3">
                            <div class="mr-3">
                                <a href="{{ route('admin.task.create') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Add
                                    Task</a>
                            </div>
                            {{-- <div class="">
                                <select name="bg bg-primary" class="form-control" id="employees">
                                    <option value="" hidden selected>-- Filter By Employee --</option>
                                    <option value="all">All Employee</option>
                                    @foreach ($employees as $employee)
                                    <option value="{{ $employee->first_name }}">{{ $employee->first_name . ' ' .
                                        $employee->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        @foreach ($units as $unit)
                        <div class="row">
                            <div class="col-lg">
                                <div
                                    class="card {{ $unit->name == 'Digital Nusantara Sinergi' ? 'card-primary' : '' }}">
                                    <div class="card-header"
                                        style="background-color: {{ $unit->name =='Ayo Bisa Indonesia' ? '#fd36d5': ($unit->name == 'STIQR' ? '#ff6701' : '') }}">
                                        <div class="card-title text-white  text-center">
                                            {{ $unit->name }}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table {{ $unit->name }} table-bordered table-hover" id="">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">From</th>
                                                    <th scope="col">To</th>
                                                    <th scope="col">Service</th>
                                                    <th scope="col">Task</th>
                                                    <th scope="col">Deadline</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($unit->tasks->whereIn('status',['on
                                                progress','open','confirmed'])->sortByDesc('created_at') as
                                                $task)
                                                <tr style="background-color:  {{ ($task->status == 'open' || $task->status == 'on progress' || $task->status == 'confirmed') 
                                                    && $task->deadline < today() ? 'antiquewhite' : 'white'}} ">
                                                    <th>{{ $loop->iteration }}</th>
                                                    <td>{{ $task->user->name}}</td>
                                                    <td>{{ $task->employee->first_name}}</td>
                                                    <td>{{ isset($task->service) ? $task->service->name : '-' }}</td>
                                                    <td>{{ $task->task }}</td>
                                                    <td>
                                                        {{ date('d-m-Y H:i',strtotime($task->deadline)) }}
                                                    </td>
                                                    <td>
                                                        @if($task->status === 'open')
                                                        <span class="badge badge-primary">{{ ucwords($task->status)
                                                            }}</span>
                                                        @elseif($task->status === 'on progress' || $task->status ==
                                                        'confirmed')
                                                        <span class="badge badge-warning">{{ ucwords($task->status)
                                                            }}</span>
                                                        @elseif($task->status === 'done')
                                                        <span class="badge badge-success">{{ ucwords($task->status)
                                                            }}</span>
                                                        @elseif($task->status === 'cancel')
                                                        <span class="badge badge-danger">{{ ucwords($task->status)
                                                            }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="modal fade" id="canceltask{{ $task->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="canceltaskLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="canceltaskLabel">
                                                                            Cancel Task
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form method="POST"
                                                                        action="{{ route('admin.task.cancel', $task->id) }}">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <div class="form-group mb-3">
                                                                                <label for="">Note</label>
                                                                                <textarea name="note" id="" cols="30"
                                                                                    rows="3"
                                                                                    class="form-control">{{ $task->note }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Save</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button {{ $task->status == 'open' ? '' : 'hidden' }} class="btn
                                                            btn-danger"
                                                            title="Cancel Task" data-toggle="modal"
                                                            data-target="#canceltask{{ $task->id }}"><svg
                                                                xmlns="http://www.w3.org/2000/svg" height="16"
                                                                width="12" viewBox="0 0 384 512">
                                                                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                <path
                                                                    d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                                            </svg></button>
                                                        <a href="{{ route('admin.task.detail', $task->id) }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="tab-pane fade" id="pills-subtask" role="tabpanel" aria-labelledby="pills-tasks-tab">
                        <div class="card card-primary">
                            <div class="card-header">
                                Subtask
                            </div>
                            <div class="card-body">
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
                                            @foreach ($subtasks as $subtask)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $subtask->employee->first_name }}</td>
                                                <td>{{ $subtask->description }}</td>
                                                <td>
                                                    <div class="row">
                                                        Deadline :
                                                        {{ date('d-m-Y H:i',strtotime($subtask->deadline)) }}
                                                    </div>
                                                    <div class="row">
                                                        Solved :
                                                        {{ $subtask->completed_time == null ? '-' : date('d-m-Y
                                                        H:i',strtotime($subtask->completed_time)) }}
                                                    </div>
                                                    <div class="row">
                                                        Status :
                                                        @if(($subtask->completed_time != null) &&
                                                        ((date('Y-m-d',strtotime($subtask->completed_time))
                                                        < date('Y-m-d',strtotime($subtask->deadline)))))
                                                            <div class="badge badge-success">
                                                                110%
                                                            </div>
                                                            @elseif(($subtask->completed_time != null) &&
                                                            ((date('Y-m-d',strtotime($subtask->completed_time))
                                                            == date('Y-m-d',strtotime($subtask->deadline))) &&
                                                            (date('H:i:s',strtotime($subtask->completed_time))
                                                            < date('H:i:s',strtotime($subtask->deadline))) ))
                                                                <div class="badge badge-primary">
                                                                    100%
                                                                </div>
                                                                @elseif(($subtask->completed_time != null) &&
                                                                ((date('Y-m-d
                                                                H:i:s',strtotime($subtask->completed_time))
                                                                > date('Y-m-d H:i:s',strtotime($subtask->deadline)))))
                                                                <div class="badge badge-warning">
                                                                    80%
                                                                </div>
                                                                @else
                                                                <div class="badge badge-danger">
                                                                    0%
                                                                </div>
                                                                @endif
                                                    </div>
                                                <td>@if($subtask->status === 'open')
                                                    <span class="badge badge-primary">{{ ucwords($subtask->status)
                                                        }}</span>
                                                    @elseif($subtask->status === 'on progress')
                                                    <span class="badge badge-warning">{{ ucwords($subtask->status)
                                                        }}</span>
                                                    @elseif($subtask->status === 'done')
                                                    <span class="badge badge-success">{{ ucwords($subtask->status)
                                                        }}</span>
                                                    @elseif($subtask->status === 'cancel')
                                                    <span class="badge badge-danger">{{ ucwords($subtask->status)
                                                        }}</span>
                                                    @endif
                                                </td>
                                                <td><a target="_blank"
                                                        href="{{ $subtask->file == null ? '#': url($subtask->file) }}"
                                                        {{ $subtask->file ==
                                                        null ? 'hidden' : '' }}>Click Here</a></td>
                                                <td><a target="_blank"
                                                        href="{{ $subtask->attach_done == null ? '#': url($subtask->attach_done) }}"
                                                        {{ $subtask->attach_done == null ? 'hidden' : '' }} >Click
                                                        Here</a></td>
                                                <td>{{ $subtask->report_done }}</td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-confirmed" role="tabpanel"
                        aria-labelledby="pills-confirmed-tab">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-title text-white  text-center">
                                    Need Confirmed
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>From</th>
                                                <th>Service</th>
                                                <th>Task</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($needConfirmed as $confirmed)
                                            <tr>
                                                <td>{{ $confirmed->employee->first_name }}</td>
                                                <td>{{ $confirmed->service->name }}</td>
                                                <td>{{ $confirmed->task }}</td>
                                                <td>
                                                    <a href="{{ route('admin.task.detail', $confirmed->id) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
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
            </div>
        </div>
    </div>
</section>

@endsection
@section('extra-js')

<script>
    $(document).ready(function() {
        var data = `{{ $units }}`;
        $('table').DataTable({
            responsive:true,
            autoWidth: false,
        });
    });
</script>
<script>
    // $(document).ready(function(){
    //     $("#employees").on("input", function () {
    //     var value = $(this).val().toLowerCase();
    //     if (value === "all") {
    //             $(`table tbody tr`).show();
    //         } else {
    //             var filteredRows = $("table tbody tr").filter(function () {
    //                 return $(this).text().toLowerCase().indexOf(value) > -1;
    //             });
    //             $("table tbody tr").hide(); // Sembunyikan semua baris terlebih dahulu
    //             filteredRows.toggle(); // Tampilkan baris yang sesuai dengan pencarian
    //         }
    //     });
    // });
</script>
@endsection