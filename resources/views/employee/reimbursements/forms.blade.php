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
                        <label for="">Jumat Ke-</label>
                        <input type="number" readonly name="minggu" value="{{ $week }}" placeholder="Minggu Ke-"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Jenis</label>
                        <select name="jenis" id="jenis" class="form-control" required>
                            <option value="" hidden selected>-- Pilih Jenis --</option>
                            <option value="transportasi">Transportasi</option>
                            <option value="konsumsi">Konsumsi</option>
                            <option value="kebutuhan kantor">Kebutuhan Kantor</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tipeSection" style="display: none;">
                        <label for="">Tipe</label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="" hidden selected>-- Pilih Tipe --</option>
                            <option value="Ride">Ride</option>
                            <option value="Car">Car</option>
                        </select>
                    </div>

                    <div class="mb-3" id="tujuanSection" style="display: none;">
                        <label for="">Tujuan</label>
                        <select name="tujuan" id="tujuan" class="form-control">
                            <option value="" hidden selected>-- Pilih Tujuan --</option>
                            <option value="Pulang">Pulang</option>
                            <option value="Pergi">Pergi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>

                        <div class="mb-3 mt-1" id="lainnyaInput" style="display: none">
                            <input type="text" class="form-control" name="tujuan_lainnya" placeholder="Tujuan Lainnya">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea required placeholder="Deskripsi" class="form-control" name="deskripsi" id="deskripsi"
                            cols="30" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">Nominal</label>
                        <input id="nominal" type="text" placeholder="Nominal" class="form-control" name="nominal"
                            required>
                    </div>

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

@foreach ($reimbursements as $reimbursement)
<div class="modal fade" id="updateReimburse{{ $reimbursement->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update
                    Reimbursement
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('employee.reimbursements.update',$reimbursement->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">Tanggal Reimbursement</label>
                        <input type="date" value="{{ date('Y-m-d',strtotime($reimbursement->tanggal_reimbursement)) }}"
                            name="tgl_reimbursement" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Minggu Ke-</label>
                        <input type="number" name="minggu" placeholder="Minggu Ke-" value="{{ $reimbursement->minggu }}"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Jenis</label>
                        <select name="jenis" id="jenis-{{ $reimbursement->id }}" class="form-control jenis-update"
                            data-id="{{ $reimbursement->id }}" required>
                            <option value="" hidden selected>-- Pilih Jenis --</option>
                            <option value="transportasi" {{ $reimbursement->jenis == 'transportasi' ? 'selected' : ''
                                }}>Transportasi</option>
                            <option value="konsumsi" {{ $reimbursement->jenis == 'konsumsi' ? 'selected' : ''
                                }}>Konsumsi</option>
                            <option value="kebutuhan kantor" {{ $reimbursement->jenis == 'kebutuhan kantor' ? 'selected'
                                : '' }}>Kebutuhan Kantor</option>
                        </select>
                    </div>

                    <div class="mb-3" id="tipeSection-{{ $reimbursement->id }}"
                        style="display: {{ $reimbursement->jenis == 'transportasi' ? 'block' : 'none' }};">

                        <label for="">Tipe</label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="" hidden selected>-- Pilih Tipe --</option>
                            <option value="Ride" {{ $reimbursement->tipe == 'Ride' ? 'selected' : '' }}>Ride</option>
                            <option value="Car" {{ $reimbursement->tipe == 'Card' ? 'selected' : '' }}>Car</option>
                        </select>
                    </div>

                    <div class="mb-3" id="tujuanSection-{{ $reimbursement->id }}"
                        style="display: {{ $reimbursement->jenis == 'transportasi' ? 'block' : 'none' }};">
                        <label for="">Tujuan</label>
                        <select name="tujuan" id="tujuan-{{ $reimbursement->id }}" class="form-control tujuan-update"
                            data-id="{{ $reimbursement->id }}">
                            <option value="" hidden selected>-- Pilih Tujuan --</option>
                            <option value="Pulang" {{ $reimbursement->tujuan == 'Pulang' ? 'selected' : '' }}>Pulang
                            </option>
                            <option value="Pergi" {{ $reimbursement->tujuan == 'Pergi' ? 'selected' : '' }}>Pergi
                            </option>
                            <option value="Lainnya" {{ ($reimbursement->tujuan != 'Pulang' && $reimbursement->tujuan !=
                                'Pergi') ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <div class="mb-3 mt-1" id="lainnyaInput-{{ $reimbursement->id }}"
                            style="display: {{  ($reimbursement->tujuan != 'Pulang' && $reimbursement->tujuan != 'Pergi') ? 'block' : 'none'}}">
                            <input type="text" class="form-control"
                                value="{{ ($reimbursement->tujuan != 'Pulang' && $reimbursement->tujuan != 'Pergi') ? $reimbursement->tujuan : ''  }}"
                                name="tujuan_lainnya" placeholder="Tujuan Lainnya">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea required placeholder="Deskripsi" class="form-control" name="deskripsi"
                            id="deskripsi-{{ $reimbursement->id }}" cols="30"
                            rows="5">{{ $reimbursement->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="">Nominal</label>
                        <input id="nominal-{{ $reimbursement->id }}" onkeyup="tes({{ $reimbursement->id }})"
                            value="@currency($reimbursement->nominal)" type="text" placeholder="Nominal"
                            class="form-control" name="nominal" required>
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
@endforeach