@extends('Layouts.app')
@section('title', 'Pelanggan')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>DAFTAR PELANGGAN</h4>
                    </div>
                </div>
                <ul class="table-top-head">
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" id="refreshButton" data-bs-placement="top" title="Refresh"><i
                                data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a class="btn btn-added" id="btnTambahPelanggan"><i data-feather="plus-circle" class="me-2"></i>TAMBAH
                        PELANGGAN</a>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive product-list">
                        <table id="pelangganTable" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>KODE PELANGGAN</th>
                                    <th>NAMA</th>
                                    <th>ALAMAT</th>
                                    <th>STATUS</th>
                                    <th class="no-sort">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- md Tambah pelanggan -->
    <div class="modal fade" id="mdTambahPelanggan">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>TAMBAH PELANGGAN</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTambahPelanggan" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">NIK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="nik" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NAMA<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="nama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KONTAK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="kontak" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TANGGAL LAHIR<span class="text-danger ms-1">*</span></label>
                            <input type="date" name="tanggal" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ALAMAT<span class="text-danger ms-1">*</span></label>
                            <textarea class="form-control" name="alamat" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- md Tambah pelangan -->
    <div class="modal fade" id="mdEditPelanggan">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>EDIT PELANGGAN</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditPelanggan" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="id" id="editid" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="nik" id="editnik" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NAMA<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="nama" id="editnama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KONTAK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="kontak" id="editkontak" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TANGGAL LAHIR<span class="text-danger ms-1">*</span></label>
                            <input type="date" name="tanggal" id="edittanggal" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ALAMAT<span class="text-danger ms-1">*</span></label>
                            <textarea class="form-control" id="editalamat" name="alamat" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pelanggan.js"></script>
@endsection
