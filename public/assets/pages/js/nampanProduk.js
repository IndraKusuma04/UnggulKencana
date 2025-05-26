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
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data Nampan Berhasil Direfresh",
            showConfirmButton: false,
            timer: 1000
        });
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
                        render: function (data, type, row) {
                            return parseFloat(data).toFixed(1) + " gram"; // Menampilkan 1 angka desimal
                        }
                    },
                    {
                        data: "produk.karat",
                        render: function (data, type, row) {
                            return data + " K"; // Menampilkan K
                        }
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
                                <a class="confirm-text p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="HAPUS DATA">
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
                    {
                        data: "berat",
                        render: function (data, type, row) {
                            return parseFloat(data).toFixed(1) + " gram"; // Menampilkan 1 angka desimal
                        }
                    },
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
        event.preventDefault(); // Cegah submit default

        let selectedItems = [];

        $("input[name='items[]']:checked").each(function () {
            selectedItems.push($(this).val());
        });

        if (selectedItems.length === 0) {
            Swal.fire("Peringatan", "Silakan pilih minimal satu produk.", "warning");
            return;
        }

        // Tampilkan SweetAlert untuk memilih jenis
        Swal.fire({
            title: "PILIH JENIS BARANG",
            input: "select",
            inputOptions: {
                awal: "STOK AWAL",
                masuk: "BARANG MASUK"
            },
            inputPlaceholder: "PILIH JENIS ..",
            showCancelButton: true,
            confirmButtonText: "LANJUTAKAN",
            cancelButtonText: "BATAL",
            customClass: {
                input: 'select form-control'  // Ini membuat <select> punya class "form-control"
            },
            inputValidator: (value) => {
                if (!value) {
                    return "Silakan pilih jenis transaksi terlebih dahulu!";
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const jenis = result.value;

                const formData = new FormData($("#formTambahProdukNampan")[0]);

                selectedItems.forEach((item, index) => {
                    formData.append(`selectedItems[${index}]`, item);
                });

                formData.append("jenis", jenis); // Tambahkan jenis ke formData

                // AJAX tetap jalan setelah pilih jenis
                $.ajax({
                    url: `/admin/nampan/nampanproduk/storeProdukNampan/${nampanID}`,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success == true) {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil",
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $("#mdTambahProduk").modal("hide");
                            tableNamProd.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: response.message || "Terjadi kesalahan saat memproses data.",
                            });
                        }
                    },
                    error: function (xhr) {
                        // Error handler tetap sama seperti sebelumnya
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
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Terjadi Kesalahan",
                                text: xhr.responseJSON?.message || "Tidak dapat memproses permintaan.",
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    },
                });
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