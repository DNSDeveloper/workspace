@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid pt-5">
        <div class="card">
            <div class="card-header">
                <h4>Ranking Absensi</h4>
            </div>
            <div class="card-body">
                @foreach($ranksAttendances as $attend)
                <label for="">{{ $attend->employee->first_name }}</label>
                @php
                $totalPercentage = 100;
                $total = $attend->terlambat + $attend->hadir + $attend->cuti;
                $latePercentage = ($attend->terlambat / $total) * $totalPercentage;
                $presentPercentage = ($attend->hadir / $total) * $totalPercentage;
                $leavePercentage = ($attend->cuti / $total) * $totalPercentage;
                @endphp
                <div class="progress">
                    <div class="progress-bar bg-warning" title="Terlambat" role="progressbar"
                        style="width: {{ $latePercentage }}%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                        {{ $attend->terlambat }}</div>
                    <div class="progress-bar bg-success" title="Hadir" role="progressbar"
                        style="width: {{ $presentPercentage }}%" aria-valuenow="30" aria-valuemin="0"
                        aria-valuemax="100">{{ $attend->hadir }}</div>
                    <div class="progress-bar bg-info" title="Cuti" role="progressbar"
                        style="width: {{ $leavePercentage }}%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        {{ $attend->cuti }}</div>
                </div>
                @endforeach
            </div>
        </div>
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
                                @elseif($attendanceOfDay && ($attendanceOfDay->status == 'Cuti'|$attendanceOfDay->status
                                == 'Sakit'))
                                <span class="badge badge-primary">{{ $attendanceOfDay->status == 'Cuti' ? 'C': 'S'
                                    }}</span>
                                @elseif($q < today()->format('d'))
                                    <span class="badge badge-danger"></span>
                                    @endif
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
                    @if ($todayAttendances->count() > 0)
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
                                    <span class="badge badge-warning">{{ ucwords($todayAttendance->status) }}</span>
                                    <span class="badge badge-primary" title="Nomor Kursi">{{ $todayAttendance->no_kursi
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
                    @else
                </div>
                <div class="d-flex justify-content-center text-center">
                    <p class="text-center">
                        Not Yet Absensi
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Daily Report Tanggal</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Task</th>
                                        <th scope="col">Report</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($reports->count() > 0)
                                    @foreach ($reports as $report)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $report->employee->first_name . ' ' . $report->employee->last_name}}</td>
                                        {{-- <td>{{ $report->task->task }}</td> --}}
                                        <td>
                                            @foreach (json_decode($report->task) as $taskreport)
                                            <ul>
                                                <li>
                                                    {{ $taskreport }}
                                                </li>
                                            </ul>
                                            @endforeach
                                        </td>
                                        <td>{!! $report->report !!}</td>
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
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- /.content-wrapper -->

@endsection
{{-- @section('extra-js')
<script>
    const ctx = document.getElementById('myChart');
    var cData = JSON.parse(`<?php echo $chart_struktur; ?>`);
    var cLabels = <?php echo json_encode($result->pluck('employee_name')); ?>; // Ambil nama karyawan dari koleksi
    var cAttendances = <?php echo json_encode($result->pluck('attendances')); ?>;
console.log(cAttendances)
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: cLabels,
        datasets: cAttendances.map((attendances, index) => {
            return {
                label: cLabels[index],
                data: attendances.map(attendance => {
                }),
                borderWidth: 1
            };
        })
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Chart.js Bar Chart'
            }
        }
    }
});
</script>
@endsection --}}