$(document).ready(function () {

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
                    url: `/admin/pembelianluartoko/getPembelianProduk`,
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
                                <a class="me-2 p-2 btn-edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="EDIT PRODUK YANG DIBELI">
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

    //ketika submit form tambah kondisi
    $("#storePembelianProduk").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        // Buat objek FormData
        const formData = new FormData(this);
        $.ajax({
            url: "/admin/pembelianluartoko/storePembelianProduk", // Endpoint Laravel untuk menyimpan pegawai
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message
                    });

                    $('#kodepembelianproduk').val(response.kode); // <-- tambahkan ini

                    // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                    if ($.fn.DataTable.isDataTable('#pembelianProdukTable')) {
                        $('#pembelianProdukTable').DataTable().ajax.reload();
                    }
                } else {
                    Swal.fire({
                        icon: "info",
                        title: "Wait!",
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan!",
                    text: "Tidak dapat menambahkan produk."
                });
            }
        });
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit", function () {
        const idPembelian = $(this).data("id");

        $.ajax({
            url: `/admin/pembelianluartoko/getPembelianByID/${idPembelian}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data;

                $("#editkodeproduk").val(data.kodeproduk);
                $("#editnama").val(data.nama);
                $("#editberat").val(data.berat);
                $("#editkarat").val(data.karat);
                $("#editlingkar").val(data.lingkar);
                $("#editpanjang").val(data.panjang);
                $("#edithargabeli").val(data.harga_beli);
                $("#editketerangan").val(data.keterangan);

                // Muat opsi jenis produk
                $.ajax({
                    url: "/admin/jenisproduk/getJenisProduk",
                    type: "GET",
                    success: function (jenisProdukResponse) {
                        let options
                        jenisProdukResponse.Data.forEach((item) => {
                            const selected =
                                item.id === data.jenisproduk_id
                                    ? "selected"
                                    : "";
                            options += `<option value="${item.id}" ${selected}>${item.jenis_produk}</option>`;
                        });
                        $("#editjenis").html(options);
                    },
                });

                // Muat opsi kondisi
                $.ajax({
                    url: "/admin/kondisi/getKondisi",
                    type: "GET",
                    success: function (kondisiResponse) {
                        let options
                        kondisiResponse.Data.forEach((item) => {
                            const selected =
                                item.id === data.kondisi_id
                                    ? "selected"
                                    : "";
                            options += `<option value="${item.id}" ${selected}>${item.kondisi}</option>`;
                        });
                        $("#editkondisi").html(options);
                    },
                });

                // Tampilkan modal edit
                $("#mdEditProduk").modal("show");
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Tidak dapat mengambil data kondisi.",
                    "error"
                );
            },
        });
    });

})