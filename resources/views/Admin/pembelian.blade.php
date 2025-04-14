@extends('Layouts.app')
@section('title', 'Pembelian')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>PEMBELIAN BARANG</h4>
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
                    <a class="btn btn-added" id="btnTambahPembelian"><i data-feather="plus-circle" class="me-2"></i>TAMBAH
                        PEMBELIAN</a>
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
                        <table id="pembelianTable" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>KODE PEMBELIAN</th>
                                    <th>TANGGAL</th>
                                    <th>PENJUAL</th>
                                    <th>TOTAL</th>
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
    <div class="modal fade" id="mdFormPembelianDariToko">
        <div class="modal-dialog modal-xl modal-dialog-centered">
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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card" id="formContainer">
                                <div class="card-header">
                                    <h5 class="card-title">KODE TRANSAKSI #<b id="titlekodetransaksi"
                                            class="text-primary"></b></h5>
                                </div>
                                <div class="card table-list-card">
                                    <div class="table-responsive product-list">
                                        <table id="pembelianProdukTable" class="table table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>KODE PRODUK</th>
                                                    <th>NAMA</th>
                                                    <th>BERAT </th>
                                                    <th>HARGA</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="card" id="tabelProdukPembelian">
                                <div class="card-header">
                                    <h5 class="card-title">FORM PEMBELIAN </h5>
                                </div>
                                <div class="card table-list-card">
                                    <div class="table-responsive product-list">
                                        <table id="keranjangPembelianProduk" class="table table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>KODE PRODUK</th>
                                                    <th>NAMA</th>
                                                    <th>BERAT </th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <form method="POST" enctype="multipart/form-data" id="storePembelian">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">KODE PEMBELIAN PRODUK<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text" name="kodepembelianproduk" id="kodepembelianproduk"
                                                value="{{ session('kodepembelianproduk') }}" class="form-control"
                                                readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">PELANGGAN<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text" id="detailpelanggan" class="form-control" readonly>
                                            <input type="hidden" name="pelanggan" id="idpelanggan"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class=" mb-3">
                                        <label class="form-label">KONDISI</label>
                                        <select class="select" name="kondisi" id="kondisi">
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pembelian.js"></script>
@endsection
