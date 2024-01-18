@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid pt-5">
        <div class="card">
            <div class="card-body">
                <h4>
                    Absent Recap {{ date('F') }}
                </h4>
                <table class="table table-responsive">
                    <thead>
                        <th>Employee</th>
                        @for ($q = 1; $q <= $days; $q++) <th>{{ $q }}</th>
                            @endfor
                    </thead>
                    <tbody>
                        @if($attendances->count() >= 1)
                        @foreach ($attendances->groupBy('employee_id') as $tes)
                        <tr>
                            <td>{{ $tes[0]->employee->first_name }}</td>
                            @php
                            $attendanceByDate = [];
                            @endphp
                            @foreach ($tes as $attendance)
                            @php
                            $attendanceDate = \Carbon\Carbon::parse($attendance->created_at)->format('j');
                            $attendanceByDate[$attendanceDate] = $attendance;
                            @endphp
                            @endforeach

                            @for ($q = 1; $q <= $days; $q++) @php $attendanceOfDay=$attendanceByDate[$q] ?? null;
                                @endphp <td colspan="">
                                @if ($attendanceOfDay && $attendanceOfDay->status == 'terlambat')
                                    <span class="badge badge-warning">T</span>
                                @elseif($attendanceOfDay && $attendanceOfDay->status == 'hadir')
                                <span class="badge badge-warning">H</span>
                                @elseif($attendanceOfDay && ($attendanceOfDay->status == 'Cuti'|$attendanceOfDay->status == 'Sakit'))
                                <span class="badge badge-primary">{{ $attendanceOfDay->status == 'Cuti' ? 'C': 'S' }}</span>
                                @elseif($q < today()->format('d'))
                                <span class="badge badge-danger">A</span>
                                @endif
                                {{-- <span
                                    class="badge badge-{{ $attendanceOfDay && $attendanceOfDay->status == 'terlambat' ? 'warning' : ($attendanceOfDay && $attendanceOfDay->status == 'hadir' ? 'success' : ($q < today()->format('d') ? 'danger' : ' ')) }}">
                                    {{ $attendanceOfDay && $attendanceOfDay->status == 'terlambat' ? 'T' :
                                    ($attendanceOfDay && $attendanceOfDay->status == 'hadir' ? 'H' : ($q < today()->
                                        format('d') ? 'A' : ' ')) }}
                                </span> --}}
                                </td>
                                @endfor
                        </tr>
                        @endforeach
                        @else
                        <tr class="text-center">
                            <td class="text-center" colspan="{{ $days }}">Not Yet</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>


        <div class="card">
            <div class="card-body">
                <h4>Presensi Hari Ini</h4>
                <div class="row">
                    @foreach ($todayAttendances as $todayAttendance)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="row m-2">
                                <div class="col-5">
                                    <img style="cursor: pointer" class="img-thumbnail img-rounded"
                                        src="{{ asset($todayAttendance->img_present)  }}" alt="" data-toggle="modal"
                                        data-target="#exampleModal{{ $todayAttendance->id }}">
                                </div>
                                <div class="col-7">
                                    <div class="">
                                       Nama : {{ $todayAttendance->employee->first_name }}
                                    </div>
                                    <div class="">
                                       Jam Masuk :
                                        {{ $todayAttendance->jam_masuk == null ? '-' : $todayAttendance->jam_masuk }}
                                    </div>
                                    <div class="">
                                            Jam Pulang :
                                        {{ $todayAttendance->jam_pulang == null ? '-' : $todayAttendance->jam_pulang
                                        }}
                                    </div>
                                    <span class="badge badge-warning">{{ ucwords($todayAttendance->status) }}</span> <span
                                        class="badge badge-primary" title="Nomor Kursi">{{ $todayAttendance->no_kursi
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{ $todayAttendance->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            {{-- <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div> --}}
                            <img src="{{ asset($todayAttendance->img_present) }}" alt="">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Daily Report Tanggal</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Report</th>
                                    <th scope="col">Ask</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($reports->count() > 0)
                                @foreach ($reports as $report)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $report->employee->first_name . ' ' . $report->employee->last_name}}</td>
                                    <td>{!! $report->report !!}</td>
                                    <td>{{ $report->ask }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <td colspan="4">Not Yet Report</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- /.content-wrapper -->

@endsection