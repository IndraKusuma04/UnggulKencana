<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unggul Kencana </title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets') }}/img/logo.png">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/animate.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/feather.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/dataTables.bootstrap5.min.css">

    <!-- Datepicker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/owlcarousel/owl.theme.default.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
</head>

<body>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>

    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/feather.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/jquery.slimscroll.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/summernote/summernote-bs4.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/select2/js/select2.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/moment.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/owlcarousel/owl.carousel.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/sweetalert/sweetalert2.all.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/plugins/sweetalert/sweetalerts.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/theia-sticky-sidebar/ResizeSensor.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js" type="text/javascript">
    </script>

    <!-- Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets') }}/js/script.js" type="text/javascript"></script>
</body>

</html>
