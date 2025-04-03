<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel dengan Ukuran 24mm × 55mm</title>
    <style>
        @page {
            size: 55mm 24mm;
            margin: 0;
        }

        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        table {
            width: 55mm;
            height: 24mm;
            border-collapse: collapse;
            border: 1px solid black;
            font-size: 4px;
            /* Mengatur ukuran font agar muat */
        }

        td {
            border: 1px solid black;
            padding: 1mm;
            word-wrap: break-word;
            /* Memastikan teks panjang tetap terbagi ke baris berikutnya */
            overflow-wrap: break-word;
            /* Menghindari teks yang keluar dari batas */
        }

        .left {
            width: 60%;
        }

        .right {
            width: 40%;
        }

        .top-right {
            height: 50%;
        }

        .bottom-right {
            height: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .barcode-image {
            max-width: 100%;
            /* Menyesuaikan lebar gambar dengan lebar kolom */
            max-height: 50%;
            /* Menyesuaikan tinggi gambar agar tidak melebihi 50% */
            object-fit: contain;
            /* Menjaga rasio aspek gambar */
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td class="left" rowspan="2"></td>
            <td class="right top-right">ini tulisan nama dan detail produk yang lebih panjang untuk menguji layout</td>
        </tr>
        <tr>
            <td class="right bottom-right">
                <img src="{{ asset('storage/barcode/UCuU5N0LZX.png') }}" alt="barcode" class="barcode-image">
            </td>
        </tr>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>

</body>

</html>
