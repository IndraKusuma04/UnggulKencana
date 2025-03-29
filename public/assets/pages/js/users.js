$(document).ready(function () {
    // Inisialisasi tooltip Bootstrap
    function initializeTooltip() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    //function refresh
    $(document).on("click", "#refreshButton", function () {
        if (tableUsers) {
            tableUsers.ajax.reload(null, false); // Reload data dari server
        }
        const successtoastExample = document.getElementById("successToast");
        const toast = new bootstrap.Toast(successtoastExample);
        $(".toast-body").text("Data Users Berhasil Direfresh");
        toast.show();
    });

    //load data jabatan
    function getUsers() {
        // Datatable
        if ($('#usersTable').length > 0) {
            tableUsers = $('#usersTable').DataTable({
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
                    url: `/admin/users/getUsers`, // Ganti dengan URL endpoint server Anda
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
                        data: "pegawai.nama",
                    },
                    {
                        data: "role.role",
                        render: function (data, type, row) {
                            if (!data || data === null) {
                                return `<span class="badge bg-secondary fw-medium fs-10">Role Belum Di Pilih</span>`;
                            } else {
                                return data; // Menampilkan email jika tidak null
                            }
                        }
                    },
                    {
                        data: "email",
                        render: function (data, type, row) {
                            if (!data || data === null) {
                                return `<span class="badge bg-secondary fw-medium fs-10">Email Belum Di Input</span>`;
                            } else {
                                return data; // Menampilkan email jika tidak null
                            }
                        }
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

    getUsers();

    //ketika button edit di tekan
    $(document).on("click", ".btn-edit", function () {
        const usersID = $(this).data("id");

        $.ajax({
            url: `/admin/users/getUsersByID/${usersID}`, // Endpoint untuk mendapatkan data pegawai
            type: "GET",
            success: function (response) {
                // Ambil data pertama
                let data = response.Data[0];

                // Isi modal dengan data pegawai
                $("#editid").val(data.id);
                $("#editnama").val(data.pegawai.nama);
                $("#editemail").val(data.email);

                // Muat opsi role
                $.ajax({
                    url: "/admin/role/getRole",
                    type: "GET",
                    success: function (roleResponse) {
                        let options
                        roleResponse.Data.forEach((item) => {
                            const selected =
                                item.id === data.role_id
                                    ? "selected"
                                    : "";
                            options += `<option value="${item.id}" ${selected}>${item.role}</option>`;
                        });
                        $("#editrole").html(options);
                    },
                });

                // Tampilkan modal edit
                $("#mdEditUsers").modal("show");
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

    // Ketika modal ditutup, reset semua field
    $("#mdEditUsers").on("hidden.bs.modal", function () {
        // Reset form input (termasuk gambar dan status)
        $("#formEditUsers")[0].reset();
    });

    // // Kirim data ke server saat form disubmit
    $(document).on("submit", "#formEditUsers", function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        // Buat objek FormData
        const formData = new FormData(this);
        // Ambil ID dari form
        const idUsers = formData.get("id"); // Mengambil nilai input dengan name="id"

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/admin/users/updateUsers/${idUsers}`, // URL untuk mengupdate data pegawai
            type: "POST", // Gunakan metode POST (atau PATCH jika route mendukung)
            data: formData, // Gunakan FormData
            processData: false, // Jangan proses FormData sebagai query string
            contentType: false, // Jangan set Content-Type secara manual
            success: function (response) {
                // Tampilkan toast sukses
                const successtoastExample =
                    document.getElementById("successToast");
                const toast = new bootstrap.Toast(successtoastExample);
                $(".toast-body").text(response.message);
                toast.show();
                $("#mdEditUsers").modal("hide"); // Tutup modal
                tableUsers.ajax.reload(null, false); // Reload data dari server
            },
            error: function (xhr) {
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
            },
        });
    });
})