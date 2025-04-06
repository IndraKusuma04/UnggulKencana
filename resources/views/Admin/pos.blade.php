@extends('Layouts.app')
@section('title', 'POS')
@section('content')
    <div class="page-wrapper pos-pg-wrapper">
        <div class="content pos-design p-0">
            <div class="btn-row d-sm-flex align-items-center">
                <a href="javascript:void(0);" class="btn btn-secondary mb-xs-3" id="modalTransaksi"><span
                        class="me-1 d-flex align-items-center"><i data-feather="shopping-cart"
                            class="feather-16"></i></span>LIHAT ORDER</a>
                <a href="javascript:void(0);" class="btn btn-primary" id="refreshButton"><span
                        class="me-1 d-flex align-items-center"><i data-feather="refresh-ccw"
                            class="feather-16"></i></span>REFRESH</a>
            </div>
            <div class="row align-items-start pos-wrapper">
                <div class="col-md-12 col-lg-8">
                    <div class="pos-categories tabs_wrapper">
                        <h5>NAMPAN</h5>
                        <p>PILIH DARI NAMPAN DIBAWAH INI</p>
                        <ul class="tabs owl-carousel pos-category" id="daftarNampan">
                        </ul>
                        <div class="pos-products">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-3">PRODUK</h5>
                            </div>
                            <div class="tabs_container" id="daftarProduk">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 ps-0">
                    <aside class="product-order-list">
                        <div class="head d-flex align-items-center justify-content-between w-100">
                            <div class>
                                <h5>DAFTAR ORDER</h5>
                                <span>ID TRANSAKSI : <b id="kodetransaksi"></b></span>
                            </div>
                        </div>
                        <div class="customer-info block-section">
                            <h6>INFORMASI PELANGGAN</h6>
                            <div class="input-block d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <select class="select" id="pelanggan" name="pelanggan">
                                    </select>
                                </div>
                                <a href="#" class="btn btn-primary btn-icon" id="btnTambahPelanggan"><i
                                        data-feather="user-plus" class="feather-16"></i></a>
                            </div>
                        </div>
                        <div class="product-added block-section">
                            <div class="head-text d-flex align-items-center justify-content-between">
                                <h6 class="d-flex align-items-center mb-0">PRODUK DITAMBAHKAN<span class="count"
                                        id="keranjangCount"></span>
                                </h6>
                                <a href="javascript:void(0);" class="d-flex align-items-center text-danger"
                                    id="deleteSemua"><span class="me-1"><i data-feather="x"
                                            class="feather-16"></i></span>BATALKAN SEMUA</a>
                            </div>
                            <div class="product-wrap">
                                <div class="product-list d-flex align-items-center justify-content-between">

                                </div>
                            </div>
                        </div>
                        <div class="customer-info block-section">
                            <h6>DISKON / PROMO</h6>
                            <div class="input-block d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <select class="select" id="diskon">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="block-section">
                            <div class="order-total">
                                <table class="table table-responsive table-borderless">
                                    <tr>
                                        <td>Sub Total</td>
                                        <td class="text-end" id="subtotal"></td>
                                    </tr>
                                    <tr>
                                        <td>Diskon</td>
                                        <td class="text-end" id="diskonDipilih"></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-end" id="total"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="d-grid btn-block">
                            <a class="btn btn-secondary" href="javascript:void(0);" id="grandTotal">

                            </a>
                        </div>
                        <div class="btn-row d-sm-flex align-items-center justify-content-between">
                            <a href="javascript:void(0);" class="btn btn-success btn-icon flex-fill" id="payment"><span
                                    class="me-1 d-flex align-items-center"><i data-feather="credit-card"
                                        class="feather-16"></i></span>Payment</a>
                        </div>
                    </aside>
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

    <div class="modal fade pos-modal" id="mdTransaksi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="modal-title">Recent Transactions</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a class="btn btn-searchset d-flex align-items-center h-100"><img
                                        src="{{ asset('assets') }}/img/icons/search-white.svg" alt="img"></a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="transaksiTable" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>TANGGAL</th>
                                    <th>NO TRANSAKSI</th>
                                    <th>PELANGGAN</th>
                                    <th>TOTAL </th>
                                    <th>STATUS </th>
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


    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pos.js"></script>
@endsection
