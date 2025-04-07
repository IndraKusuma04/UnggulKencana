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

    //panggul function getKondisi
    getPembelian();

    //ketika button tambah di tekan
    $("#btnTambahKondisi").on("click", function () {
        $("#mdTambahKondisi").modal("show");
    });

    //ketika submit form tambah kondisi
    $("#formTambahKondisi").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        // Buat objek FormData
        const formData = new FormData(this);
        $.ajax({
            url: "/admin/kondisi/storeKondisi", // Endpoint Laravel untuk menyimpan pegawai
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {
                const successtoastExample =
                    document.getElementById("successToast");
                const toast = new bootstrap.Toast(successtoastExample);
                $(".toast-body").text(response.message);
                toast.show();
                $("#mdTambahKondisi").modal("hide"); // Tutup modal
                tableKondisi.ajax.reload(null, false); // Reload data dari server
            },
            error: function (xhr) {
                // Tampilkan pesan error dari server
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
            },
        });
    });

    // Ketika modal ditutup, reset semua field
    $("#mdTambahKondisi").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formTambahKondisi")[0].reset();
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
                fetch(`/admin/kondisi/deleteKondisi/${deleteID}`, {
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
                            tableKondisi.ajax.reload(null, false); // Reload data dari server
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
})