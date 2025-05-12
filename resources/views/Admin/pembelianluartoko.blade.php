@extends('Layouts.app')
@section('title', 'Pembelian')
@section('content')
    <!-- /Main Wrapper -->
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header transfer">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>PEMBELIAN LUAR TOKO</h4>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a id="refreshButton" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i
                                data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card" id="formContainer">
                        <div class="card-header">
                            <h5 class="card-title">FORM PEMBELIAN PRODUK / BARANG</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" id="storePembelianProduk">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">NAMA<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="nama" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">JENIS<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="jenis" id="jenisproduk">
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">KONDISI<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="kondisi" id="kondisi">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">BERAT<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="berat" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">KARAT<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="karat" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">LINGKAR<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="lingkar" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">PANJANG<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="panjang" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">HARGA BELI<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="hargabeli" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">KETERANGAN<span class="text-danger ms-1">*</span></label>
                                    <textarea class="form-control" rows="4" name="keterangan"></textarea>
                                </div>
                                <!-- Form Tambahan -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card" id="tabelProdukPembelian">
                        <div class="card-header">
                            <h5 class="card-title">FORM PEMBELIAN </h5>
                        </div>
                        <div class="card-body">
                            <div class="card table-list-card">
                                <div class="table-responsive product-list">
                                    <table class="table table-hover" id="pembelianProdukTable" style="width: 100%">
                                        <thead class="thead-secondary">
                                            <tr>
                                                <th>KODE PRODUK</th>
                                                <th>NAMA PRODUK</th>
                                                <th>BERAT </th>
                                                <th>KONDISI </th>
                                                <th>HARGA BELI </th>
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
                                <div class="input-blocks add-products">
                                    <label class="d-block">Pembelian Dari</label>
                                    <div class="single-pill-product mb-3">
                                        <ul class="nav nav-pills" id="pills-tab1" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <span class="custom_radio me-4 mb-0 active" id="pills-home-tab"
                                                    data-bs-toggle="pill" data-bs-target="#pills-home" role="tab"
                                                    aria-controls="pills-home" aria-selected="true">
                                                    <input type="radio" class="form-control" name="payment">
                                                    <span class="checkmark"></span> Suplier</span>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <span class="custom_radio me-2 mb-0" id="pills-profile-tab"
                                                    data-bs-toggle="pill" data-bs-target="#pills-profile" role="tab"
                                                    aria-controls="pills-profile" aria-selected="false">
                                                    <input type="radio" class="form-control" name="pelanggan">
                                                    <span class="checkmark"></span> Pelanggan</span>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <span class="custom_radio me-2 mb-0" id="pills-pembeli-tab"
                                                    data-bs-toggle="pill" data-bs-target="#pills-pembeli" role="tab"
                                                    aria-controls="pills-pembeli" aria-selected="false">
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
                                <!-- Form Tambahan -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- md Tambah Diskon -->
    <div class="modal fade" id="mdEditProduk">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>TAMBAH DISKON / PROMO</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTambahDiskon" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">NAMA DISKON / PROMO<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="diskon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NILAI<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="nilai" class="form-control">
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

    <!-- jQuery -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pembelianluartoko.js" type="text/javascript"></script>
@endsection
