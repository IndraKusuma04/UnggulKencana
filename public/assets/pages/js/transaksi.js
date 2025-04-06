$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableTransaksi) {
            tableTransaksi.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Kondisi Berhasil Direfresh");
        toast.show();
    });

    //load data transaksi
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
                        data: null, // Kolom nomor urut
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // Nomor urut dimulai dari 1
                        },
                        orderable: false,
                    },
                    {
                        data: "kodetransaksi",
                    },
                    {
                        data: "tanggal",
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
                                return `<span class="badge bg-warning fw-medium fs-10"><b>BELUM DIBAYAR</b></span>`;
                            } else if (data == 2) {
                                return `<span class="badge bg-success fw-medium fs-10"><b>DIBAYAR</b></span>`;
                            } else {
                                return `<span class="badge bg-danger fw-medium fs-10"><b>BATAL</b></span>`;
                            }
                        }
                    },
                    {
                        data: null,        // Kolom aksi
                        orderable: false,  // Aksi tidak perlu diurutkan
                        className: "action-table-data",
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

    //panggul function getKondisi
    getTransaksi();

    // ketika button hapus di tekan
    $(document).on("click", ".confirm-payment", function () {
        const deleteID = $(this).data("id");

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Konfirmasi Pembayaran",
            text: "Pembayaran Sudah Dilakukan ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Sudah!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/transaksi/konfirmasiPembayaran/${deleteID}`, {
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
                                "Dikonfirmasi!",
                                "Pembayaran berhasil dikonfirmasi.",
                                "success"
                            );
                            tableTransaksi.ajax.reload(null, false); // Reload data dari server
                        } else {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan saat konfirmasi pembayaran.",
                                "error"
                            );
                        }
                    })
                    .catch((error) => {
                        Swal.fire(
                            "Gagal!",
                            "Terjadi kesalahan dalam konfirmasi pembayaran.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Pembayaran tidak dikonfirmasi.", "info");
            }
        });
    });

    // ketika button hapus di tekan
    $(document).on("click", ".cancel-payment", function () {
        const deleteID = $(this).data("id");

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: "Pembatalan Pembayaran",
            text: "Konfirmasi pembayaran dibatalkan ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Batalkan!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan hapus (gunakan itemId)
                fetch(`/admin/transaksi/konfirmasiPembatalanPembayaran/${deleteID}`, {
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
                                "Dikonfirmasi!",
                                "Pembayaran berhasil dibatalkan.",
                                "success"
                            );
                            tableTransaksi.ajax.reload(null, false); // Reload data dari server
                        } else {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan saat pembatalan pembayaran.",
                                "error"
                            );
                        }
                    })
                    .catch((error) => {
                        Swal.fire(
                            "Gagal!",
                            "Terjadi kesalahan dalam pembatalan pembayaran.",
                            "error"
                        );
                    });
            } else {
                // Jika batal, beri tahu pengguna
                Swal.fire("Dibatalkan", "Pembatalan pembayaran tidak dikonfirmasi.", "info");
            }
        });
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-detail", function () {
        const produkID = $(this).data("id");

        $.ajax({
            url: `/admin/transaksi/getTransaksiByID/${produkID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
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
                            <td>${parseFloat(produk.berat).toFixed(1)} gram</td>
                            <td>Rp ${Number(produk.harga_jual).toLocaleString('id-ID')}</td>
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

    $(document).on("click", "#btnPrintModal", function (e) {
        e.preventDefault();
    
        let modalContent = document.querySelector("#detailTransaksi .modal-content").innerHTML;
    
        let printWindow = window.open('', '', 'width=900,height=700');
    
        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak Detail Transaksi</title>
    
                    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                    <link rel="stylesheet" href="/assets/css/animate.css">
                    <link rel="stylesheet" href="/assets/css/feather.css">
                    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
                    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
                    <link rel="stylesheet" href="/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
                    <link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
                    <link rel="stylesheet" href="/assets/plugins/owlcarousel/owl.carousel.min.css">
                    <link rel="stylesheet" href="/assets/plugins/owlcarousel/owl.theme.default.min.css">
                    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
                    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">
                    <link rel="stylesheet" href="/assets/css/style.css">
                </head>
                <body onload="window.print(); window.close();">
                    ${modalContent}
                </body>
                <script src="/assets/js/jquery-3.7.1.min.js" type="text/javascript"></script>
                <script src="/assets/js/feather.min.js" type="text/javascript"></script>
                <script src="/assets/js/jquery.slimscroll.min.js" type="text/javascript"></script>
                <script src="/assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
                <script src="/assets/js/dataTables.bootstrap5.min.js" type="text/javascript"></script>
                <script src="/assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>
                <script src="/assets/plugins/summernote/summernote-bs4.min.js" type="text/javascript"></script>
                <script src="/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
                <script src="/assets/js/moment.min.js" type="text/javascript"></script>
                <script src="/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js" type="text/javascript"></script>
                <script src="/assets/plugins/owlcarousel/owl.carousel.min.js" type="text/javascript"></script>
                <script src="/assets/plugins/sweetalert/sweetalert2.all.min.js" type="text/javascript"></script>
                <script src="/assets/plugins/sweetalert/sweetalerts.min.js" type="text/javascript"></script>
                <script src="/assets/plugins/theia-sticky-sidebar/ResizeSensor.js" type="text/javascript"></script>
                <script src="/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js" type="text/javascript">
                </script>
                <!-- Datepicker JS -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
                <script src="/assets/js/script.js" type="text/javascript"></script>
            </html>
        `);
    
        printWindow.document.close();
    });
    
    
})