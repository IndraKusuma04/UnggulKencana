@extends('Layouts.app')
@section('title', 'POS')
@section('content')
    <div class="page-wrapper pos-pg-wrapper">
        <div class="content pos-design p-0">
            <div class="btn-row d-sm-flex align-items-center">
                <a href="javascript:void(0);" class="btn btn-secondary mb-xs-3" data-bs-toggle="modal"
                    data-bs-target="#orders"><span class="me-1 d-flex align-items-center"><i data-feather="shopping-cart"
                            class="feather-16"></i></span>View Orders</a>
                <a href="javascript:void(0);" class="btn btn-info"><span class="me-1 d-flex align-items-center"><i
                            data-feather="rotate-cw" class="feather-16"></i></span>Reset</a>
                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recents"><span
                        class="me-1 d-flex align-items-center"><i data-feather="refresh-ccw"
                            class="feather-16"></i></span>Transaction</a>
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
                                <a href="#" class="btn btn-primary btn-icon" data-bs-toggle="modal"
                                    data-bs-target="#create"><i data-feather="user-plus" class="feather-16"></i></a>
                            </div>
                        </div>
                        <div class="product-added block-section">
                            <div class="head-text d-flex align-items-center justify-content-between">
                                <h6 class="d-flex align-items-center mb-0">PRODUK DITAMBAHKAN<span class="count"
                                        id="keranjangCount"></span>
                                </h6>
                                <a href="javascript:void(0);" class="d-flex align-items-center text-danger"><span
                                        class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear
                                    all</a>
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
                            <a href="javascript:void(0);" class="btn btn-info btn-icon flex-fill" data-bs-toggle="modal"
                                data-bs-target="#hold-order"><span class="me-1 d-flex align-items-center"><i
                                        data-feather="pause" class="feather-16"></i></span>Hold</a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-icon flex-fill"><span
                                    class="me-1 d-flex align-items-center"><i data-feather="trash-2"
                                        class="feather-16"></i></span>Void</a>
                            <a href="javascript:void(0);" class="btn btn-success btn-icon flex-fill" data-bs-toggle="modal"
                                data-bs-target="#payment-completed"><span class="me-1 d-flex align-items-center"><i
                                        data-feather="credit-card" class="feather-16"></i></span>Payment</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pos.js"></script>
@endsection
