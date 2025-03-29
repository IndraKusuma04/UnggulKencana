@extends('Layouts.app')
@section('title', 'Pegawai')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>DAFTAR PEGAWAI</h4>
                    </div>
                </div>
                <ul class="table-top-head">
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" id="refreshButton" data-bs-placement="top" title="Refresh"><i
                                data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a class="btn btn-added" id="btnTambahPegawai"><i data-feather="plus-circle" class="me-2"></i>TAMBAH
                        ROLE</a>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive product-list">
                        <table id="pegawaiTable" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>NIP</th>
                                    <th>NAMA</th>
                                    <th>JABATAN</th>
                                    <th>STATUS</th>
                                    <th class="no-sort">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- md Tambah Pegawai -->
    <div class="modal fade" id="mdTambahPegawai">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>TAMBAH PEGAWAI</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTambahPegawai" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">NIP<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="nip" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">NAMA<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="nama" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ALAMAT<span class="text-danger ms-1">*</span></label>
                            <textarea name="alamat" class="form-control" cols="30" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KONTAK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="kontak" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JABATAN<span class="text-danger ms-1">*</span></label>
                            <select class="select" id="jabatan" name="jabatan">
                            </select>
                        </div>
                        <div class="add-choosen">
                            <div class="mb-3">
                                <label class="form-label">AVATAR</label>
                                <div class="image-upload ">
                                    <input type="file" name="imagePegawai" id="imagePegawai">
                                    <div class="image-uploads">
                                        <i data-feather="upload" class="plus-down-add me-0"></i>
                                        <h4>UPLOAD AVATAR</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-img"
                                style="width: 150px; height: 150px; overflow: hidden; border-radius: 8px;">
                                <div id="imagePegawaiPreview" alt="previewImage"
                                    style="width: 150px; height: 150px; display: block; overflow: hidden;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- md Tambah Pegawai -->
    <div class="modal fade" id="mdEditPegawai">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>EDIT PEGAWAI</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditPegawai" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">NIP<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="nip" id="editnip" class="form-control" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">NAMA<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="nama" id="editnama" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ALAMAT<span class="text-danger ms-1">*</span></label>
                            <textarea name="alamat" id="editalamat" class="form-control" cols="30" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KONTAK<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="kontak" id="editkontak" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">JABATAN<span class="text-danger ms-1">*</span></label>
                            <select class="select" id="editjabatan" name="jabatan">
                            </select>
                        </div>
                        <div class="add-choosen">
                            <div class="mb-3">
                                <label class="form-label">AVATAR</label>
                                <div class="image-upload ">
                                    <input type="file" name="imagePegawai" id="editimagePegawai">
                                    <div class="image-uploads">
                                        <i data-feather="upload" class="plus-down-add me-0"></i>
                                        <h4>UPLOAD AVATAR</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-img"
                                style="width: 150px; height: 150px; overflow: hidden; border-radius: 8px;">
                                <div id="editimagePegawaiPreview" alt="previewImage"
                                    style="width: 150px; height: 150px; display: block; overflow: hidden;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /Add Jenis -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/pages/js/pegawai.js"></script>
@endsection
