$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableSuplier) {
            tableSuplier.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Suplier Berhasil Direfresh");
        toast.show();
    });

    //load data Suplier
    function getSuplier() {
        // Datatable
        if ($('#suplierTable').length > 0) {
            tableSuplier = $('#suplierTable').DataTable({
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
                    url: `/admin/suplier/getSuplier`, // Ganti dengan URL endpoint server Anda
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
                        data: "kodesuplier",
                    },
                    {
                        data: "nama",
                    },
                    {
                        data: "alamat",
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

    //panggul function getSuplier
    getSuplier();

    //ketika button tambah di tekan
    $("#btnTambahSuplier").on("click", function () {
        $("#mdTambahSuplier").modal("show");
    });

    //ketika submit form tambah kondisi
    $("#formTambahSuplier").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        // Buat objek FormData
        const formData = new FormData(this);
        $.ajax({
            url: "/admin/suplier/storeSuplier", // Endpoint Laravel untuk menyimpan pegawai
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
                $("#mdTambahSuplier").modal("hide"); // Tutup modal
                tableSuplier.ajax.reload(null, false); // Reload data dari server
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
    $("#mdTambahSuplier").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formTambahSuplier")[0].reset();
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit", function () {
        const suplierID = $(this).data("id");

        $.ajax({
            url: `/admin/suplier/getSuplierByID/${suplierID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data[0];

                // Isi modal dengan data pegawai
                $("#editid").val(data.id);
                $("#editsuplier").val(data.nama);
                $("#editkontak").val(data.kontak);
                $("#editalamat").val(data.alamat);

                // Tampilkan modal edit
                $("#mdEditSuplier").modal("show");
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
    $("#mdEditSuplier").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formEditSuplier")[0].reset();
    });

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#formEditSuplier", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const idSuplier = formData.get("id"); // Mengambil nilai input dengan name="id"

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/suplier/updateSuplier/${idSuplier}`, // URL untuk mengupdate data pegawai
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
                $("#mdEditSuplier").modal("hide"); // Tutup modal
                tableSuplier.ajax.reload(null, false); // Reload data dari server
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
                fetch(`/admin/suplier/deleteSuplier/${deleteID}`, {
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
                            tableSuplier.ajax.reload(null, false); // Reload data dari server
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