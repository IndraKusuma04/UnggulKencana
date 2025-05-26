$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableNampan) {
            tableNampan.ajax.reload(null, false); // Reload data dari server
        }
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data Nampan Berhasil Direfresh",
            showConfirmButton: false,
            timer: 1000
        });
    });

    //load data nampan
    function getNampan() {
        // Datatable
        if ($('#nampanTable').length > 0) {
            tableNampan = $('#nampanTable').DataTable({
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
                    url: `/admin/nampan/getNampan`, // Ganti dengan URL endpoint server Anda
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
                        data: "nampan",
                    },
                    {
                        data: "jenis_produk.jenis_produk",
                    },
                    {
                        data: 'status',
                        render: function (data, type, row) {
                            // Menampilkan badge sesuai dengan status
                            if (data == 1) {
                                return `<span class="badge bg-success fw-medium fs-10"><b>ACTIVE</b></span>`;
                            } else if (data == 2) {
                                return `<span class="badge bg-danger fw-medium fs-10"><b>IN ACTIVE</b></span>`;
                            } else {
                                return `<span class="badge bg-secondary fw-medium fs-10"><b>UNKNOWN</b></span>`;
                            }
                        }
                    },
                    {
                        data: 'status_final',        // Kolom aksi
                        orderable: false,  // Aksi tidak perlu diurutkan
                        className: "action-table-data",
                        render: function (data, type, row, meta) {
                            if (data == 1) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 edit-icon p-2 btn-detail" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="DETAIL DATA">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        <a class="me-2 p-2 btn-edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="EDIT DATA">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a class="me-2 final-nampan p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="FINAL NAMPAN">
                                            <i data-feather="check" class="feather-print"></i>
                                        </a>
                                        <a class="confirm-text p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="HAPUS DATA">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 edit-icon p-2 btn-detail" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="DETAIL DATA">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        <a class="me-2 final-nampan p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="FINAL NAMPAN">
                                            <i data-feather="check" class="feather-print"></i>
                                        </a>
                                        <a class="confirm-text p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="HAPUS DATA">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                    </div>
                                `;
                            }
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

    //panggul function getKondisi
    getNampan();

    //ketika button tambah di tekan
    $("#btnTambahNampan").on("click", function () {
        $.ajax({
            url: "/admin/jenisproduk/getJenisProduk", // Endpoint untuk mendapatkan data jabatan
            type: "GET",
            success: function (response) {
                let options
                response.Data.forEach((item) => {
                    options += `<option value="${item.id}">${item.jenis_produk}</option>`;
                });
                $("#jenisProduk").html(options); // Masukkan data ke select
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Tidak dapat mengambil data jenis produk.",
                    "error"
                );
            },
        });
        $("#mdTambahNampan").modal("show");
    });

    //ketika submit form tambah nampan
    $("#formTambahNampan").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        // Buat objek FormData
        const formData = new FormData(this);
        $.ajax({
            url: "/admin/nampan/storeNampan", // Endpoint Laravel untuk menyimpan pegawai
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1000
                });
                $("#mdTambahNampan").modal("hide"); // Tutup modal
                tableNampan.ajax.reload(null, false); // Reload data dari server
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

    // Ketika modal ditutup, reset semua field
    $("#mdTambahNampan").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formTambahNampan")[0].reset();
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit", function () {
        const nampanID = $(this).data("id");

        $.ajax({
            url: `/admin/nampan/getNampanByID/${nampanID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data[0];

                // Isi modal dengan data pegawai
                $("#editid").val(data.id);
                $("#editnampan").val(data.nampan);
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
                        $("#editJenisProduk").html(options);
                    },
                });

                // Tampilkan modal edit
                $("#mdEditNampan").modal("show");
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
    $("#mdEditNampan").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formEditNampan")[0].reset();
    });

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#formEditNampan", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const idNampan = formData.get("id"); // Mengambil nilai input dengan name="id"

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/nampan/updateNampan/${idNampan}`, // URL untuk mengupdate data pegawai
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
                $("#mdEditNampan").modal("hide"); // Tutup modal
                tableNampan.ajax.reload(null, false); // Reload data dari server
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

    // ketika button hapus di tekan
    $(document).on("click", ".final-nampan", function () {
        const deleteID = $(this).data("id");

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Konfirmasi Final Nampan",
            text: "Nampan Akan Difinal!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Final!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/nampan/finalNampan/${deleteID}`, {
                    method: "GET",
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
                                "Difinal!",
                                "Nampan berhasil difinal.",
                                "success"
                            );
                            tableNampan.ajax.reload(null, false); // Reload data dari server
                        } else {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan saat memfinal nampan.",
                                "error"
                            );
                        }
                    })
                    .catch((error) => {
                        Swal.fire(
                            "Gagal!",
                            "Terjadi kesalahan dalam memfinal nampan.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Nampan tidak difinal.", "info");
            }
        });
    });

    // ketika button hapus di tekan
    $(document).on("click", ".confirm-text", function () {
        const deleteID = $(this).data("id");

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data ini akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/nampan/deleteNampan/${deleteID}`, {
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
                                "Dihapus!",
                                "Data berhasil dihapus.",
                                "success"
                            );
                            tableNampan.ajax.reload(null, false); // Reload data dari server
                        } else {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan saat menghapus data.",
                                "error"
                            );
                        }
                    })
                    .catch((error) => {
                        Swal.fire(
                            "Gagal!",
                            "Terjadi kesalahan dalam penghapusan data.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Data tidak dihapus.", "info");
            }
        });
    });

    // Ketika tombol detail produk ditekan
    $(document).on("click", ".btn-detail", function () {
        const produkID = $(this).data("id");
        const url = `/admin/nampan/NampanProduk/${produkID}`; // Sesuaikan dengan route Laravel
        window.location.href = url;
    });
})