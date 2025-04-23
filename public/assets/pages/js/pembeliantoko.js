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
        $(".toast-body").text("Data Pembelian Berhasil Direfresh");
        toast.show();
    });

    //load data transaksi
    function getransaksi() {
        // Datatable
        if ($('#produkTransaksiTable').length > 0) {
            tableCustomer = $('#produkTransaksiTable').DataTable({
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
                    url: `/admin/pembelian/pembeliantoko/getTransaksiByKodeTransaksi`, // Ganti dengan URL endpoint server Anda
                    type: 'POST', // Metode HTTP (GET/POST)
                    dataSrc: 'Data' // Jalur data di response JSON
                },
                columns: [
                    { data: "kodeproduk" },
                    { data: "nama" },
                    {
                        data: "berat",
                        render: function (data, type, row) {
                            return parseFloat(data).toFixed(1) + " gram"; // Menampilkan 1 angka desimal
                        }
                    },
                    {
                        data: "harga",
                        render: function (data) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0, // Menentukan jumlah angka di belakang koma
                                maximumFractionDigits: 0  // Menentukan jumlah angka di belakang koma
                            }).format(data);
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: "action-table-data justify-content-center",
                        render: function (data, type, row, meta) {
                            return `
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2 btn-pilihproduk" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="PILIH PRODUK">
                                        <i data-feather="plus-circle" class="feather-edit"></i>
                                    </a>
                                </div>
                            `;
                        }
                    }
                ],
                drawCallback: function () {
                    // Re-inisialisasi Feather Icons setelah render ulang DataTable
                    feather.replace();
                    // Re-inisialisasi tooltip Bootstrap setelah render ulang DataTable
                    initializeTooltip();
                }
            });
        }
    }

    getransaksi();



    // //ketika submit form tambah kondisi
    // $("#formCariByKodeTransaksi").on("submit", function (event) {
    //     event.preventDefault(); // Mencegah form submit secara default
    //     const formData = new FormData(this);
    //     const KodeTransaksi = formData.get("kodetransaksi"); // Mengambil nilai input dengan name="id"

    //     $.ajax({
    //         url: `/admin/pembelian/pembeliantoko/getTransaksiByKodeTransaksi`, // Endpoint Laravel untuk menyimpan pegawai
    //         type: "POST",
    //         data: formData,
    //         processData: false, // Agar data tidak diubah menjadi string
    //         contentType: false, // Agar header Content-Type otomatis disesuaikan
    //         success: function (response) {

    //             // Hanya buka modal jika response.success true
    //             if (response.success) {

    //                 const successtoastExample =
    //                     document.getElementById("successToast");
    //                 const toast = new bootstrap.Toast(successtoastExample);
    //                 $(".toast-body").text(response.message);
    //                 toast.show();

    //                 // Pastikan response.Data[0] ada dan memiliki properti keranjang
    //                 if (response.Data && response.Data[0] && response.Data[0].keranjang) {
    //                     const keranjangData = response.Data[0].keranjang;

    //                     // Menyaring data produk dan menyiapkan untuk DataTable
    //                     const tableData = keranjangData.map(item => ({
    //                         kodeproduk: item.produk.kodeproduk,
    //                         nama: item.produk.nama,
    //                         berat: item.berat,
    //                         harga: item.harga_jual,
    //                         id: item.id
    //                     }));


    //                     // Inisialisasi DataTable jika belum ada
    //                     if (!$.fn.DataTable.isDataTable('#produkTransaksiTable')) {
    //                         tabelTransksiByKodeTransaksi = $('#produkTransaksiTable').DataTable({
    //                             "scrollX": false, // Jangan aktifkan scroll horizontal secara paksa
    //                             "bFilter": true,
    //                             "sDom": 'fBtlpi',
    //                             "ordering": true,
    //                             "language": {
    //                                 search: ' ',
    //                                 sLengthMenu: '_MENU_',
    //                                 searchPlaceholder: "Search",
    //                                 info: "_START_ - _END_ of _TOTAL_ items",
    //                                 paginate: {
    //                                     next: ' <i class=" fa fa-angle-right"></i>',
    //                                     previous: '<i class="fa fa-angle-left"></i> '
    //                                 },
    //                             },
    //                             data: tableData, // Isi DataTable dengan data yang sudah diformat
    //                             columns: [
    //                                 { data: "kodeproduk" },
    //                                 { data: "nama" },
    //                                 {
    //                                     data: "berat",
    //                                     render: function (data, type, row) {
    //                                         return parseFloat(data).toFixed(1) + " gram"; // Menampilkan 1 angka desimal
    //                                     }
    //                                 },
    //                                 {
    //                                     data: "harga",
    //                                     render: function (data) {
    //                                         return new Intl.NumberFormat('id-ID', {
    //                                             style: 'currency',
    //                                             currency: 'IDR',
    //                                             minimumFractionDigits: 0, // Menentukan jumlah angka di belakang koma
    //                                             maximumFractionDigits: 0  // Menentukan jumlah angka di belakang koma
    //                                         }).format(data);
    //                                     }
    //                                 },
    //                                 {
    //                                     data: null,
    //                                     orderable: false,
    //                                     className: "action-table-data justify-content-center",
    //                                     render: function (data, type, row, meta) {
    //                                         return `
    //                                             <div class="edit-delete-action">
    //                                                 <a class="me-2 p-2 btn-pilihproduk" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="PILIH PRODUK">
    //                                                     <i data-feather="plus-circle" class="feather-edit"></i>
    //                                                 </a>
    //                                             </div>
    //                                         `;
    //                                     }
    //                                 }
    //                             ],
    //                             drawCallback: function () {
    //                                 feather.replace();
    //                                 initializeTooltip();
    //                             }
    //                         });
    //                     } else {
    //                         // Jika sudah ada datatable, update isinya
    //                         tabelTransksiByKodeTransaksi.clear().rows.add(tableData).draw();
    //                     }


    //                     $("#titlekodetransaksi").text(response.Data[0].kodetransaksi);
    //                     $("#detailpelanggan").val(response.Data[0].pelanggan.nama);
    //                     $("#idpelanggan").val(response.Data[0].pelanggan.id);

    //                     // 🔁 Muat data pembelian_produk ke DataTable kedua
    //                     loadDetailPembelianProduk(response.Data[0].kodetransaksi);

    //                     // loadKondisi
    //                     $.ajax({
    //                         url: "/admin/kondisi/getKondisi", // Endpoint untuk mendapatkan data jabatan
    //                         type: "GET",
    //                         success: function (response) {
    //                             let options
    //                             response.Data.forEach((item) => {
    //                                 options += `<option value="${item.id}">${item.kondisi}</option>`;
    //                             });
    //                             $("#kondisi").html(options); // Masukkan data ke select
    //                         },
    //                         error: function () {
    //                             Swal.fire(
    //                                 "Gagal!",
    //                                 "Tidak dapat mengambil data kondisi.",
    //                                 "error"
    //                             );
    //                         },
    //                     });

    //                 } else {
    //                     // Tangani jika tidak ada data keranjang
    //                     const dangertoastExamplee =
    //                         document.getElementById("dangerToast");
    //                     const toast = new bootstrap.Toast(dangertoastExamplee);
    //                     $(".toast-body").text(response.message);
    //                     toast.show();
    //                 }

    //             } else {
    //                 // Tangani jika success=false
    //                 const dangertoastExamplee =
    //                     document.getElementById("dangerToast");
    //                 const toast = new bootstrap.Toast(dangertoastExamplee);
    //                 $(".toast-body").text(response.message);
    //                 toast.show();
    //             }
    //         },
    //         error: function (xhr) {
    //             const errors = xhr.responseJSON.errors;
    //             if (errors) {
    //                 let errorMessage = "";
    //                 for (let key in errors) {
    //                     errorMessage += `${errors[key][0]}\n`;
    //                 }
    //                 const dangertoastExamplee =
    //                     document.getElementById("dangerToast");
    //                 const toast = new bootstrap.Toast(dangertoastExamplee);
    //                 $(".toast-body").text(errorMessage);
    //                 toast.show();
    //             } else {
    //                 const dangertoastExamplee =
    //                     document.getElementById("dangerToast");
    //                 const toast = new bootstrap.Toast(dangertoastExamplee);
    //                 $(".toast-body").text(response.message);
    //                 toast.show();
    //             }
    //         }
    //     });
    // });
})