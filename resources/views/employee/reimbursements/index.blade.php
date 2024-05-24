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
@include('employee.reimbursements.forms')
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
                                        <option value="">Jumat Ke- </option>
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
                                    <th>Tujuan</th>
                                    <th>Jumat Ke-</th>
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
                                    <td>{{ ucfirst($reimbursement->tujuan) }}</td>
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
                                        @if($reimbursement->status !== 'paid')
                                        <button class="btn btn-success"
                                            data-target="#updateReimburse{{ $reimbursement->id }}" data-toggle="modal"
                                            {{ auth()->user()->employee->id == 3 && (auth()->user()->employee->id !=
                                            $reimbursement->employee->id) ? 'hidden' : '' }}>
                                            <i class="fas fa-edit">
                                            </i>
                                        </button>
                                        @endif
                                        <button data-id="{{ $reimbursement->id }}"
                                            class="btn btn-danger delete-reimbursement"><i
                                                class="fas fa-trash"></i></button>
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
    var table = $('#dataTable').DataTable({
        responsive:true,
        autoWidth: false,
    });

    $('body').on('click', '.delete-reimbursement', function () {
        var id = $(this).data("id");
            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                var urlDelete = "{{ route('employee.reimbursements.delete',':id') }}".replace(':id',id)
                    $.ajax({
                        type: "DELETE",
                        url: urlDelete,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: data.success,
                                icon: "success"
                            });
                            setTimeout(() => {
                                location.reload() 
                            }, 2000);
                        },
                    error: function (data) {
                        console.log('Error:', data);
                        }
                    });
                }
            });
        });
    });
    function setTransportDescription() {
        var jenis = $('#jenis').val();
        var tujuan = $('#tujuan').val();
        var deskripsi = "";
        var isShowTujuan = $("#tujuanSection")
        var isShowTipe = $("#tipeSection")
        if (jenis === "transportasi") {
            deskripsi = "Reimbursement Transportasi \n\nGojek dari ... ke ... + Admin\nRp. ... + Rp. ...";
            isShowTujuan.show()
            isShowTipe.show()
            tujuan === 'Lainnya' ? $("#lainnyaInput").show() : $("#lainnyaInput").hide()  
        } else {
            isShowTujuan.hide()
            isShowTipe.hide()
            isShowTujuan.val('')
            isShowTipe.val('');
        }

        $("#deskripsi").val(deskripsi);
    }

    // Event listener untuk perubahan pada jenis transportasi
    $('#jenis').on('change', function() {
        setTransportDescription();
    });

    // Event listener untuk perubahan pada opsi tujuan
    $('#tujuan').on('change', function() {
        setTransportDescription();
    });

    
</script>
<script>
    $(document).on('change', '.jenis-update', function() {
        var id = $(this).data('id');
        var jenisUpdate = $('#jenis-' + id).val();
        
        var isShowTujuan = $("#tujuanSection-"+id)
        var isShowTipe = $("#tipeSection-"+id)
        if (jenisUpdate === "transportasi") {
            isShowTujuan.show()
            isShowTipe.show()
        } else {
            isShowTujuan.hide()
            isShowTipe.hide()
            isShowTujuan.val('')
            isShowTipe.val('');
        }

    });
    $(document).on('change', '.tujuan-update', function() {
        var id = $(this).data('id');
        var jenisUpdate = $('#tujuan-' + id).val();
        jenisUpdate === 'Lainnya' ? $('#lainnyaInput-'+id).show() : $('#lainnyaInput-'+id).hide() 
    })


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