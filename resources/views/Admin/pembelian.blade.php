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
                        <div class="col-md-6">
                            <div class="card" id="formContainer">
                                <div class="card-header">
                                    <h5 class="card-title">DAFTAR PRODUK</h5>
                                </div>
                                <div class="card-body">
                                    <div class="card table-list-card">
                                        <div class="table-top">
                                            <div class="search-set">
                                                <div class="search-input">
                                                    <a href="" class="btn btn-searchset"><i data-feather="search"
                                                            class="feather-search"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive product-list">
                                            <table class="table pembelianProdukTable table-hover" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>NO.</th>
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
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card" id="tabelProdukPembelian">
                                <div class="card-header">
                                    <h5 class="card-title">FORM PEMBELIAN </h5>
                                </div>
                                <div class="card-body">
                                    <div class="card table-list-card">
                                        <div class="table-top">
                                            <div class="search-set">
                                                <div class="search-input">
                                                    <a href="" class="btn btn-searchset"><i data-feather="search"
                                                            class="feather-search"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive product-list">
                                            <table class="table pembelianProdukTable table-hover" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Produk</th>
                                                        <th>Berat </th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data" id="storePembelian">
                                        @csrf
                                        <div class="input-blocks add-products">
                                            <label class="d-block">Pembelian Dari</label>
                                            <div class="single-pill-product mb-3">
                                                <ul class="nav nav-pills" id="pills-tab1" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <span class="custom_radio me-4 mb-0 active" id="pills-home-tab"
                                                            data-bs-toggle="pill" data-bs-target="#pills-home"
                                                            role="tab" aria-controls="pills-home"
                                                            aria-selected="true">
                                                            <input type="radio" class="form-control" name="payment">
                                                            <span class="checkmark"></span> Suplier</span>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <span class="custom_radio me-2 mb-0" id="pills-profile-tab"
                                                            data-bs-toggle="pill" data-bs-target="#pills-profile"
                                                            role="tab" aria-controls="pills-profile"
                                                            aria-selected="false">
                                                            <input type="radio" class="form-control" name="pelanggan">
                                                            <span class="checkmark"></span> Pelanggan</span>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <span class="custom_radio me-2 mb-0" id="pills-pembeli-tab"
                                                            data-bs-toggle="pill" data-bs-target="#pills-pembeli"
                                                            role="tab" aria-controls="pills-pembeli"
                                                            aria-selected="false">
                                                            <input type="radio" class="form-control"
                                                                name="nonsuplierdanpembeli">
                                                            <span class="checkmark"></span> Non Suplier / Pelanggan</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                                    aria-labelledby="pills-home-tab">
                                                    <div class="mb-3">
                                                        <label class="form-label">Suplier</label>
                                                        <select class="select" name="suplier_id" id="suplier_id">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                                    aria-labelledby="pills-profile-tab">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pelanggan</label>
                                                        <select class="select" name="pelanggan_id" id="pelanggan_id">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-pembeli" role="tabpanel"
                                                    aria-labelledby="pills-pembeli-tab">
                                                    <div class="mb-3">
                                                        <label class="form-label">Non Suplier / Pembeli</label>
                                                        <input type="text" name="nonsuplierdanpembeli"
                                                            id="nonsuplierdanpembeli" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
