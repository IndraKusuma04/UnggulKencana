$(document).ready(function () {

    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //ketika button tambah di tekan
    $("#btnTambahPelanggan").on("click", function () {
        $("#mdTambahPelanggan").modal("show");
    });

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        getKeranjang();
        getNampanProduk('all');
        getNampan();
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Keranjang Berhasil Direfresh");
        toast.show();
    });

    function getNampan() {
        $.ajax({
            url: '/admin/nampan/getNampan',
            type: 'GET',
            success: function (response) {
                const $container = $("#daftarNampan");
                $container.trigger('destroy.owl.carousel'); // Hancurkan instance lama
                $container.empty(); // Kosongkan isi dulu

                // Tambahkan "All Categories"
                $container.append(`
                    <li id="all">
                        <a href="javascript:void(0);">
                            <img src="/assets/img/categories/category-01.png" alt="All Categories">
                        </a>
                        <h6><a href="javascript:void(0);">SEMUA NAMPAN</a></h6>
                        <span>${response.Total} Items</span>
                    </li>
                `);

                // Tambahkan item dari database
                $.each(response.Data, function (key, item) {
                    const imgSrc = item.jenis_produk?.image_jenis_produk
                        ? `/storage/icon/${item.jenis_produk.image_jenis_produk}`
                        : '/assets/img/notfound.png';

                    const title = item.jenis_produk?.jenis_produk || 'Tanpa Jenis';
                    const jumlahProduk = item.produk_count || 0; // <-- ambil dari withCount

                    $container.append(`
                        <li id="${item.id}">
                            <a href="javascript:void(0);">
                                <img src="${imgSrc}" alt="${title}">
                            </a>
                            <h6><a href="javascript:void(0);">${item.nampan}</a></h6>
                            <span>${jumlahProduk} Items</span>
                        </li>
                    `);
                });

                // Re-init Owl Carousel setelah data ditambahkan
                $container.owlCarousel({
                    items: 6,
                    loop: false,
                    margin: 8,
                    nav: true,
                    dots: false,
                    autoplay: false,
                    smartSpeed: 1000,
                    navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                    responsive: {
                        0: {
                            items: 2
                        },
                        500: {
                            items: 3
                        },
                        768: {
                            items: 4
                        },
                        991: {
                            items: 5
                        },
                        1200: {
                            items: 6
                        },
                        1401: {
                            items: 6
                        }
                    }
                });
            },
            error: function (err) {
                console.error('Gagal mengambil data nampan:', err);
                $("#daftarNampan").html('<li><span>Gagal memuat data nampan.</span></li>');
            }
        });
    }

    getNampan();

    function getNampanProduk(nampan) {
        $.ajax({
            url: `/admin/nampan/nampanProduk/getNampanProduk/${nampan}`,
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    const produkContainer = $("#daftarProduk");
                    produkContainer.empty(); // Hapus semua tab_content yang lama

                    let tabContent = `
                        <div class="tab_content active" data-tab="${nampan}">
                            <div class="row">
                    `;

                    if (response.Data.length === 0) {
                        tabContent += `
                            <div class="col-12 text-center">
                                <p><b>Produk tidak ditemukan.</b></p>
                            </div>
                        `;
                    } else {
                        $.each(response.Data, function (index, item) {
                            const formatter = new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0
                            });

                            const hargajual = formatter.format(item.produk.hargatotal);

                            tabContent += `
                                <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3 pe-2">
                                    <div class="product-info default-cover card">
                                        <a href="javascript:void(0);" class="img-bg">
                                            <img src="/storage/produk/${item.produk.image_produk}" alt="Products" width="100px" height="100px">
                                        </a>
                                        <h6 class="cat-name"><a href="javascript:void(0);">KODE: ${item.produk.kodeproduk}</a></h6>
                                        <h6 class="product-name"><a href="javascript:void(0);">NAMA: ${item.produk.nama}</a></h6>
                                        <div class="d-flex align-items-center justify-content-between price">
                                            <span>BERAT: ${parseFloat(item.produk.berat).toFixed(1)} gram</span>
                                            <p>${hargajual}</p>
                                        </div>
                                        <div class="align-items-center justify-content-between price text-center">
                                            <button data-id="${item.produk.id}" data-berat="${item.produk.berat}" data-karat="${item.produk.karat}" data-harga="${item.produk.harga_jual}" data-lingkar="${item.produk.lingkar}" data-panjang="${item.produk.panjang}" class="btn btn-sm btn-secondary  mr-2 add-to-cart ">ADD TO CART
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }

                    tabContent += `</div></div>`; // Tutup row & tab_content
                    produkContainer.append(tabContent);

                    // Feather icon replace (kalau pakai Feather)
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                } else {
                    console.log('Data kosong atau gagal');
                }
            },
            error: function (err) {
                console.log('Gagal mengambil data:', err);
            }
        });
    }

    $(document).on('click', '#daftarNampan li', function () {
        var $this = $(this);
        var $theTab = $this.attr('id');

        if ($this.hasClass('active')) {
            // Sudah aktif, tidak lakukan apa-apa
            return;
        }

        // Hapus 'active' dari semua tab dan konten
        $this.closest('.tabs_wrapper').find('ul.tabs li, .tabs_container .tab_content').removeClass('active');

        // Tambahkan 'active' ke tab yang diklik dan konten terkait
        $('.tabs_container .tab_content[data-tab="' + $theTab + '"], ul.tabs li[id="' + $theTab + '"]').addClass('active');

        getNampanProduk($theTab)
    });

    getNampanProduk('all')

    let totalHargaKeranjang = 0

    function getKeranjang() {
        $.ajax({
            url: `/admin/keranjang/getKeranjang`,
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    let html = '';
                    $.each(response.Data, function (index, item) {
                        const hargaFormatted = parseInt(item.total).toLocaleString('id-ID');
                        html += `
                            <div class="product-list d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center product-info">
                                    <a href="javascript:void(0);" class="img-bg">
                                        <img src="/storage/produk/${item.produk.image_produk}" alt="Products" width="50">
                                    </a>
                                    <div class="info">
                                        <span>${item.produk.kodeproduk}</span>
                                        <h6><a href="javascript:void(0);">${item.produk.nama}</a></h6>
                                        <p>Rp${hargaFormatted}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center action">
                                    <a class="btn-icon delete-icon confirm-text" href="javascript:void(0);" data-id="${item.id}">
                                        <i data-feather="trash-2" class="feather-14"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                    });

                    totalHargaKeranjang = Number(response.TotalHargaKeranjang);

                    // Format Grand Total ke dalam format mata uang Rupiah
                    const subtotalHarga = totalHargaKeranjang.toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    });

                    $('.product-wrap').html(html);
                    $('#keranjangCount').text(response.TotalKeranjang); // update jumlah produk
                    $('#subtotal').text(subtotalHarga); // update jumlah produk
                    $('#total').text(subtotalHarga); // update jumlah produk
                    $('#grandTotal').text(subtotalHarga); // update jumlah produk


                    $.ajax({
                        url: "/admin/transaksi/getKodeTransaksi", // Endpoint untuk mendapatkan data pelanggan
                        type: "GET",
                        success: function (response) {
                            $("#kodetransaksi").html(response.kodetransaksi); // Masukkan data ke select
                        },
                        error: function () {
                            Swal.fire(
                                "Gagal!",
                                "Tidak dapat mengambil data kodetransaksi.",
                                "error"
                            );
                        },
                    });

                    $.ajax({
                        url: "/admin/pelanggan/getPelanggan", // Endpoint untuk mendapatkan data pelanggan
                        type: "GET",
                        success: function (response) {
                            let options
                            response.Data.forEach((item) => {
                                options += `<option value="${item.id}">${item.nama}</option>`;
                            });
                            $("#pelanggan").html(options); // Masukkan data ke select
                        },
                        error: function () {
                            Swal.fire(
                                "Gagal!",
                                "Tidak dapat mengambil data pelanggan.",
                                "error"
                            );
                        },
                    });

                    $.ajax({
                        url: "/admin/diskon/getDiskon", // Endpoint untuk mendapatkan data pelanggan
                        type: "GET",
                        success: function (response) {
                            let options
                            response.Data.forEach((item) => {
                                options += `<option value="${item.id}" data-nilai="${item.nilai + " %"}">${item.diskon}</option>`;
                            });
                            $("#diskon").html(options); // Masukkan data ke select
                            $('#diskonDipilih').text(0 + " %"); // update jumlah produk
                        },
                        error: function () {
                            Swal.fire(
                                "Gagal!",
                                "Tidak dapat mengambil data diskon.",
                                "error"
                            );
                        },
                    });

                    feather.replace(); // refresh feather icon
                }
            }
        });
    }

    getKeranjang();

    // Event listener untuk perubahan dropdown
    $(document).on("change", "#diskon", function getDiscount() {
        const diskonPersen = parseFloat($(this).find(':selected').data('nilai')) || 0;
        const diskonDesimal = diskonPersen / 100;
        const grandTotal = totalHargaKeranjang * (1 - diskonDesimal);

        // Format Grand Total ke dalam format mata uang Rupiah
        const grandTotalFormatted = grandTotal.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        $('#diskonDipilih').text(diskonPersen + "%");
        $('#total').text(grandTotalFormatted);
        $('#grandTotal').text(grandTotalFormatted);
    });

    // Event listener untuk tombol "Tambah ke Keranjang"
    $(document).on("click", ".add-to-cart", function () {
        const produkId = $(this).data("id");
        const dataBerat = $(this).data("berat");
        const dataKarat = $(this).data("karat");
        const dataHarga = $(this).data("harga");
        const dataLingkar = $(this).data("lingkar");
        const dataPanjang = $(this).data("panjang");
        $.ajax({
            url: "/admin/keranjang/addToCart",
            method: "POST",
            data: {
                id: produkId,
                berat: dataBerat,
                karat: dataKarat,
                harga_jual: dataHarga,
                lingkar: dataLingkar,
                panjang: dataPanjang,
                _token: $('meta[name="csrf-token"]').attr('content') // Jika menggunakan Laravel
            },
            success: function (response) {
                if (response.success === true) {
                    // Menampilkan notifikasi sukses menggunakan Bootstrap Toast
                    const successtoastExample =
                        document.getElementById("successToast");
                    const toast = new bootstrap.Toast(successtoastExample);
                    $(".toast-body").text(response.message);
                    toast.show();

                    getKeranjang();
                } else if (response.status === "error") {
                    // Menampilkan notifikasi error menggunakan Bootstrap Toast
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(response.message);
                    toast.show();
                }
            },
            error: function () {
                const dangertoastExample =
                    document.getElementById("dangerToast");
                const toast = new bootstrap.Toast(dangertoastExample);
                document.getElementById("dangerToastMessage").innerText =
                    "Terjadi kesalahan pada server."; // Pesan fallback
                toast.show();
            }
        });
    });

    // ketika button hapus di tekan
    $(document).on("click", "#deleteSemua", function () {
        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Produk ini akan dibatalkan semua ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Batalkan!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/keranjang/deleteKeranjangAll`, {
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
                            getKeranjang();
                            getNampan();
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
                            "Terjadi kesalahan dalam membatalkan produk.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Produk tidak dibatalkan.", "info");
            }
        });
    });

    // ketika button hapus di tekan
    $(document).on("click", ".confirm-text", function () {
        const deleteID = $(this).data("id");

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Produk ini akan dibatalkan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Batal!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/keranjang/deleteKeranjangByID/${deleteID}`, {
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
                            getKeranjang();
                            getNampan();
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
                            "Terjadi kesalahan dalam membatalkan produk.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Data tidak dibatalkan.", "info");
            }
        });
    });

    //ketika submit form tambah kondisi
    $("#formTambahPelanggan").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file

        // Buat objek FormData
        const formData = new FormData(this);
        $.ajax({
            url: "/admin/pelanggan/storePelanggan", // Endpoint Laravel untuk menyimpan pegawai
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
                $("#mdTambahPelanggan").modal("hide"); // Tutup modal

                $.ajax({
                    url: "/admin/pelanggan/getPelanggan", // Endpoint untuk mendapatkan data pelanggan
                    type: "GET",
                    success: function (response) {
                        let options
                        response.Data.forEach((item) => {
                            options += `<option value="${item.id}">${item.nama}</option>`;
                        });
                        $("#pelanggan").html(options); // Masukkan data ke select
                    },
                    error: function () {
                        Swal.fire(
                            "Gagal!",
                            "Tidak dapat mengambil data pelanggan.",
                            "error"
                        );
                    },
                });
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

    // ketika button payment di tekan
    $(document).on("click", "#payment", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Konfirmasi Pembayaran",
            text: "Apakah kamu yakin ingin melanjutkan ke pembayaran?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Lanjutkan!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                prosesCheckout(); // Panggil fungsi pembayaran
            } else {
                Swal.fire("Dibatalkan", "Transaksi belum diproses.", "info");
            }
        });
    });

    function prosesCheckout() {
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        const pelanggan = $("#pelanggan").val();
        const diskon = $("#diskon").val();
        const transaksi_id = $("#kodetransaksi").text();
        const grandTotalText = $("#grandTotal").text();

        if (!pelanggan || !diskon || pelanggan === "Walk in Customer" || diskon === "zero") {
            showToast("danger", "Pelanggan atau diskon belum dipilih.");
            return;
        }

        const grandTotal = parseInt(grandTotalText.replace(/[^\d]/g, ""), 10); // Hapus format Rp

        // Ambil kodekeranjang dulu
        $.ajax({
            url: "/admin/keranjang/getKodeKeranjang", // endpoint khusus untuk ambil kodekeranjang
            type: "GET",
            success: function (res) {
                if (res.success && res.kode) {
                    const kodekeranjang = res.kode;

                    // Kirim data pembayaran lengkap
                    $.ajax({
                        url: "/admin/transaksi/payment", // Endpoint Laravel
                        type: "POST",
                        data: {
                            _token: csrfToken,
                            pelangganID: pelanggan,
                            diskonID: diskon,
                            transaksiID: transaksi_id,
                            kodeKeranjangID: kodekeranjang,
                            total: grandTotal,
                        },
                        success: function (res) {
                            if (res.success) {
                                showToast("success", res.message);
                                getKeranjang(); 
                                getNampanProduk('all');
                                getNampan();

                                $("#grandTotal").text("Rp0");
                                $("#total").text("Rp0");
                                $("#subtotal").text("Rp0");
                                $("#diskonDipilih").text("0 %");
                                $("#pelanggan").val("");
                                $("#diskon").val("");
                                // window.open(`/admin/transaksi/cetak/${res.transaksi_id}`, "_blank"); // optional
                            } else {
                                showToast("danger", res.message);
                            }
                        },
                        error: function () {
                            showToast("danger", "Gagal memproses pembayaran.");
                        }
                    });

                } else {
                    showToast("danger", "Gagal mengambil kode keranjang.");
                }
            },
            error: function () {
                showToast("danger", "Terjadi kesalahan saat mengambil kode keranjang.");
            }
        });
    }

    function showToast(type, message) {
        const toastId = type === "success" ? "successToast" : "dangerToast";
        const toast = new bootstrap.Toast(document.getElementById(toastId));
        $("#" + toastId + " .toast-body").text(message);
        toast.show();
    }

    //load data pelanggan
    function getTransaksi() {
        // Datatable
        if ($('#transaksiTable').length > 0) {
            tableTransaksi = $('#transaksiTable').DataTable({
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
                    url: `/admin/transaksi/getTransaksi`, // Ganti dengan URL endpoint server Anda
                    type: 'GET', // Metode HTTP (GET/POST)
                    dataSrc: 'Data' // Jalur data di response JSON
                },
                columns: [
                    {
                        data: "tanggal",
                    },
                    {
                        data: "kodetransaksi",
                    },
                    {
                        data: "pelanggan.nama",
                    },
                    {
                        data: "total",
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

   

    //ketika button tambah di tekan
    $("#modalTransaksi").on("click", function () {
        if ($.fn.DataTable.isDataTable('#transaksiTable')) {
            $('#transaksiTable').DataTable().clear().destroy();
        }
        getTransaksi();
        $("#mdTransaksi").modal("show");
    });

})