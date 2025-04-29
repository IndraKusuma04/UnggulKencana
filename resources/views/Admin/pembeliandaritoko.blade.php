@extends('Layouts.app')
@section('title', 'Pembelian')
@section('content')
    <div class="page-wrapper notes-page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>PEMBELIAN DARI TOKO</h4>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <ul class="table-top-head">
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
                <div class="page-btn">
                    <a class="btn btn-added" id="btnTambahPembelian"><i data-feather="plus-circle" class="me-2"></i>TAMBAH
                        PEMBELIAN</a>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-header">
                    <h4 class="card-title">PRODUK TRANSAKSI PELANGGAN</h4>
                </div>
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
                        <table id="produkTransaksiTable" class="table table-hover" style="width: 100%">
                            <thead class="thead-secondary">
                                <tr>
                                    <th>KODE PRODUK</th>
                                    <th>NAMA</th>
                                    <th>BERAT</th>
                                    <th>HARGA JUAL</th>
                                    <th class="text-center no-sort">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <div class="card table-list-card">
                            <div class="card-header">
                                <h4 class="card-title text-secondary">PRODUK DIBELI / DIPILIH</h4>
                            </div>
                            <div class="table-responsive product-list">
                                <table id="produkPembelianTable" class="table table-hover" style="width: 100%">
                                    <thead class="thead-secondary">
                                        <tr>
                                            <th>KODE PRODUK</th>
                                            <th>NAMA</th>
                                            <th>BERAT </th>
                                            <th>HARGA BELI</th>
                                            <th class="text-center no-sort">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <form method="POST" enctype="multipart/form-data" id="storePembelianPelanggan">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">KODE PEMBELIAN PRODUK<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="kodepembelianproduk" id="kodepembelianproduk"
                                            value="{{ session('kodepembelianproduk') }}" class="form-control" readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">PELANGGAN<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text" id="detailpelanggan" class="form-control" readonly>
                                            <input type="hidden" name="pelanggan" id="idpelanggan" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">KONDISI PRODUK<span
                                                    class="text-danger ms-1">*</span></label>
                                            <select class="select" name="kondisi" id="kondisi"></select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CATATAN<span class="text-danger ms-1">*</span></label>
                                        <textarea name="catatan" class="form-control" cols="5" rows="4"></textarea>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- md Pembelian Dari Toko -->
    <div class="modal fade" id="mdPembelianDariToko">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>PEMBELIAN DARI TOKO</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCariByKodeTransaksi" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">CARI DENGAN KODE TRANSAKSI<span
                                    class="text-danger ms-1">*</span></label>
                            <input type="text" name="kodetransaksi" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">CARI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- md Tambah Pembelian -->
    <div class="modal fade" id="mdEditHargaBeli">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>FORM EDIT HARGA BELI</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="formUpdateHargaBeli">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">ID<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="id" id="editid" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">HARGA BELI<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="hargabeli" id="editharga" class="form-control">
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

    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pembeliantoko.js"></script>
@endsection
