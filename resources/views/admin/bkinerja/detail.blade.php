@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Bonus Kinerja</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Bonus Kinerja
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

                <div class="row">
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header bg-success">
                                Kehadiran
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam Hadir</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($attendances->count() > 0)
                                        @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>{{ date('d M Y',strtotime($attendance->created_at)) }}</td>
                                            <td>{{ $attendance->jam_masuk }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $attendance->status =='terlambat' ? 'warning' : ($attendance->status =='hadir' ? 'success' : 'danger')}}">
                                                    {{ ucwords($attendance->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada presensi</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header bg-success">
                                Task
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($tasks->count() > 0)
                                        @foreach ($tasks as $task)
                                        <tr>
                                            <td>{{ $task->task }}</td>
                                            <td>
                                                @if(($task->completed_time != null) &&
                                                ((date('Y-m-d',strtotime($task->completed_time))
                                                < date('Y-m-d',strtotime($task->deadline)))))
                                                    <div class="badge badge-success">
                                                        110%
                                                    </div>
                                                    @elseif(($task->completed_time != null) &&
                                                    ((date('Y-m-d',strtotime($task->completed_time))
                                                    == date('Y-m-d',strtotime($task->deadline))) &&
                                                    (date('H:i:s',strtotime($task->completed_time))
                                                    < date('H:i:s',strtotime($task->deadline))) ))
                                                        <div class="badge badge-primary">
                                                            100%
                                                        </div>
                                                        @elseif(($task->completed_time != null) &&
                                                        ((date('Y-m-d H:i:s',strtotime($task->completed_time))
                                                        > date('Y-m-d H:i:s',strtotime($task->deadline)))))
                                                        <div class="badge badge-warning">
                                                            80%
                                                        </div>
                                                        @else
                                                        <div class="badge badge-danger">
                                                            0%
                                                        </div>
                                                        @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="2">Belum ada task</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header bg-success">
                                Subtask
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Dari</th>
                                            <th>Subtask</th>
                                            <th>Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($subtasks->count() > 0)
                                        @foreach ($subtasks as $subtask)
                                        <tr>
                                            <td>{{ $subtask->task->employee->first_name . '
                                                '.$subtask->task->employee->last_name }}
                                            </td>
                                            <td>{{ $subtask->description }}</td>
                                            <td>
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
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="2">Belum ada subtask</td>
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
    </div>
</section>
@endsection

@section('extra-js')

@endsection