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
                        <a href="{{ route('admin.index') }}">Dashboard Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Reimbursement
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
                        <h3 class="card-title">Data Reimbursement</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <form class="d-flex mr-2" method="GET" action="{{ route('admin.reimbursements.export')}}">
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
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th class="none">Minggu Ke-</th>
                                    <th class="text-center">Info</th>
                                    <th class="none text-center">File</th>
                                    <th>Status</th>
                                    <th class="none">Catetan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reimbursements as $reimbursement)
                                <tr>
                                    <td>{{ $reimbursement->employee->first_name . ' ' .
                                        $reimbursement->employee->last_name }}</td>
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
                                                    <a target="_blank"
                                                        href="{{ asset('/reimbursement/'. $reimbursement->file_employee) }}">
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
                                                    <a target="_blank"
                                                        href="{{ asset('/reimbursement/'. $reimbursement->file_admin) }}">
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
                                                        action="{{ route('admin.reimbursements.update',$reimbursement->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="">Status</label>
                                                                <select name="status" onchange="changeStatus(this)"
                                                                    class="form-control" id="" required>
                                                                    <option value="" hidden>-- Select Status --
                                                                    </option>
                                                                    <option value="pending">Pending</option>
                                                                    <option value="paid">Paid</option>
                                                                </select>
                                                            </div>
                                                            <div style="display: none"
                                                                id="catetan-{{ $reimbursement->id }}" class="mb-3">
                                                                <label for="">Catetan</label>
                                                                <textarea name="catetan" id="" cols="30" rows="3"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                            <div style="display: none"
                                                                id="file-{{ $reimbursement->id }}" class="mb-3">
                                                                <label for="">File</label>
                                                                <input type="file" class="form-control" name="file">
                                                            </div>
                                                            <div class="mb-3" style="display: none"
                                                                id="tgl-{{ $reimbursement->id }}">
                                                                <label for="">Tanggal Transfer</label>
                                                                <input type="date" class="form-control"
                                                                    name="tgl_transfer" required>
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
                                        @if($reimbursement->status != 'paid')
                                        <button class="btn btn-success"
                                            data-target="#updateReimburse{{ $reimbursement->id }}" data-toggle="modal">
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
function changeStatus(selectElement) {
        var reimburse = selectElement.closest('.modal').id.replace('updateReimburse', '');

        var statusSelect = selectElement;
        var catetan = document.getElementById('catetan-' + reimburse);
        var file = document.getElementById('file-' + reimburse);
        var tgl = document.getElementById('tgl-' + reimburse);
        catetan.style.display = 'none';
        file.style.display = 'none';
        if (statusSelect.value != 'pending') {
            catetan.style.display = 'none';
            file.style.display = 'block';
            tgl.style.display = 'block';
        } else {
            catetan.style.display = 'block';
        }
    }
</script>
@endsection