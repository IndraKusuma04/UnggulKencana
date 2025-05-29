$(document).ready(function () {
    const nampan_id = window.location.pathname.split("/").pop(); // Mendapatkan ID produk dari URL

    // Mengambil data produk berdasarkan ID
    $.ajax({
        url: `/admin/stok/nampanstok/getDetailNampanStok/${nampan_id}`,
        method: 'GET',
        success: function (response) {
            const data = response.Data;
            const tbody = $("table tbody");
            tbody.empty();

            let totalKeluarProduk = 0;
            let totalKeluarBerat = 0;

            // Tampilkan data produk
            data.nampan_produk.forEach(item => {
                const kode = item.produk.kodeproduk || '-';
                const nama = item.produk.nama || '-';
                const berat = parseFloat(item.produk.berat).toFixed(3);
                const jenis = item.jenis.charAt(0).toUpperCase() + item.jenis.slice(1);
                const masuk = item.tanggalmasuk ?? '-';
                const keluar = item.tanggalkeluar ?? '-';

                // Hitung total keluar
                if (item.jenis === "keluar") {
                    totalKeluarProduk++;
                    totalKeluarBerat += parseFloat(item.produk.berat);
                }

                const row = `
                    <tr>
                        <td>${kode}</td>
                        <td>${nama}</td>
                        <td>${berat}</td>
                        <td>${jenis}</td>
                        <td>${masuk}</td>
                        <td>${keluar}</td>
                    </tr>
                `;
                tbody.append(row);
            });

            const stokAwalProduk = data.stok_nampan?.stokprodukawal ?? 0;
            const stokAwalBerat = parseFloat(data.stok_nampan?.stokawalberat ?? 0).toFixed(3);
            const stokAkhirProduk = data.stok_nampan?.stokprodukakhir ?? 0;
            const stokAkhirBerat = parseFloat(data.stok_nampan?.stokakhirberat ?? 0).toFixed(3);

            // Tambahkan baris ringkasan
            const ringkasanRow = `
                <tr style="background-color:#f5f5f5; font-weight:bold;">
                    <td colspan="2">Stok Awal</td>
                    <td>${stokAwalBerat} g</td>
                    <td colspan="3">${stokAwalProduk} Produk</td>
                </tr>
                <tr style="background-color:#f5f5f5; font-weight:bold;">
                    <td colspan="2">Stok Akhir</td>
                    <td>${stokAkhirBerat} g</td>
                    <td colspan="3">${stokAkhirProduk} Produk</td>
                </tr>
                <tr style="background-color:#ffe5e5; font-weight:bold;">
                    <td colspan="2">Total Keluar</td>
                    <td>${totalKeluarBerat.toFixed(3)} g</td>
                    <td colspan="3">${totalKeluarProduk} Produk</td>
                </tr>
            `;

            tbody.append(ringkasanRow);
        },
        error: function (xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                let errorMessage = "";
                for (let key in errors) {
                    errorMessage += `${errors[key][0]}\n`;
                }
                const dangertoastExamplee = document.getElementById("dangerToast");
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