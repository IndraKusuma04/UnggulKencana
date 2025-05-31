$(document).ready(function () {
    const profile_id = window.location.pathname.split("/").pop(); // Mendapatkan ID produk dari URL

    // Mengambil data produk berdasarkan ID
    $.ajax({
        url: `/admin/profile/getProfile/${profile_id}`, // Sesuaikan dengan route API Laravel
        method: 'GET',
        success: function (response) {
            // Ambil data pertama
            if (response.success && response.Data.length > 0) {
                let data = response.Data[0];

                // Pastikan data pegawai ada
                if (data.pegawai) {

                    // Isi ke input
                    $('#nip').val(data.pegawai.nip);
                    $('#nama').val(data.pegawai.nama);
                    $('#kontak').val(data.pegawai.kontak);
                    $('#email').val(data.email);
                    $('#jabatan').val(data.pegawai.jabatan.jabatan);
                    $('#alamat').val(data.pegawai.alamat);

                    // Update preview gambar dengan background-image
                    if (data.pegawai.image_pegawai) {
                        $('#foto-pegawai').attr('src', `/storage/avatar/${data.pegawai.image_pegawai}`);
                    } else {
                        $('#foto-pegawai').attr('src', '/assets/img/notfound.png');
                    }
                }
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
})