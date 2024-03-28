@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid pt-3">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-header bg-primary">
            Presensi Hari Ini
          </div>
          @if ($attendance != null)
          <img class="card-img-top" src="{{ asset($attendance->img_present) }}" alt="Card image cap">
          <div class="card-body">
            <table>
              <tr>
                <td>
                  <b>
                    Jam Masuk
                  </b>
                </td>
                <td>:</td>
                <td>
                  {{ $attendance->jam_masuk }}
                </td>
              </tr>
              <tr>
                <td>
                  <b>
                    Jam Pulang
                  </b>
                </td>
                <td>:</td>
                <td>{{ $attendance->jam_pulang == null ? '-' :$attendance->jam_pulang }}</td>
              </tr>
              <tr>
                <td>
                  <b>
                    Status
                  </b>
                </td>
                <td>:</td>
                <td>
                  <span
                    class="badge badge-{{ $attendance->status =='terlambat' ? 'warning' : ($attendance->status =='hadir' ? 'success' : 'danger')}}">
                    {{ ucwords($attendance->status) }}
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <b>No Kursi</b>
                </td>
                <td>:</td>
                <td><span class="badge badge-primary">{{ $attendance->no_kursi }}</span></td>
              </tr>
            </table>
          </div>
          @else
          <div class="card-body">
            <div class="alert alert-danger">
              <div class="text-center">
                Opps Kamu Belum Absen Hari Ini 😭
              </div>
              <div class="row mt-3">
                <a href="{{ route('employee.attendance.create') }}"
                  class="btn btn-primary btn-lg btn-block text-decoration-none "> Absen Dulu Yuk <i
                    class="fa fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header bg-primary">
            Task
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">From</th>
                  <th scope="col">Task</th>
                  <th scope="col">Deadline</th>
                  <th scope="col">Status</th>
                  <th scope="col">Detail</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($tasks as $task)
                <tr>
                  <td>{{ $task->user->name }}</td>
                  <td>{{ $task->task }}</td>
                  <td>{{ $task->deadline }}</td>
                  <td>
                    <span class="badge badge-{{ $task->status == 'open' ? 'primary' : 'warning' }}">{{
                      ucwords($task->status)
                      }}</span>
                  </td>
                  <td>
                    <a class="btn btn-warning" href="{{ route('employee.task.detail',$task->id) }}">
                      <i class="fas fa-eye">
                      </i>
                    </a>
                  </td>
                </tr>
                @endforeach
                @foreach ($subtask as $sub)
                <tr>
                  <td>{{ $sub->task->employee->first_name }}</td>
                  <td>{{ $sub->task->task }}</td>
                  <td>{{ $sub->deadline }}</td>
                  <td> <span class="badge badge-{{ $sub->status == 'open' ? 'primary' : 'warning' }}">{{
                      ucwords($sub->status)
                      }}</span></td>
                  <td><a class="btn btn-warning" href="{{ route('employee.task') }}">
                      <i class="fas fa-eye"></i>
                    </a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-primary">
            Uang Harian
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Nama Employee</th>
                  <th>Total Per Bulan</th>
                  <th colspan="5">Total Per Minggu</th> <!-- Menggunakan colspan untuk menggabungkan 5 kolom -->
                </tr>
              </thead>
              <tbody>
                @foreach($uangHarians as $employee)
                <tr>
                  <td>{{ $employee['nama_employee'] }}</td>
                  <td>@currency($employee['total_permonth'])</td>
                  <td colspan="5">
                    <!-- Menggunakan colspan untuk menggabungkan 5 kolom -->
                    <table class="table">
                      <thead>
                        <tr>
                          @foreach($employee['detail'] as $detail)
                          <td>{{ $detail['week'] }}</td>
                          @endforeach
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          @foreach($employee['detail'] as $detail)
                          <td>@currency($detail['total'])</td>
                          @endforeach
                        </tr>
                      </tbody>
                    </table>
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