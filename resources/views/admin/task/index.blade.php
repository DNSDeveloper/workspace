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

        {{-- note --}}
        {{-- cancel ada remarks --}}
        <!-- Modal -->
        <a href="{{ route('admin.task.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Task</a>
        <br>
        @foreach ($units as $unit)
        <div class="row">
            <div class="col-lg">
                <div class="card {{ $unit->name == 'Digital Nusantara Sinergi' ? 'card-primary' : '' }}">
                    <div class="card-header"
                        style="background-color: {{ $unit->name =='Ayo Bisa Indonesia' ? '#fd36d5': ($unit->name == 'STIQR' ? '#ff6701' : '') }}">
                        <div class="card-title text-white  text-center">
                            {{ $unit->name }}
                        </div>
                    </div>
                    {{-- <div class="float-right">
                        <a href="{{ route('admin.task.detail', str_replace(' ','-',$unit->name)) }}"
                            class="btn btn-success float-right mr-4 mt-2">Detail</a>
                    </div> --}}
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="">
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
                                @foreach ($unit->tasks->whereIn('status',['on progress','open','cancel']) as $task)
                                <tr style="background-color:  {{ ($task->status == 'open' || $task->status == 'on progress') 
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
                                        <div class="modal fade" id="canceltask{{ $task->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="canceltaskLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="canceltaskLabel">Cancel Task</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST"
                                                        action="{{ route('admin.task.cancel', $task->id) }}">
                                                        @csrf

                                                        <div class="modal-body">
                                                            <div class="form-group mb-3">
                                                                <label for="">Note</label>
                                                                <textarea name="note" id="" cols="30" rows="3"
                                                                    class="form-control">{{ $task->note }}</textarea>
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
                                        <button {{ $task->status == 'open' ? '' : 'hidden' }} class="btn btn-danger"
                                            title="Cancel Task" data-toggle="modal"
                                            data-target="#canceltask{{ $task->id }}"><svg
                                                xmlns="http://www.w3.org/2000/svg" height="16" width="12"
                                                viewBox="0 0 384 512">
                                                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                <path
                                                    d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                            </svg></button>
                                        <a href="{{ route('admin.task.detail', $task->id) }}" class="btn btn-warning">
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
        {{-- ABI --}}
        {{-- fd36d5 --}}
        {{-- stiqr --}}
        {{-- #ff6701 --}}
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
@endsection