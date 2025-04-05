@extends('Layouts.app')
@section('title', 'Scan Barcode')
@section('content')
    <style>
        .product-slide .slider-product {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .product-slide .slider-product img {
            max-width: 100%;
            /* Agar gambar responsif */
            height: auto;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>SCAN BARCODE PRODUK</h4>
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

            <div class="d-flex justify-content-center" id="scannerContainer">
                <div class="card col-lg-4">
                    <div class="card-body d-flex justify-content-center">
                        <div class="col-12 d-flex">
                            <div class="card flex-fill bg-white">
                                <div id="reader"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="bar-code-view">
                                <img src="" id="barcode" alt="barcode" width="100px">
                            </div>
                            <div class="productdetails">
                                <ul class="product-bar">
                                    <li>
                                        <h4>KODE PRODUK</h4>
                                        <h6 id="kodeproduk"></h6>
                                    </li>
                                    <li>
                                        <h4>JENIS PRODUK</h4>
                                        <h6 id="jenisproduk"></h6>
                                    </li>
                                    <li>
                                        <h4>BERAT</h4>
                                        <h6 id="berat"></h6>
                                    </li>
                                    <li>
                                        <h4>KARAT</h4>
                                        <h6 id="karat"></h6>
                                    </li>
                                    <li>
                                        <h4>LINGKAR</h4>
                                        <h6 id="lingkar"></h6>
                                    </li>
                                    <li>
                                        <h4>PANJANG</h4>
                                        <h6 id="panjang"></h6>
                                    </li>
                                    <li>
                                        <h4>HARGA / GRAM</h4>
                                        <h6 id="harga"></h6>
                                    </li>
                                    <li>
                                        <h4>STATUS</h4>
                                        <h6 id="status"></h6>
                                    </li>
                                    <li>
                                        <h4>KETERANGAN</h4>
                                        <h6 id="keterangan"></h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="slider-product-details">
                                <div class="owl-carousel owl-theme product-slide">
                                    <div class="slider-product">
                                        <img src="" id="imageProduk" alt="img">
                                        <h4 id="namaImage"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/scanbarcode.js"></script>
@endsection
