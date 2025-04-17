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
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                                    class="feather-rotate-ccw"></i></a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                    data-feather="chevron-up" class="feather-chevron-up"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="barcode-content-list">
                <form>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="row seacrh-barcode-item">
                                <div class="col-sm-6 mb-3 seacrh-barcode-item-one">
                                    <label class="form-label">Warehouse</label>
                                    <select class="select">
                                        <option>Choose</option>
                                        <option>Manufacture</option>
                                        <option>Transport</option>
                                        <option>Customs</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 mb-3 seacrh-barcode-item-one">
                                    <label class="form-label">Select Store</label>
                                    <select class="select">
                                        <option>Choose</option>
                                        <option>Online</option>
                                        <option>Offline</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-blocks search-form seacrh-barcode-item">
                                <div class="searchInput">
                                    <label class="form-label">Product</label>
                                    <input type="text" class="form-control" placeholder="Search Product by Codename">
                                    <div class="resultBox">
                                    </div>
                                    <div class="icon"><i class="fas fa-search"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-lg-12">
                    <div class="modal-body-table search-modal-header">
                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Code</th>
                                        <th>Qty</th>
                                        <th class="text-center no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="productimgname">
                                                <a href="javascript:void(0);" class="product-img stock-img">
                                                    <img src="assets/img/products/stock-img-02.png" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">Nike Jordan</a>
                                            </div>
                                        </td>
                                        <td>PT002</td>
                                        <td>HG3FK</td>
                                        <td>
                                            <div class="product-quantity">
                                                <span class="quantity-btn"><i data-feather="minus-circle"
                                                        class="feather-search"></i></span>
                                                <input type="text" class="quntity-input" value="4">
                                                <span class="quantity-btn">+<i data-feather="plus-circle"
                                                        class="plus-circle"></i></span>
                                            </div>
                                        </td>
                                        <td class="action-table-data justify-content-center">
                                            <div class="edit-delete-action">
                                                <a class="confirm-text barcode-delete-icon" href="javascript:void(0);">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="productimgname">
                                                <a href="javascript:void(0);" class="product-img stock-img">
                                                    <img src="assets/img/products/stock-img-03.png" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">Apple Series 5 Watch</a>
                                            </div>
                                        </td>
                                        <td>PT003</td>
                                        <td>TEUIU7</td>
                                        <td>
                                            <div class="product-quantity">
                                                <span class="quantity-btn"><i data-feather="minus-circle"
                                                        class="feather-search"></i></span>
                                                <input type="text" class="quntity-input" value="4">
                                                <span class="quantity-btn">+<i data-feather="plus-circle"
                                                        class="plus-circle"></i></span>
                                            </div>
                                        </td>
                                        <td class="action-table-data justify-content-center">
                                            <div class="edit-delete-action">
                                                <a class="barcode-delete-icon confirm-text" href="javascript:void(0);">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="paper-search-size">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <form class="mb-0">
                                <label class="form-label">Paper Size</label>
                                <select class="select">
                                    <option>Choose</option>
                                    <option>Recent1</option>
                                    <option>Recent2</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-6 pt-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="search-toggle-list">
                                        <p>Show Store Name</p>
                                        <div class="input-blocks m-0">
                                            <div
                                                class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                <input type="checkbox" id="user7" class="check" checked>
                                                <label for="user7" class="checktoggle mb-0"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="search-toggle-list">
                                        <p>Show Product Name</p>
                                        <div class="input-blocks m-0">
                                            <div
                                                class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                <input type="checkbox" id="user8" class="check" checked>
                                                <label for="user8" class="checktoggle mb-0"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="search-toggle-list">
                                        <p>Show Price</p>
                                        <div class="input-blocks m-0">
                                            <div
                                                class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                <input type="checkbox" id="user9" class="check" checked>
                                                <label for="user9" class="checktoggle mb-0"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search-barcode-button">
                    <a href="javascript:void(0);" class="btn btn-submit me-2" data-bs-toggle="modal"
                        data-bs-target="#prints-barcode">
                        <span><i class="fas fa-eye me-2"></i></span>
                        Generate Barcode</a>
                    <a href="javascript:void(0);" class="btn btn-cancel me-2">
                        <span><i class="fas fa-power-off me-2"></i></span>
                        Reset Barcode</a>
                    <a href="javascript:void(0);" class="btn btn-cancel close-btn">
                        <span><i class="fas fa-print me-2"></i></span>
                        Print Barcode</a>
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
                                        <table id="keranjangPembelianProduk" class="table table-hover"
                                            style="width: 100%">
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
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" id="formUpdateHargaBeli">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">ID<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="id" id="editid" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">HARGA BELI<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="hargabeli" id="editharga" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pembelian.js"></script>
@endsection
