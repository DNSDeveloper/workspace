@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Reimbursement</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}">Dashboard Employee</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Reimbursement
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Reimbursement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('employee.reimbursements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">Tanggal Reimbursement</label>
                        <input type="date" name="tgl_reimbursement" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Minggu Ke-</label>
                        <input type="number" readonly name="minggu" value="{{ $week }}" placeholder="Minggu Ke-"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Jenis</label>
                        <select name="jenis" id="" class="form-control" required>
                            <option value="" hidden selected>-- Pilih Jenis --</option>
                            <option value="transportasi">Transportasi</option>
                            <option value="konsumsi">Konsumsi</option>
                            <option value="kebutuhan kantor">Kebutuhan Kantor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea required placeholder="Deskripsi" class="form-control" name="deskripsi" id="" cols="30"
                            rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">Nominal</label>
                        <input id="nominal" type="text" placeholder="Nominal" class="form-control" name="nominal"
                            required>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="">Tanggal Transfer</label>
                        <input type="date" class="form-control" name="tgl_transfer" required>
                    </div> --}}

                    <div class="mb-3">
                        <label for="">File</label>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
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
                        <h3 class="card-title">Data Reimbursement</h3>
                    </div>
                    <div class="card-body">
                        @if (auth()->user()->employee->id == 3)
                        <div class="mb-3">
                            <form class="d-flex mr-2" method="GET"
                                action="{{ route('employee.reimbursements.export')}}">
                                <div class="mr-2">
                                    <select name="minggu" id="" class="form-control">
                                        <option value="">Minggu Ke- </option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export
                                    Excel</button>
                            </form>
                        </div>
                        @endif
                        <div class="mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> <i
                                    class="fas fa-plus"></i> Add Reimbursement </button>
                        </div>
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    @if (auth()->user()->employee->id == 3)
                                    <th>Nama</th>
                                    @endif
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th>Minggu Ke-</th>
                                    <th class="text-center">Info</th>
                                    <th class="text-center">File</th>
                                    <th>Status</th>
                                    <th>Catetan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reimbursements as $reimbursement)
                                <tr>
                                    @if (auth()->user()->employee->id == 3)
                                    <td>{{ $reimbursement->employee->first_name . ' '.
                                        $reimbursement->employee->last_name }}</td>
                                    @endif
                                    <td>{{ ucfirst($reimbursement->jenis) }}</td>
                                    <td>{{ ucfirst($reimbursement->deskripsi) }}</td>
                                    <td>{{ $reimbursement->minggu }}</td>
                                    <td>
                                        <table class="d-flex justify-content-center">
                                            <tr>
                                                <td>Tgl Reimbursement</td>
                                                <td> {{ date('d M Y',strtotime($reimbursement->tanggal_reimbursement))
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> Tgl Transfer</td>
                                                <td> {{ $reimbursement->tanggal_transfer != null ? date('d M
                                                    Y',strtotime($reimbursement->tanggal_transfer) ) : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nominal</td>
                                                <td>@currency($reimbursement->nominal)</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>Employee</td>
                                                <td>
                                                    @if($reimbursement->file_employee != null)
                                                    <a href="{{ $reimbursement->file_employee }}">
                                                        Click Here
                                                    </a>
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Admin</td>
                                                <td>
                                                    @if($reimbursement->file_admin != null)
                                                    <a href="{{ $reimbursement->file_admin }}">
                                                        Click Here
                                                    </a>
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        @if($reimbursement->status =='requested')
                                        <div class="badge badge-danger">
                                            {{ ucfirst($reimbursement->status) }}
                                        </div>
                                        @elseif($reimbursement->status == 'pending')
                                        <div class="badge badge-warning">
                                            {{ ucfirst($reimbursement->status) }}
                                        </div>
                                        @elseif($reimbursement->status == 'paid')
                                        <div class="badge badge-success">
                                            {{ ucfirst($reimbursement->status) }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($reimbursement->catetan) }}</td>
                                    <td>
                                        <div class="modal fade" id="updateReimburse{{ $reimbursement->id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update
                                                            Reimbursement
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form
                                                        action="{{ route('employee.reimbursements.update',$reimbursement->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="">Tanggal Reimbursement</label>
                                                                <input type="date"
                                                                    value="{{ date('Y-m-d',strtotime($reimbursement->tanggal_reimbursement)) }}"
                                                                    name="tgl_reimbursement" class="form-control"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="">Minggu Ke-</label>
                                                                <input type="number" name="minggu"
                                                                    placeholder="Minggu Ke-"
                                                                    value="{{ $reimbursement->minggu }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="">Jenis</label>
                                                                <select name="jenis" id="" class="form-control"
                                                                    required>
                                                                    <option value="" hidden selected>-- Pilih Jenis --
                                                                    </option>
                                                                    <option value="transportasi" {{ $reimbursement->
                                                                        jenis == 'transportasi' ? 'selected' : ''
                                                                        }}>Transportasi</option>
                                                                    <option value="konsumsi" {{ $reimbursement->jenis ==
                                                                        'konsumsi' ? 'selected' : '' }}>Konsumsi
                                                                    </option>
                                                                    <option value="kebutuhan kantor" {{ $reimbursement->
                                                                        jenis == 'kebutuhan kantor' ? 'selected' : ''
                                                                        }}>Kebutuhan Kantor</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="">Deskripsi</label>
                                                                <textarea required placeholder="Deskripsi"
                                                                    class="form-control" name="deskripsi" id=""
                                                                    cols="30"
                                                                    rows="3">{{ $reimbursement->deskripsi }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="">Nominal</label>
                                                                <input id="nominal-{{ $reimbursement->id }}"
                                                                    onkeyup="tes({{ $reimbursement->id }})"
                                                                    value="@currency($reimbursement->nominal)"
                                                                    type="text" placeholder="Nominal"
                                                                    class="form-control" name="nominal" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @if($reimbursement->status !== 'paid')
                                        <button class="btn btn-success"
                                            data-target="#updateReimburse{{ $reimbursement->id }}" data-toggle="modal"
                                            {{ auth()->user()->employee->id == 3 && (auth()->user()->employee->id !=
                                            $reimbursement->employee->id) ? 'hidden' : '' }}>
                                            <i class="fas fa-edit">
                                            </i>
                                        </button>
                                        @endif
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
    $(document).ready(function(){
    $('#dataTable').DataTable({
        responsive:true,
        autoWidth: false,
    });
});
</script>
<script>
    let dengan_rupiah = document.getElementById('nominal');
    dengan_rupiah.addEventListener('keyup', function (e) {
    dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
});

/* Fungsi */
function formatRupiah(angka, prefix) {
    let number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}
</script>

<script>
    function tes(id) {
        let dengan_rupiah = document.getElementById('nominal-'+id);
    dengan_rupiah.addEventListener('keyup', function (e) {
    dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
});

/* Fungsi */
function formatRupiah(angka, prefix) {
    let number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}
    }
</script>
@endsection