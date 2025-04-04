$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableNamProd) {
            tableNamProd.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Nampan Berhasil Direfresh");
        toast.show();
    });

    const nampanID = window.location.pathname.split("/").pop(); // Mendapatkan ID produk dari URL

    //load data nampan produk
    function getNampanProduk() {
        // Datatable
        if ($('#nampanProdukTable').length > 0) {
            tableNamProd = $('#nampanProdukTable').DataTable({
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
                    url: `/admin/nampan/nampanProduk/getNampanProduk/${nampanID}`, // Ganti dengan URL endpoint server Anda
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
                        data: "produk.kodeproduk",
                    },
                    {
                        data: "produk.nama",
                    },
                    {
                        data: "produk.berat",
                    },
                    {
                        data: "produk.karat",
                    },
                    {
                        data: "produk.harga_jual",
                        render: function (data, type, row) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data);
                        }
                    },
                    {
                        data: null,        // Kolom aksi
                        orderable: false,  // Aksi tidak perlu diurutkan
                        className: "action-table-data",
                        render: function (data, type, row, meta) {
                            return `
                            <div class="edit-delete-action">
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

    //panggul function getNampanProduk
    getNampanProduk();

    //ketika button tambah di tekan
    $("#btnTambahProduk").on("click", function () {
        if (!$.fn.DataTable.isDataTable('#produkTable')) {
            tableProduk = $('#produkTable').DataTable({
                "destroy": true, // Mengizinkan penghancuran instance lama jika perlu
                "scrollX": false,
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
                    url: `/admin/nampan/nampanProduk/getProdukNampan/${nampanID}`,
                    type: 'GET',
                    dataSrc: 'Data'
                },
                columns: [
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <label class="checkboxs">
                                    <input type="checkbox" name="items[]" value="${data}">
                                    <span class="checkmarks"></span>
                                </label>
                            `;
                        },
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                    },
                    { data: "kodeproduk" },
                    { data: "nama" },
                    { data: "berat" },
                ],
                initComplete: function (settings, json) {
                    $('.dataTables_filter').appendTo('#tableSearch');
                    $('.dataTables_filter').appendTo('.search-input');
                },
                drawCallback: function () {
                    feather.replace();
                    initializeTooltip();
                }
            });
        } else {
            tableProduk.ajax.reload(); // Reload data jika DataTable sudah ada
        }

        // Hapus centang semua checkbox saat modal dibuka kembali
        $('#produkTable input[type="checkbox"]').prop('checked', false);

        $("#mdTambahProduk").modal("show");
    });


    $("#mdTambahProduk").on("hidden.bs.modal", function () {
        // Hapus data DataTable jika sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#tableProduk')) {
            $('#tableProduk').DataTable().clear().destroy();
        }
    });

    // Fungsi untuk menangani submit form nampan produk
    $("#formTambahProdukNampan").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        let selectedItems = []; // Array untuk menyimpan data checkbox yang dicentang

        // Loop melalui checkbox yang dicentang
        $("input[name='items[]']:checked").each(function () {
            selectedItems.push($(this).val()); // Ambil nilai checkbox dan masukkan ke array
        });

        const formData = new FormData(this);

        selectedItems.forEach((item, index) => {
            formData.append(`selectedItems[${index}]`, item);
        });
        $.ajax({
            url: `/admin/nampan/nampanproduk/storeProdukNampan/${nampanID}`, // Endpoint Laravel untuk menyimpan nampan produk
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {
                if (response.success == true) {
                    const successtoastExample =
                        document.getElementById("successToast");
                    const toast = new bootstrap.Toast(successtoastExample);
                    $(".toast-body").text(response.message);
                    toast.show();
                    $("#mdTambahProduk").modal("hide"); // Tutup modal
                    tableNamProd.ajax.reload(); // Reload data dari server
                } else
                {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(response.message);
                    toast.show();
                }
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
                    fetch(`/admin/nampan/nampanproduk/deleteNampanProduk/${deleteID}`, {
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
                                tableNamProd.ajax.reload(null, false); // Reload data dari server
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