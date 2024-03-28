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
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Bonus Kinerja</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Nama</th>
                                        <th colspan="2">
                                            <center>
                                                Based On
                                            </center>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Kehadiran</th>
                                        <th>Task</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->first_name . ' ' . $attendance->last_name }}</td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td>Jumlah Hari Kerja &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                                    <td> : &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <td>{{ $jumlahHariKerja }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Hadir</td>
                                                    <td>:</td>
                                                    <td>{{ $attendance->attendance_hadir }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Terlambat</td>
                                                    <td>:</td>
                                                    <td>{{ $attendance->attendance_terlambat }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Persentase</td>
                                                    <td>:</td>
                                                    <td>
                                                        @if ($attendance->persentase_hadir >= 90 )
                                                        <span class="badge badge-success">{{
                                                            $attendance->persentase_hadir }}%</span>
                                                        @endif
                                                        @if ($attendance->persentase_hadir < 90 && $attendance->
                                                            persentase_hadir >= 50 )
                                                            <span class="badge badge-warning">{{
                                                                $attendance->persentase_hadir }}%</span>
                                                            @endif
                                                            @if ($attendance->persentase_hadir < 50) <span
                                                                class="badge badge-danger">{{
                                                                $attendance->persentase_hadir }}%</span>
                                                                @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td>Jumlah Task &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                                    <td> : &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <td>{{ $attendance->tasks_count }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Subtask &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <td> : &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <td>{{ $attendance->subtasks }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tepat Waktu</td>
                                                    <td>:</td>
                                                    <td>{{ $attendance->task_tepat_waktu }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Terlambat</td>
                                                    <td>:</td>
                                                    <td>{{ $attendance->task_terlambat }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Persentase</td>
                                                    <td>:</td>
                                                    <td>
                                                        @if ($attendance->persentase_task >= 90 )
                                                        <span class="badge badge-success">{{
                                                            $attendance->persentase_task }}%</span>
                                                        @endif
                                                        @if ($attendance->persentase_task < 90 && $attendance->
                                                            persentase_task >= 50 )
                                                            <span class="badge badge-warning">{{
                                                                $attendance->persentase_task }}%</span>
                                                            @endif
                                                            @if ($attendance->persentase_task < 50) <span
                                                                class="badge badge-danger">{{
                                                                $attendance->persentase_task }}%</span>
                                                                @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.bkinerja.detail',$attendance->id) }}"
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
</section>
@endsection

@section('extra-js')

@endsection