$(document).ready(function(){

    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    // Muat opsi kondisi
    $.ajax({
        url: "/admin/kondisi/getKondisi", // Endpoint untuk mendapatkan data jabatan
        type: "GET",
        success: function (response) {
            let options
            response.Data.forEach((item) => {
                options += `<option value="${item.id}">${item.kondisi}</option>`;
            });
            $("#kondisi").html(options); // Masukkan data ke select
        },
        error: function () {
            Swal.fire(
                "Gagal!",
                "Tidak dapat mengambil data kondisi.",
                "error"
            );
        },
    });

    $.ajax({
        url: "/admin/jenisproduk/getJenisProduk", // Endpoint untuk mendapatkan data jabatan
        type: "GET",
        success: function (response) {
            let options
            response.Data.forEach((item) => {
                options += `<option value="${item.id}">${item.jenis_produk}</option>`;
            });
            $("#jenisproduk").html(options); // Masukkan data ke select
        },
        error: function () {
            Swal.fire(
                "Gagal!",
                "Tidak dapat mengambil data jenis produk.",
                "error"
            );
        },
    });

    function getProdukPembelianTable() {
        if ($('#pembelianProdukTable').length > 0) {
            if ($.fn.DataTable.isDataTable('#pembelianProdukTable')) {
                $('#pembelianProdukTable').DataTable().destroy();
            }

            $('#pembelianProdukTable').DataTable({
                scrollX: false,
                bFilter: false,
                sDom: 'fBtlpi',
                ordering: true,
                language: {
                    search: ' ',
                    sLengthMenu: '_MENU_',
                    searchPlaceholder: "Search",
                    info: "_START_ - _END_ of _TOTAL_ items",
                    paginate: {
                        next: ' <i class="fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>'
                    },
                },
                ajax: {
                    url: `/admin/pembelian/pembeliantoko/getPembelianProduk`,
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataSrc: 'Data'
                },
                columns: [
                    { data: "kodeproduk" },
                    { data: "nama" },
                    {
                        data: "berat",
                        render: data => `${parseFloat(data).toFixed(1)} gram`
                    },
                    { data: "kondisi.kondisi" },
                    {
                        data: "harga_beli",
                        render: function (data) {
                            if (data != null) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(data);
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: null,
                        orderable: false,
                        className: "action-table-data justify-content-center",
                        render: (data, type, row) => `
                            <div class="edit-delete-action">
                                <a class="me-2 p-2 btn-edit-harga" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="EDIT PRODUK YANG DIBELI">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="p-2 btn-delete-produk" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="BATALKAN PRODUK">
                                    <i data-feather="trash-2"></i>
                                </a>
                            </div>
                        `
                    }
                ],
                drawCallback: function () {
                    feather.replace();
                    initializeTooltip();
                }
            });
        }
    }

    getProdukPembelianTable();

})