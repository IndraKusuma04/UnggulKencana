$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableProduk) {
            tableProduk.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Produk Berhasil Direfresh");
        toast.show();
    });

    //load data produk
    function getProduk() {
        // Datatable
        if ($('#produkTable').length > 0) {
            tableProduk = $('#produkTable').DataTable({
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
                    url: `/admin/produk/getProduk`, // Ganti dengan URL endpoint server Anda
                    type: 'GET', // Metode HTTP (GET/POST)
                    dataSrc: 'Data' // Jalur data di response JSON
                },
                columns: [
                    {
                        data: null, // Kolom nomor urut
                        render: function (data, type, row, meta) {
                            return meta.row + 1 + "."; // Nomor urut dimulai dari 1
                        },
                        orderable: false,
                    },
                    {
                        data: "kodeproduk",
                    },
                    {
                        data: "image_produk", // Nama field dari API
                        render: function (data, type, row) {
                            let timestamp = new Date().getTime(); // Gunakan timestamp untuk cache busting
                            return `
                            <div class="productimgname">
                                <a href="javascript:void(0);" class="product-img stock-img">
                                    <img src="/storage/produk/${data}?t=${timestamp}" alt="produk">
                                </a>
                                <a href="javascript:void(0);">${row.nama}</a>
                            </div>
                        `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "berat",
                        render: function (data, type, row) {
                            return parseFloat(data).toFixed(1) + " gram"; // Menampilkan 1 angka desimal
                        }
                    },
                    {
                        data: "karat",
                        render: function (data, type, row) {
                            return data + " K"; // Menampilkan K
                        }
                    },
                    {
                        data: "harga_jual",
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
                                <a class="me-2 edit-icon p-2 btn-detail" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Detail Produk">
                                    <i data-feather="eye" class="action-eye"></i>
                                </a>
                                <a class="me-2 p-2 btn-edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit Produk">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="me-2 print-barcode p-2 btn-print" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Cetak Barcode">
                                    <i data-feather="printer" class="feather-print"></i>
                                </a>
                                <a class="confirm-text p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Hapus Produk">
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

    //panggul function getProduk
    getProduk();

    function uploadImage(inputId, previewId) {
        const inputFile = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewId);

        inputFile.addEventListener("change", () => {
            const file = inputFile.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                previewContainer.innerHTML = "";
                const img = document.createElement("img");
                img.src = reader.result;
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    }

    function editUploadImage(inputId, previewId) {
        const inputFile = document.getElementById(inputId);
        const previewContainer = $("#" + previewId); // Gunakan jQuery

        inputFile.addEventListener("change", () => {
            const file = inputFile.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                // Hapus background image
                previewContainer.css("background-image", "");

                // Masukkan gambar ke dalam preview div
                previewContainer.html(""); // Hapus isi sebelumnya
                const img = document.createElement("img");
                img.src = reader.result;
                img.style.width = "100%";
                img.style.height = "100%";
                img.style.objectFit = "cover"; // Agar rapi dalam div
                previewContainer.append(img);
            };

            reader.readAsDataURL(file);
        });
    }

    //ketika button tambah di tekan
    $("#btnTambahProduk").on("click", function () {
        uploadImage("imageproduk", "imageProdukPreview");

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

            $("#mdTambahProduk").modal("show");
        
    });

    //ketika submit form tambah produk
    $("#formTambahProduk").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        // Buat objek FormData
        const formData = new FormData(this);
        $.ajax({
            url: "/admin/produk/storeProduk", // Endpoint Laravel untuk menyimpan pegawai
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
                $("#mdTambahProduk").modal("hide"); // Tutup modal
                tableProduk.ajax.reload(null, false); // Reload data dari server
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
    $("#mdTambahProduk").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formTambahProduk")[0].reset();
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit", function () {
        const produkID = $(this).data("id");

        $.ajax({
            url: `/admin/produk/getProdukByID/${produkID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data[0];

                // Isi modal dengan data produk
                $("#editid").val(data.id);
                $("#editkodeprouk").val(data.kodeproduk);

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

                $("#editnama").val(data.nama);
                $("#editberat").val(data.berat);
                $("#editkarat").val(data.karat);
                $("#editlingkar").val(data.lingkar);
                $("#editpanjang").val(data.panjang);
                $("#edithargajual").val(data.harga_jual);
                $("#edithargabeli").val(data.harga_beli);
                $("#editketerangan").val(data.keterangan);

                // Update preview gambar dengan background-image
                let imageSrc = data.image_produk
                    ? `/storage/produk/${data.image_produk}`
                    : `/assets/img/notfound.png`;

                $("#editImageProdukPreview").css({
                    "background-image": `url('${imageSrc}')`,
                    "background-size": "cover",
                    "background-position": "center",
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