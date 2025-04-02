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
                    const successtoastExample =
                        document.getElementById("successToast");
                    const toast = new bootstrap.Toast(successtoastExample);
                    $(".toast-body").text(response.message);
                    toast.show();

                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);

                } else {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(response.message);
                    toast.show();
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = "";

                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += `${errors[key][0]}\n`; // Ambil pesan pertama dari setiap error
                        }
                    }

                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(errorMessage.trim());

                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(xhr.responseJSON.message);

                } else {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text("Terjadi kesalahan. Silakan coba lagi!");
                }
            }
        });
    });
})