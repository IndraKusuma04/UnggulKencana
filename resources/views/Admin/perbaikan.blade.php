@extends('Layouts.app')
@section('title', 'Perbaikan')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>PERBAIKAN BARANG</h4>
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
                        <table id="perbaikanTable" class="table table-hover" style="width: 100%">
                            <thead class="thead-secondary">
                                <tr>
                                    <th>NO.</th>
                                    <th>KODE PERBAIKAN</th>
                                    <th>KODE PRODUK</th>
                                    <th>KONDISI</th>
                                    <th>TANGGAL</th>
                                    <th>KETERANGAN</th>
                                    <th>STATUS</th>
                                    <th class="no-sort text-center">ACTION</th>
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

    <!-- md Tambah Jabatan -->
    <div class="modal fade" id="mdDetailPerbaikan">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>DETAIL PERBAIKAN</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">KODE PERBAIKAN<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="id" id="detailkodeperbaikan" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">KODE PRODUK<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="id" id="detailkodeproduk" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KONDISI<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="id" id="detailkondisi" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TANGGAL MASUK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="id" id="tanggalmasuk" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TANGGAL KELUAR<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="id" id="tanggalkeluar" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KETERANGAN<span class="text-danger ms-1">*</span></label>
                            <textarea class="form-control" rows="4" id="detailketerangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/perbaikan.js"></script>
@endsection
