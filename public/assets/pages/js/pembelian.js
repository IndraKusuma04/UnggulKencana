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
                        data: "total_harga",
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
                        className: "action-table-data justify-content-center",
                        render: function (data, type, row, meta) {
                            if (row.status === 1) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 edit-icon p-2 btn-detail" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="DETAIL TRANSAKSI">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        <a class="me-2 p-2 confirm-payment" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="KONFIRMASI PEMBAYARAN">
                                            <i data-feather="check-circle" class="feather-edit"></i>
                                        </a>
                                        <a class="cancel-payment p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="BATALKAN PEMBAYARAN">
                                            <i data-feather="x-circle" class="feather-trash-2"></i>
                                        </a>
                                    </div>
                                `;
                            } else {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 edit-icon p-2 btn-detail" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="DETAIL TRANSAKSI">
                                            <i data-feather="eye" class="action-eye"></i>
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

    //panggil function getPembelian
    getPembelian();

    //ketika button edit di tekan
    $(document).on("click", ".btn-detail", function () {
        const produkID = $(this).data("id");

        $.ajax({
            url: `/admin/pembelian/getPembelianByID/${produkID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                //Ambil data pertama
                let data = response.Data[0];

                $("#namapelanggan").text(data.pelanggan.nama);
                $("#alamatpelanggan").text(data.pelanggan.alamat);
                $("#kontakpelanggan").text(data.pelanggan.kontak);
                $("#kodetransaksi").text(data.kodetransaksi);
                let tanggalAsli = data.tanggal; // misalnya "2025-04-07"
                let tanggalBaru = new Date(tanggalAsli);

                // Format: 7 April 2025
                let tanggalFormatted = new Intl.DateTimeFormat('id-ID', {
                    day: 'numeric', 
                    month: 'long',
                    year: 'numeric'
                }).format(tanggalBaru);

                $("#tanggaltransaksi").text(tanggalFormatted);

                let statusHTML = "";

                if (data.status == 1) {
                    statusHTML = `<span class="badge bg-warning fw-medium fs-10"><b>BELUM DIBAYAR</b></span>`;
                } else if (data.status == 2) {
                    statusHTML = `<span class="badge bg-success fw-medium fs-10"><b>DIBAYAR</b></span>`;
                } else {
                    statusHTML = `<span class="badge bg-danger fw-medium fs-10"><b>BATAL</b></span>`;
                }

                $("#statustransaksi").html(statusHTML);
                $("#oleh").text(data.user.pegawai.nama);

                // Kosongkan isi tbody dulu
                $("#transaksiProduk tbody").empty();

                // Loop setiap item dalam keranjang
                data.keranjang.forEach(function (item) {
                    let produk = item.produk;

                    let row = `
                        <tr>
                            <td>${produk.kodeproduk}</td>
                            <td>${produk.nama}</td>
                            <td>${parseFloat(item.berat).toFixed(1)} gram</td>
                            <td>Rp ${Number(item.harga_jual).toLocaleString('id-ID')}</td>
                            <td>Rp ${Number(item.total).toLocaleString('id-ID')}</td>
                        </tr>
                    `;

                    $("#transaksiProduk tbody").append(row);
                });

                let subtotal = 0;
                data.keranjang.forEach(function (item) {
                    subtotal += parseFloat(item.total);
                });

                // Ambil nilai diskon dari objek
                let diskonPersen = data.diskon ? parseFloat(data.diskon.nilai) : 0;

                // Hitung nilai diskon dalam rupiah
                let diskonRupiah = subtotal * (diskonPersen / 100);

                // Total harga setelah diskon
                let totalHarga = subtotal - diskonRupiah;

                // Format ke mata uang rupiah
                let formatRupiah = angka => "Rp " + angka.toLocaleString('id-ID');

                // Tampilkan ke elemen HTML
                $("#subtotal").next("h5").text(formatRupiah(subtotal));
                $("#diskon").next("h5").text(`${diskonPersen}% (-${formatRupiah(diskonRupiah)})`);
                $("#totalharga").next("h5").text(formatRupiah(totalHarga));

                // Tampilkan modal edit
                $("#detailTransaksi").modal("show");
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Tidak dapat mengambil data role.",
                    "error"
                );
            },
        });
    });
})