$(document).ready(function () {
    const produkID = window.location.pathname.split("/").pop(); // Mendapatkan ID produk dari URL

    // Mengambil data produk berdasarkan ID
    $.ajax({
        url: `/admin/produk/getProdukByID/${produkID}`, // Sesuaikan dengan route API Laravel
        method: 'GET',
        success: function (response) {
            // Ambil data pertama
            let data = response.Data[0];
            // Menampilkan data produk pada elemen yang sesuai
            $('#kodeproduk').text(data.kodeproduk);
            $('#namaImage').text(data.nama);
            $('#jenisproduk').text(data.jenisproduk.jenis_produk);
            $('#berat').text(data.berat);
            $('#karat').text(data.karat);
            $('#lingkar').text(data.lingkar);
            $('#panjang').text(data.panjang);
            $('#harga').text(formatRupiah(data.harga_jual));
            // Menentukan status produk
            if (data.status == 1) {
                $('#status').html('<span class="badge badge-success">Ready</span>'); // Menampilkan badge sukses
            } else {
                $('#status').html('<span class="badge badge-danger">Non Ready</span>'); // Menampilkan badge danger
            }
            $('#keterangan').text(data.keterangan);

            // Menampilkan barcode gambar
            if (data.image_produk) {
                $('#barcode').attr('src', `/storage/barcode/${data.image_produk}`);
            } else {
                $('#barcode').attr('src', '/assets/img/notfound.png');
            }

            // Menampilkan gambar
            if (data.image_produk) {
                $('#imageProduk').attr('src', `/storage/produk/${data.image_produk}`);
            } else {
                $('#imageProduk').attr('src', '/assets/img/notfound.png');
            }
        },
        error: function (xhr, status, error) {
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
        }
    });

    // Fungsi untuk format angka menjadi Rupiah
    function formatRupiah(angka) {
        // Membulatkan angka ke bilangan bulat dan menghilangkan angka di belakang koma
        angka = Math.floor(angka);

        // Mengubah angka menjadi format Rupiah dengan titik sebagai pemisah ribuan
        return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
})