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
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1000
                    });

                    $('#kodepembelianproduk').val(response.kode); // <-- tambahkan ini

                    // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                    if ($.fn.DataTable.isDataTable('#pembelianProdukTable')) {
                        $('#pembelianProdukTable').DataTable().ajax.reload();
                    }

                    $("#storePembelianProduk")[0].reset();
                } else {
                    Swal.fire({
                        icon: "info",
                        title: "Wait!",
                        text: response.message,
                        timer: 1000
                    });
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    let errorList = "<ul style='text-align: left; padding-left: 20px;'>";

                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorList += `<li><span class="text-danger ms-1">* ${errors[key][0]}</span></li>`;
                        }
                    }

                    errorList += "</ul>";

                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal",
                        html: errorList,
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Tidak dapat memproses permintaan. Silakan coba lagi.",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
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

                $("#editid").val(data.id);
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

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#formEditPembelianProduk", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const idProdukPembelian = formData.get("id"); // Mengambil nilai input dengan name="id"

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/pembelianluartoko/updatePembelianByID/${idProdukPembelian}`, // URL untuk mengupdate data pegawai
            type: "POST", // Gunakan metode POST (atau PATCH jika route mendukung)
            data: formData, // Gunakan FormData
            processData: false, // Jangan proses FormData sebagai query string
            contentType: false, // Jangan set Content-Type secara manual
            success: function (response) {
                // Tampilkan toast sukses
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1000
                });
                $("#mdEditProduk").modal("hide"); // Tutup modal
                // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                if ($.fn.DataTable.isDataTable('#pembelianProdukTable')) {
                    $('#pembelianProdukTable').DataTable().ajax.reload();
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    let errorList = "<ul style='text-align: left; padding-left: 20px;'>";

                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorList += `<li><span class="text-danger ms-1">* ${errors[key][0]}</span></li>`;
                        }
                    }

                    errorList += "</ul>";

                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal",
                        html: errorList,
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Tidak dapat memproses permintaan. Silakan coba lagi.",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
        });
    });

})