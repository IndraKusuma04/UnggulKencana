$(document).ready(function () {

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
})