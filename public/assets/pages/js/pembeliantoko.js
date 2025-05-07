$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableProdukPembelianCustomer) {
            tableProdukPembelianCustomer.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Pembelian Berhasil Direfresh");
        toast.show();
    });

    let tableProdukPembelianCustomer;

    // Load data pembelian
    function getProdukPembelianTransaksiPelanggan() {
        if ($('#produkTransaksiTable').length > 0) {
            tableProdukPembelianCustomer = $('#produkTransaksiTable').DataTable({
                "scrollX": false,
                "bFilter": false,
                "sDom": 'fBtlpi',
                "ordering": true,
                "language": {
                    search: ' ',
                    sLengthMenu: '_MENU_',
                    searchPlaceholder: "Search",
                    info: "_START_ - _END_ of _TOTAL_ items",
                    paginate: {
                        next: ' <i class=" fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i> '
                    },
                },
                ajax: {
                    url: `/admin/pembelian/pembeliantoko/getTransaksiByKodeTransaksi`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.kodetransaksi = $('input[name="kodetransaksi"]').val();
                    },
                    dataSrc: function (json) {
                        if (json.success && json.Data.length > 0) {
                            const transaksi = json.Data[0];

                            // Tampilkan toast sukses
                            const successtoastExample = document.getElementById("successToast");
                            const toast = new bootstrap.Toast(successtoastExample);
                            $(".toast-body").text(json.message);
                            toast.show();

                            // Set nilai input pelanggan
                            $("#detailpelanggan").val(transaksi.pelanggan.nama);
                            $("#idpelanggan").val(transaksi.pelanggan.id);

                            // Load kondisi
                            $.ajax({
                                url: "/admin/kondisi/getKondisi",
                                type: "GET",
                                success: function (response) {
                                    let options = "";
                                    response.Data.forEach((item) => {
                                        options += `<option value="${item.id}">${item.kondisi}</option>`;
                                    });
                                    $("#kondisi").html(options);
                                },
                                error: function () {
                                    Swal.fire("Gagal!", "Tidak dapat mengambil data kondisi.", "error");
                                }
                            });

                            // Kembalikan data keranjang
                            return transaksi.keranjang;
                        } else {
                            // Tampilkan toast gagal
                            const dangertoastExample = document.getElementById("dangerToast");
                            const toast = new bootstrap.Toast(dangertoastExample);
                            $(".toast-body").text(json.message);
                            toast.show();

                            return [];
                        }
                    }
                },
                columns: [
                    { data: "produk.kodeproduk" },
                    { data: "produk.nama" },
                    {
                        data: "produk.berat",
                        render: function (data) {
                            return data ? parseFloat(data).toFixed(1) + " gram" : "-";
                        }
                    },
                    { data: "produk.kondisi.kondisi" },
                    {
                        data: "produk.harga_jual",
                        render: function (data) {
                            return data != null
                                ? new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(data)
                                : "-";
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: "action-table-data justify-content-center",
                        render: function (data, type, row) {
                            return `
                            <div class="edit-delete-action">
                                <a class="me-2 p-2 btn-pilihproduk" data-id="${row.produk_id}" data-bs-toggle="tooltip" data-bs-placement="top" title="PILIH PRODUK">
                                    <i data-feather="plus-circle" class="feather-edit"></i>
                                </a>
                            </div>
                        `;
                        }
                    }
                ],
                initComplete: function () {
                    $('.dataTables_filter').appendTo('#tableSearch');
                    $('.dataTables_filter').appendTo('.search-input');
                },
                drawCallback: function () {
                    feather.replace();
                    initializeTooltip();
                }
            });
        }
    }


    getProdukPembelianTransaksiPelanggan();

    //ketika button tambah di tekan
    $("#btnTambahPembelian").on("click", function () {
        $("#mdPembelianDariToko").modal("show");
    });

    // Handle submit form cari
    $('#formCariByKodeTransaksi').on('submit', function (e) {
        e.preventDefault();
        let kode = $('input[name="kodetransaksi"]').val().trim();
        if (kode !== '') {
            tableProdukPembelianCustomer.ajax.reload();
            $('#mdPembelianDariToko').modal('hide');
        } else {
            alert('Masukkan Kode Transaksi terlebih dahulu!');
        }
    });

    // Ketika modal ditutup, reset semua field
    $("#mdPembelianDariToko").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formCariByKodeTransaksi")[0].reset();
    });

    function getProdukPembelianTable() {
        if ($('#produkPembelianTable').length > 0) {
            if ($.fn.DataTable.isDataTable('#produkPembelianTable')) {
                $('#produkPembelianTable').DataTable().destroy();
            }

            $('#produkPembelianTable').DataTable({
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

    // Event ketika tombol pilih produk diklik
    $(document).on("click", ".btn-pilihproduk", function () {
        const idProduk = $(this).data("id");

        Swal.fire({
            title: "Pilih Produk Ini?",
            text: "Produk akan ditambahkan ke dalam pembelian.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Pilih",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data via AJAX ke Laravel
                $.ajax({
                    url: "/admin/pembelian/pembeliantoko/storeProdukToPembelianProduk",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: idProduk
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: response.message
                            });

                            $('#kodepembelianproduk').val(response.kode); // <-- tambahkan ini

                            // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                            if ($.fn.DataTable.isDataTable('#produkPembelianTable')) {
                                $('#produkPembelianTable').DataTable().ajax.reload();
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
            }
        });
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit-harga", function () {
        const produkID = $(this).data("id");

        $.ajax({
            url: `/admin/pembelian/pembeliantoko/showPembelianProduk/${produkID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data;

                // Isi modal dengan data
                $("#editid").val(data.id);
                $("#editharga").val(data.harga_beli);

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
                $("#mdEditHargaBeli").modal("show");
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Tidak dapat mengambil data harga.",
                    "error"
                );
            },
        });
    });

    // Ketika modal ditutup, reset semua field
    $("#mdEditHargaBeli").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formUpdateHargaBeli")[0].reset();
    });

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#formUpdateHargaBeli", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const idProdukPembelian = formData.get("id"); // Mengambil nilai input dengan name="id"

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/pembelian/pembeliantoko/updatehargaPembelianProduk/${idProdukPembelian}`, // URL untuk mengupdate data pegawai
            type: "POST", // Gunakan metode POST (atau PATCH jika route mendukung)
            data: formData, // Gunakan FormData
            processData: false, // Jangan proses FormData sebagai query string
            contentType: false, // Jangan set Content-Type secara manual
            success: function (response) {
                // Tampilkan toast sukses
                const successtoastExample =
                    document.getElementById("successToast");
                const toast = new bootstrap.Toast(successtoastExample);
                $(".toast-body").text(response.message);
                toast.show();
                $("#mdEditHargaBeli").modal("hide"); // Tutup modal
                // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                if ($.fn.DataTable.isDataTable('#produkPembelianTable')) {
                    $('#produkPembelianTable').DataTable().ajax.reload();
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    let errorMessage = "";
                    for (let key in errors) {
                        errorMessage += `${errors[key][0]}\n`;
                    }
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(errorMessage);
                    toast.show();
                }
            },
        });
    });

    // ketika button hapus di tekan
    $(document).on("click", ".btn-delete-produk", function () {
        const deleteID = $(this).data("id");

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Produk ini akan dibatalkan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Batal!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/pembelian/pembeliantoko/deletePembelianProduk/${deleteID}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                })
                    .then((response) => {
                        if (response.ok) {
                            Swal.fire(
                                "Dibatalkan!",
                                "Produk berhasil dibatalkan.",
                                "success"
                            );
                            // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                            if ($.fn.DataTable.isDataTable('#produkPembelianTable')) {
                                $('#produkPembelianTable').DataTable().ajax.reload();
                            }
                        } else {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan saat membatalkan produk.",
                                "error"
                            );
                        }
                    })
                    .catch((error) => {
                        Swal.fire(
                            "Gagal!",
                            "Terjadi kesalahan dalam pembatalan produk.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Produk tidak dibatalkan.", "info");
            }
        });
    });

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#storePembelianPelanggan", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/pembelian/pembeliantoko/storePembelianPelanggan`, // URL untuk mengupdate data pegawai
            type: "POST", // Gunakan metode POST (atau PATCH jika route mendukung)
            data: formData, // Gunakan FormData
            processData: false, // Jangan proses FormData sebagai query string
            contentType: false, // Jangan set Content-Type secara manual
            success: function (response) {
                // Tampilkan toast sukses
                const successtoastExample =
                    document.getElementById("successToast");
                const toast = new bootstrap.Toast(successtoastExample);
                $(".toast-body").text(response.message);
                toast.show();

                if ($.fn.DataTable.isDataTable('#produkPembelianTable')) {
                    $('#produkPembelianTable').DataTable().ajax.reload();
                }

                $("#storePembelianPelanggan")[0].reset();
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    let errorMessage = "";
                    for (let key in errors) {
                        errorMessage += `${errors[key][0]}\n`;
                    }
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(errorMessage);
                    toast.show();
                }
            },
        });
    });
})