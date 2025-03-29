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
    <title>Unggul Kencana | @yield('title')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets') }}/img/favicon.png">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/animate.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/feather.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        @include('Components.header')

        @include('Components.sidebar')

        @yield('content')
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast colored-toast bg-secondary-transparent" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="toast-header bg-secondary text-fixed-white">
                <strong class="me-auto">Peringatan !</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            </div>
        </div>
        <div id="dangerToast" class="toast colored-toast bg-danger-transparent" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="toast-header bg-danger text-fixed-white">
                <strong class="me-auto">Peringatan !</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/feather.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/jquery.slimscroll.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/summernote/summernote-bs4.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/select2/js/select2.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/moment.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/sweetalert/sweetalert2.all.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/plugins/sweetalert/sweetalerts.min.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/theme-script.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/script.js" type="text/javascript"></script>
</body>

</html>
