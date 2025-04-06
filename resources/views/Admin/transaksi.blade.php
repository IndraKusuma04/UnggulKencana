@extends('Layouts.app')
@section('title', 'Transaksi')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>DAFTAR TRANSAKSI</h4>
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
                        <table id="transaksiTable" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>KODE TRANSAKSI</th>
                                    <th>TANGGAL</th>
                                    <th>PELANGGAN</th>
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

    <!-- details popup -->
    <div class="modal fade" id="detailTransaksi">
        <div class="modal-dialog sales-details-modal">
            <div class="modal-content">
                <div class="page-header p-4 border-bottom mb-0 d-print-none">
                    <div class="add-item d-flex align-items-center">
                        <div class="page-title modal-datail">
                            <h4 class="mb-0 me-2">DETAIL TRANSAKSI</h4>
                        </div>
                    </div>
                    <ul class="table-top-head">
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                                    src="{{ asset('assets') }}/img/icons/pdf.svg" alt="img"></a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" id="btnPrintModal" data-bs-placement="top" title="Pdf"><img
                                    src="{{ asset('assets') }}/img/icons/printer.svg" alt="img"></a>
                        </li>
                    </ul>
                </div>

                <div class="card border-0">
                    <div class="card-body pb-0">
                        <div class="invoice-box table-height"
                            style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                            <div class="row sales-details-items d-flex">
                                <div class="col-md-4 details-item">
                                    <h6>PELANGGAN</h6>
                                    <h4 class="mb-1"> <span id="namapelanggan"></span></h4>
                                    <p class="mb-0"> <span id="alamatpelanggan"></span></p>
                                    <p class="mb-0"> <span id="kontakpelanggan"></span></p>
                                </div>
                                <div class="col-md-4 details-item">
                                    <h6>TOKO</h6>
                                    <h4 class="mb-1">UNGGUL KENCANA</h4>
                                    <p class="mb-0">Ruko No. 8, Jl. Patimura, Karang Lewas, Purwokerto, Banyumas, Jawa
                                        Tengah</p>
                                    <p class="mb-0">Telp <span>0822 2537 7888</span></p>
                                </div>
                                <div class="col-md-4 details-item">
                                    <h6>FAKTUR</h6>
                                    <p class="mb-0">No. Transaksi: <span class="fs-16 text-primary ms-2">#<span
                                                id="kodetransaksi"></span></span>
                                    </p>
                                    <p class="mb-0">Tanggal: <span class="ms-2 text-gray-9" id="tanggaltransaksi"></span>
                                    </p>
                                    <p class="mb-0">Status: <span id="statustransaksi"></span>
                                    </p>
                                    <p class="mb-0">Sales: <span class="ms-2 text-gray-9" id="oleh">
                                            ></span></p>
                                </div>
                            </div>
                            <h5 class="order-text">DETAIL PESANAN</h5>
                            <div class="table-responsive no-pagination mb-3">
                                <table class="table" id="transaksiProduk">
                                    <thead>
                                        <tr>
                                            <th>KODE PRODUK</th>
                                            <th>NAMA PRODUK</th>
                                            <th>BERAT</th>
                                            <th>HARGA</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="row">
                                <div class="col-lg-6 ms-auto">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul class="border-1 rounded-1">
                                            <li class="border-bottom">
                                                <h4 class="border-end" id="subtotal">Sub Total</h4>
                                                <h5 class="text-danger"></h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end" id="diskon">Diskon</h4>
                                                <h5 class="text-secondary"></h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end" id="totalharga">Total</h4>
                                                <h5 class="text-success"></h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /details popup -->

    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/transaksi.js"></script>
@endsection
