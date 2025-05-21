<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <ul>
                        <li>
                            <a href="/admin/dashboard" data-menu-title="POS">
                                <b><i data-feather="grid"></i><span>Dashboard</span></b>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <b><i data-feather="server"></i><span>Produk</span><span class="menu-arrow"></span></b>
                            </a>
                            <ul>
                                <li><a href="/admin/jabatan" data-menu-title="Jabatan"><span>Jabatan</span></a>
                                </li>
                                <li><a href="/admin/role" data-menu-title="Role"></i><span>Role</span></a>
                                </li>
                                <li><a href="/admin/kondisi" data-menu-title="Kondisi"></i><span>Kondisi</span></a>
                                </li>
                                <li><a href="/admin/diskon" data-menu-title="Diskon"></i><span>Diskon</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <b><i data-feather="users"></i><span>User Management</span><span
                                        class="menu-arrow"></span></b>
                            </a>
                            <ul>
                                <li><a href="/admin/pegawai" data-menu-title="Pegawai"><span>Pegawai</span></a></li>
                                <li><a href="/admin/users" data-menu-title="Users"></i><span>Users</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <b><i data-feather="archive"></i><span>Produk</span><span class="menu-arrow"></span></b>
                            </a>
                            <ul>
                                <li>
                                    <a href="/admin/jenisproduk" data-menu-title="Jenis Produk"><span>Jenis
                                            Produk</span></a>
                                </li>
                                <li>
                                    <a href="/admin/produk" data-menu-title="Produk"><span>Produk</span></a>
                                </li>
                                <li>
                                    <a href="/admin/nampan" data-menu-title="Nampan"><span>Nampan</span></a>
                                </li>
                                <li>
                                    <a href="/admin/scanbarcode" data-menu-title="Scan Barcode"><span>Scan
                                            Barcode</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <b><i data-feather="users"></i><span>Pelanggan & Suplier</span><span
                                        class="menu-arrow"></span></b>
                            </a>
                            <ul>
                                <li>
                                    <a href="/admin/pelanggan" data-menu-title="Pelanggan"></i><span>
                                            Pelanggan</span></a>
                                </li>
                                <li>
                                    <a href="/admin/suplier" data-menu-title="Suplier"><span>
                                            Suplier</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <b><i data-feather="dollar-sign"></i><span>Transaksi</span><span
                                        class="menu-arrow"></span></b>
                            </a>
                            <ul>
                                <li><a href="/admin/pos" data-menu-title="POS"><i data-feather="hard-drive"></i><span>
                                            POS</span></a></li>
                                <li><a href="/admin/transaksi" data-menu-title="Transaksi Penjualan"><i
                                            data-feather="file-minus"></i><span>
                                            Penjualan</span></a></li>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i data-feather="layers"></i><span>Pembelian</span><span
                                            class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="/admin/pembelian" data-menu-title="Transaksi Pembelian">Transaksi
                                                Pembelian</a></li>
                                        <li><a href="/admin/pembeliantoko"
                                                data-menu-title="Pembelian Produk Dari Toko">Pembelian Dari Toko</a>
                                        </li>
                                        <li><a href="/admin/pembelianluartoko"
                                                data-menu-title="Pembelian Produk Diluar Toko">Pembelian Diluar
                                                Toko</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li>
                            <a href="/admin/perbaikan" data-menu-title="Perbaikan">
                                <b><i data-feather="slack"></i><span>
                                        Perbaikan</span></b>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <b><i data-feather="book"></i><span>Laporan</span><span class="menu-arrow"></span></b>
                            </a>
                            <ul>
                                <li>
                                    <a href="/admin/report/cetakDaftarProduk" target="_blank"
                                        data-menu-title="Daftar Produk"></i><span>
                                            Daftar Produk</span></a>
                                </li>
                                <li>
                                    <a href="/admin/suplier" data-menu-title="Suplier"><span>
                                            Suplier</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        let path = window.location.pathname;
        let segments = path.split('/');
        let rolePrefix = segments[1]; // "admin" atau "owner"
        let secondSegment = segments[2]; // Misalnya "nampan"

        $('#sidebar a').each(function() {
            let linkHref = $(this).attr('href'); // misal: "/admin/nampan"

            // Cek apakah link mengandung role + segment kedua
            let expectedHref = '/' + rolePrefix + '/' + secondSegment;

            if (linkHref === expectedHref) {
                $(this).addClass('active');
                $(this).parent('li').addClass('active');

                // Buka parent submenu jika ada
                let closestSubmenu = $(this).closest('ul');
                if (closestSubmenu.length > 0 && closestSubmenu.parent('li').length > 0) {
                    closestSubmenu.css('display', 'block');
                    // Tidak tambahkan 'subdrop' jika tidak diperlukan
                }
            }
        });
    });
</script>
