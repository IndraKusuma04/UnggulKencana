$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tablePegawai) {
            tablePegawai.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Pegawai Berhasil Direfresh");
        toast.show();
    });

    //load data pegawai
    function getPegawai() {
        // Datatable
        if ($('#pegawaiTable').length > 0) {
            tablePegawai = $('#pegawaiTable').DataTable({
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
                    url: `/admin/pegawai/getPegawai`, // Ganti dengan URL endpoint server Anda
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
                        data: "nip",
                    },
                    {
                        data: "image_pegawai", // Nama field dari API
                        render: function (data, type, row) {
                            let timestamp = new Date().getTime(); // Gunakan timestamp untuk cache busting
                            return `
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);" class="avatar avatar-sm me-2">
                                    <img src="/storage/avatar/${data}?t=${timestamp}" alt="user">
                                </a>
                                <a href="javascript:void(0);">${row.nama}</a>
                            </div>
                        `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "jabatan.jabatan",
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
                        className: "action-table-data",
                        render: function (data, type, row, meta) {
                            return `
                            <div class="edit-delete-action">
                                <a class="me-2 p-2 btn-edit" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit Data">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="confirm-text p-2" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Hapus Data">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>
                        `;
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

    getPegawai();

    function uploadImage(inputId, previewId) {
        const inputFile = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewId);

        inputFile.addEventListener("change", () => {
            const file = inputFile.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                previewContainer.innerHTML = "";
                const img = document.createElement("img");
                img.src = reader.result;
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    }

    function editUploadImage(inputId, previewId) {
        const inputFile = document.getElementById(inputId);
        const previewContainer = $("#" + previewId); // Gunakan jQuery

        inputFile.addEventListener("change", () => {
            const file = inputFile.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                // Hapus background image
                previewContainer.css("background-image", "");

                // Masukkan gambar ke dalam preview div
                previewContainer.html(""); // Hapus isi sebelumnya
                const img = document.createElement("img");
                img.src = reader.result;
                img.style.width = "100%";
                img.style.height = "100%";
                img.style.objectFit = "cover"; // Agar rapi dalam div
                previewContainer.append(img);
            };

            reader.readAsDataURL(file);
        });
    }


    // Fungsi untuk memuat data jabatan
    function loadJabatan() {
        $.ajax({
            url: "/admin/jabatan/getJabatan", // Endpoint untuk mendapatkan data jabatan
            type: "GET",
            success: function (response) {
                let options
                response.Data.forEach((item) => {
                    options += `<option value="${item.id}">${item.jabatan}</option>`;
                });
                $("#jabatan").html(options); // Masukkan data ke select
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Tidak dapat mengambil data jabatan.",
                    "error"
                );
            },
        });
    }

    //ketika button tambah di tekan
    $("#btnTambahPegawai").on("click", function () {
        // Panggil fungsi untuk setiap input gambar
        uploadImage("imagePegawai", "imagePegawaiPreview");
        loadJabatan();
        $("#mdTambahPegawai").modal("show");
    });

    // Ketika modal ditutup, reset semua field
    $("#mdTambahPegawai").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formTambahPegawai")[0].reset();
        $("#imagePegawaiPreview").empty();
    });

    // Fungsi untuk menangani submit form pegawai
    $("#formTambahPegawai").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default
        // Ambil elemen input file
        const fileInput = $("#imagePegawai")[0];
        const file = fileInput.files[0];

        // Buat objek FormData
        const formData = new FormData(this);
        formData.delete("imagePegawai"); // Hapus field 'image' bawaan form
        formData.append("imagePegawai", file); // Tambahkan file baru
        $.ajax({
            url: "/admin/pegawai/storePegawai", // Endpoint Laravel untuk menyimpan pegawai
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {
                const successtoastExample =
                    document.getElementById("successToast");
                const toast = new bootstrap.Toast(successtoastExample);
                $(".toast-body").text(response.message);
                toast.show();
                $("#mdTambahPegawai").modal("hide"); // Tutup modal
                tablePegawai.ajax.reload(null, false);
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
                    toast.show();

                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(errorMessage.trim());
                    toast.show();

                } else {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(errorMessage.trim());
                    toast.show();
                }
            }
        });
    });

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit", function () {
        const pegawaiID = $(this).data("id");

        $.ajax({
            url: `/admin/pegawai/getPegawaiByID/${pegawaiID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                editUploadImage("editimagePegawai", "editimagePegawaiPreview");
                // Ambil data pertama
                let data = response.Data[0];

                // Isi modal dengan data pegawai
                $("#editnip").val(data.nip);
                $("#editnama").val(data.nama);
                $("#editalamat").val(data.alamat);
                $("#editkontak").val(data.kontak);
                // Muat opsi jabatan
                $.ajax({
                    url: "/admin/jabatan/getJabatan",
                    type: "GET",
                    success: function (jabatanResponse) {
                        let options
                        jabatanResponse.Data.forEach((item) => {
                            const selected =
                                item.id === data.jabatan_id
                                    ? "selected"
                                    : "";
                            options += `<option value="${item.id}" ${selected}>${item.jabatan}</option>`;
                        });
                        $("#editjabatan").html(options);
                    },
                });

                // Update preview gambar dengan background-image
                let imageSrc = data.image_pegawai
                    ? `/storage/avatar/${data.image_pegawai}`
                    : `/assets/img/notfound.png`;

                $("#editimagePegawaiPreview").css({
                    "background-image": `url('${imageSrc}')`,
                    "background-size": "cover",
                    "background-position": "center",
                });

                // Tampilkan modal edit
                $("#mdEditPegawai").modal("show");
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Tidak dapat mengambil data jabatan.",
                    "error"
                );
            },
        });
    });

    // Ketika modal ditutup, reset semua field
    $("#mdEditPegawai").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formEditPegawai")[0].reset();
        $("#editimagePegawaiPreview").empty();
    });

    //kirim data ke server <i class=""></i>
    $("#formEditPegawai").on("submit", function (event) {
        event.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const nipPegawai = formData.get("nip"); // Mengambil nilai input dengan name="id"

        $.ajax({
            url: `/admin/pegawai/updatePegawai/${nipPegawai}`, // Endpoint Laravel untuk menyimpan pegawai
            type: "POST",
            data: formData,
            processData: false, // Agar data tidak diubah menjadi string
            contentType: false, // Agar header Content-Type otomatis disesuaikan
            success: function (response) {
                const successtoastExample =
                    document.getElementById("successToast");
                const toast = new bootstrap.Toast(successtoastExample);
                $(".toast-body").text(response.message);
                toast.show();

                $("#mdEditPegawai").modal("hide"); // Tutup modal
                tablePegawai.ajax.reload(null, false);
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
                    toast.show();

                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text(errorMessage.trim());
                    toast.show();

                } else {
                    const dangertoastExamplee =
                        document.getElementById("dangerToast");
                    const toast = new bootstrap.Toast(dangertoastExamplee);
                    $(".toast-body").text("Terjadi kesalahan silahkan coba lagi");
                    toast.show();
                }
            }
        });
    });

    // ketika button hapus di tekan
    $(document).on("click", ".confirm-text", function () {
        const deleteID = $(this).data("id");

            // SweetAlert2 untuk konfirmasi
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim permintaan hapus (gunakan itemId)
                    fetch(`/admin/pegawai/deletePegawai/${deleteID}`, {
                        method: "DELETE",
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
                                    "Dihapus!",
                                    "Data berhasil dihapus.",
                                    "success"
                                );
                                tablePegawai.ajax.reload(null, false); // Reload data dari server
                            } else {
                                Swal.fire(
                                    "Gagal!",
                                    "Terjadi kesalahan saat menghapus data.",
                                    "error"
                                );
                            }
                        })
                        .catch((error) => {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan dalam penghapusan data.",
                                "error"
                            );
                        });
                } else {
                    // Jika batal, beri tahu pengguna
                    Swal.fire("Dibatalkan", "Data tidak dihapus.", "info");
                }
            });
    });
})