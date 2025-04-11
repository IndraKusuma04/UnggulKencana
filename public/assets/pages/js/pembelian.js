$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tablePembelian) {
            tablePembelian.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Pembelian Berhasil Direfresh");
        toast.show();
    });

    //load data pembelian
    function getPembelian() {
        // Datatable
        if ($('#pembelianTable').length > 0) {
            tablePembelian = $('#pembelianTable').DataTable({
                "scrollX": false, // Jangan aktifkan scroll horizontal secara paksa
                "bFilter": true,
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
                    url: `/admin/pembelian/getPembelian`, // Ganti dengan URL endpoint server Anda
                    type: 'GET', // Metode HTTP (GET/POST)
                    dataSrc: 'Data' // Jalur data di response JSON
                },
                columns: [
                    {
                        data: null, // Kolom nomor urut
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // Nomor urut dimulai dari 1
                        },
                        orderable: false,
                    },
                    {
                        data: "kodepembelian",
                    },
                    {
                        data: "tanggal",
                    },
                    {
                        data: null, // Kolom yang memuat data pelanggan atau suplier
                        render: function (data, type, row) {
                            // Cek apakah pelanggan atau suplier tersedia
                            if (row.suplier_id === null && row.nonsuplierdanpembeli === null) {
                                return row.pelanggan.nama; // Jika `suplier_id` null, tampilkan nama pelanggan
                            } else if (row.pelanggan_id === null && row.nonsuplierdanpembeli === null) {
                                return row.suplier.suplier; // Jika `pelanggan_id` null, tampilkan nama suplier
                            } else if (row.pelanggan_id === null && row.suplier_id === null) {
                                return row.nonsuplierdanpembeli; // Jika `pelanggan_id` null, tampilkan nama suplier
                            } else {
                                return "-"; // Jika keduanya tidak ada, tampilkan tanda "-"
                            }
                        },
                    },
                    {
                        data: "grand_total",
                        render: function (data, type, row) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data);
                        }
                    },
                    {
                        data: 'status',
                        render: function (data, type, row) {
                            // Menampilkan badge sesuai dengan status
                            if (data == 1) {
                                return `<span class="badge bg-success fw-medium fs-10">Active</span>`;
                            } else if (data == 2) {
                                return `<span class="badge bg-danger fw-medium fs-10">In Active</span>`;
                            } else {
                                return `<span class="badge bg-secondary fw-medium fs-10">Unknown</span>`;
                            }
                        }
                    },
                    {
                        data: null,        // Kolom aksi
                        orderable: false,  // Aksi tidak perlu diurutkan
                        className: "action-table-data",
                        render: function (data, type, row, meta) {
                            return `
                            <div class="edit-delete-action">
                                <a class="me-2 p-2 btn-edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit Data">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="confirm-text p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Hapus Data">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>
                        `;
                        }
                    }
                ],
                initComplete: (settings, json) => {
                    $('.dataTables_filter').appendTo('#tableSearch');
                    $('.dataTables_filter').appendTo('.search-input');
                },
                drawCallback: function () {
                    // Re-inisialisasi Feather Icons setelah render ulang DataTable
                    feather.replace();
                    // Re-inisialisasi tooltip Bootstrap setelah render ulang DataTable
                    initializeTooltip();
                }
            });
        }
    }

    //panggil function getPembelian
    getPembelian();

    //ketika button tambah di tekan
    $("#btnTambahPembelian").on("click", function () {
        Swal.fire({
            title: 'Pilih Jenis Pembelian',
            text: 'Apakah produk berasal dari toko atau luar toko?',
            icon: 'question',
            showCloseButton: true, // ⨉ tombol silang di pojok kanan atas
            showDenyButton: true,
            confirmButtonText: '<i class="fa fa-store"></i> Dari Toko',
            denyButtonText: '<i class="fa fa-truck"></i> Diluar Toko',
        }).then((result) => {
            if (result.isConfirmed) {
                // Munculkan form "Dari Toko"
                $("#mdPembelianDariToko").modal("show");
            } else if (result.isDenied) {
                // Munculkan form "Diluar Toko"
                $("#modalDiluarToko").modal("show");
            }
        });
    });

    //ketika submit form tambah kondisi
    $("#formCariByKodeTransaksi").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        const formData = new FormData(this);
        const KodeTransaksi = formData.get("kodetransaksi"); // Mengambil nilai input dengan name="id"

        $.ajax({
            url: `/admin/pembelian/getTransaksiByKodeTransaksi`, // Endpoint Laravel untuk menyimpan pegawai
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {

                // Hanya buka modal jika response.success true
                if (response.success) {

                    const successtoastExample =
                        document.getElementById("successToast");
                    const toast = new bootstrap.Toast(successtoastExample);
                    $(".toast-body").text(response.message);
                    toast.show();

                    $("#mdFormPembelianDariToko").modal("show"); // Tampilkan modal

                    // Pastikan response.Data[0] ada dan memiliki properti keranjang
                    if (response.Data && response.Data[0] && response.Data[0].keranjang) {
                        const keranjangData = response.Data[0].keranjang;

                        // Menyaring data produk dan menyiapkan untuk DataTable
                        const tableData = keranjangData.map(item => ({
                            kodeproduk: item.produk.kodeproduk,
                            nama: item.produk.nama,
                            berat: item.berat,
                            harga: item.harga_jual,
                            id: item.id
                        }));


                        // Inisialisasi DataTable jika belum ada
                        if (!$.fn.DataTable.isDataTable('#pembelianProdukTable')) {
                            tabelTransksiByKodeTransaksi = $('#pembelianProdukTable').DataTable({
                                "scrollX": false, // Jangan aktifkan scroll horizontal secara paksa
                                "bFilter": true,
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
                                data: tableData, // Isi DataTable dengan data yang sudah diformat
                                columns: [
                                    { data: "kodeproduk" },
                                    { data: "nama" },
                                    {
                                        data: "berat",
                                        render: function (data, type, row) {
                                            return parseFloat(data).toFixed(1) + " gram"; // Menampilkan 1 angka desimal
                                        }
                                    },
                                    {
                                        data: "harga",
                                        render: function (data) {
                                            return new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0, // Menentukan jumlah angka di belakang koma
                                                maximumFractionDigits: 0  // Menentukan jumlah angka di belakang koma
                                            }).format(data);
                                        }
                                    },
                                    {
                                        data: null,
                                        orderable: false,
                                        className: "action-table-data",
                                        render: function (data, type, row, meta) {
                                            return `
                                                <div class="edit-delete-action">
                                                    <a class="me-2 p-2 btn-pilihproduk" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="PILIH PRODUK">
                                                        <i data-feather="plus-circle" class="feather-edit"></i>
                                                    </a>
                                                </div>
                                            `;
                                        }
                                    }
                                ],
                                drawCallback: function () {
                                    feather.replace();
                                    initializeTooltip();
                                }
                            });
                        } else {
                            // Jika sudah ada datatable, update isinya
                            tabelTransksiByKodeTransaksi.clear().rows.add(tableData).draw();
                        }


                        $("#titlekodetransaksi").text(response.Data[0].kodetransaksi);
                        $("#detailpelanggan").val(response.Data[0].pelanggan.nama);
                        $("#idpelanggan").val(response.Data[0].pelanggan.id);

                        // 🔁 Muat data pembelian_produk ke DataTable kedua
                        loadDetailPembelianProduk(response.Data[0].kodetransaksi);

                        // loadKondisi
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

                    } else {
                        // Tangani jika tidak ada data keranjang
                        const dangertoastExamplee =
                            document.getElementById("dangerToast");
                        const toast = new bootstrap.Toast(dangertoastExamplee);
                        $(".toast-body").text(response.message);
                        toast.show();
                    }

                } else {
                    // Tangani jika success=false
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(response.message);
                    toast.show();
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
                } else {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(response.message);
                    toast.show();
                }
            }
        });
    });

    // Ketika modal ditutup, reset semua field
    $("#mdPembelianDariToko").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formCariByKodeTransaksi")[0].reset();
    });

    function loadDetailPembelianProduk() {
        if ($('#keranjangPembelianProduk').length > 0) {
            if ($.fn.DataTable.isDataTable('#keranjangPembelianProduk')) {
                $('#keranjangPembelianProduk').DataTable().destroy();
            }

            $('#keranjangPembelianProduk').DataTable({
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
                    url: `/admin/pembelian/getPembelianProduk`,
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataSrc: 'Data'
                },
                columns: [
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1,
                        orderable: false,
                    },
                    { data: "kodeproduk" },
                    {
                        data: "berat",
                        render: data => `${parseFloat(data).toFixed(1)} gram`
                    },
                    {
                        data: null,
                        orderable: false,
                        className: "action-table-data",
                        render: (data, type, row) => `
                            <div class="edit-delete-action">
                                <a class="me-2 p-2 btn-edit-produk" data-id="${row.id}" title="Edit Produk">
                                    <i data-feather="edit"></i>
                                </a>
                                <a class="p-2 btn-delete-produk" data-id="${row.id}" title="Hapus Produk">
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
                    url: "/admin/pembelian/storeProdukToPembelianProduk",
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

                            // Reload DataTable pembelian_produk (pastikan sudah diinisialisasi sebelumnya)
                            if ($.fn.DataTable.isDataTable('#keranjangPembelianProduk')) {
                                $('#keranjangPembelianProduk').DataTable().ajax.reload();
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
    $(document).on("click", ".btn-edit", function () {
        const kondisiID = $(this).data("id");

        $.ajax({
            url: `/admin/kondisi/getKondisiByID/${kondisiID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data[0];

                // Isi modal dengan data pegawai
                $("#editid").val(data.id);
                $("#editkondisi").val(data.kondisi);

                // Tampilkan modal edit
                $("#mdEditKondisi").modal("show");
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

    // Ketika modal ditutup, reset semua field
    $("#mdEditKondisi").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formEditKondisi")[0].reset();
    });

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#formEditKondisi", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const idKondisi = formData.get("id"); // Mengambil nilai input dengan name="id"

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/kondisi/updateKondisi/${idKondisi}`, // URL untuk mengupdate data pegawai
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
                $("#mdEditKondisi").modal("hide"); // Tutup modal
                tableKondisi.ajax.reload(null, false); // Reload data dari server
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
                fetch(`/admin/pembelian/deletePembelianProduk/${deleteID}`, {
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
                            if ($.fn.DataTable.isDataTable('#keranjangPembelianProduk')) {
                                $('#keranjangPembelianProduk').DataTable().ajax.reload();
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
})