$(document).ready(function () {

    let lastScannedCode = null;

    function onScanSuccess(decodedText, decodedResult) {
        // Cegah proses ulang QR yang sama
        if (decodedText === lastScannedCode) return;
        lastScannedCode = decodedText;

        $("#result").val(decodedText);
        let kodeProduk = decodedText;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: `/admin/scanbarcode/getProdukByScanbarcode/${kodeProduk}`,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN
            },
            success: function (data) {
                if (data.success && data.Data.length > 0) {
                    let produk = data.Data[0];
                    $('#kodeproduk').text(produk.kodeproduk);
                    $('#namaImage').text(produk.nama);
                    $('#jenisproduk').text(produk.jenisproduk.jenis_produk);
                    $('#berat').text(parseFloat(produk.berat).toFixed(1) + " gram");
                    $('#karat').text(produk.karat + " K");
                    $('#lingkar').text(produk.lingkar + " cm");
                    $('#panjang').text(produk.panjang + " cm");
                    $('#harga').text(formatRupiah(produk.harga_jual));
                    // Menentukan status produk
                    if (produk.status == 1) {
                        $('#status').html('<span class="badge badge-success">Ready</span>'); // Menampilkan badge sukses
                    } else {
                        $('#status').html('<span class="badge badge-danger">Non Ready</span>'); // Menampilkan badge danger
                    }
                    $('#keterangan').text(produk.keterangan);

                    // Menampilkan barcode gambar
                    if (produk.image_produk) {
                        $('#barcode').attr('src', `/storage/barcode/${produk.image_produk}`);
                    } else {
                        $('#barcode').attr('src', '/assets/img/notfound.png');
                    }

                    // Menampilkan gambar
                    if (produk.image_produk) {
                        $('#imageProduk').attr('src', `/storage/produk/${produk.image_produk}`);
                    } else {
                        $('#imageProduk').attr('src', '/assets/img/notfound.png');
                    }

                    // Tampilkan toast sukses
                    const toast = new bootstrap.Toast(document.getElementById("successToast"));
                    $(".toast-body").text(data.message);
                    toast.show();

                    // Reset scanner dalam 3 detik supaya bisa scan lagi
                    setTimeout(() => {
                        lastScannedCode = null;
                    }, 3000);
                } else {
                    const toast = new bootstrap.Toast(document.getElementById("dangerToastScan"));
                    toast.show();
                }
            },
            error: function () {
                const toast = new bootstrap.Toast(document.getElementById("dangerToastScan"));
                toast.show();
            }
        });
    }

    // Fungsi untuk format angka menjadi Rupiah
    function formatRupiah(angka) {
        // Membulatkan angka ke bilangan bulat dan menghilangkan angka di belakang koma
        angka = Math.floor(angka);

        // Mengubah angka menjadi format Rupiah dengan titik sebagai pemisah ribuan
        return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function onScanFailure(error) {
        // boleh log jika perlu
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250,
            },
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
