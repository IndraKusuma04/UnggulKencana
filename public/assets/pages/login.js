$(document).ready(function () {
    $("#pushLogin").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "/login/pushLogin",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success == true) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: response.message,
                        html: errorList,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    let errorList = "<ul style='text-align: left; padding-left: 20px;'>";

                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorList += `<li><span class="text-danger ms-1">* ${errors[key][0]}</span></li>`;
                        }
                    }

                    errorList += "</ul>";

                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal",
                        html: errorList,
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Tidak dapat memproses permintaan. Silakan coba lagi.",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
        });
    });
})