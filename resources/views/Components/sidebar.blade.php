<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li><a href="/admin/dashboard"><i data-feather="grid"></i><span>Dashboard</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Master</h6>
                    <ul>
                        <li><a href="/admin/jabatan"><i data-feather="circle"></i><span>Jabatan</span></a>
                        </li>
                        <li><a href="/admin/role"><i data-feather="circle"></i><span>Role</span></a>
                        </li>
                        <li><a href="/admin/kondisi"><i data-feather="circle"></i><span>Kondisi</span></a>
                        </li>
                        <li><a href="/admin/diskon"><i data-feather="circle"></i><span>Diskon</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li><a href="/admin/pegawai"><i data-feather="users"></i><span>Pegawai</span></a></li>
                        <li><a href="/admin/users"><i data-feather="user-check"></i><span>Users</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Produk</h6>
                    <ul>
                        <li><a href="/admin/jenisproduk"><i data-feather="tag"></i><span>Jenis Produk</span></a>
                        </li>
                        <li><a href="/admin/produk"><i data-feather="box"></i><span>Produk</span></a>
                        </li>
                        <li><a href="/admin/nampan"><i data-feather="inbox"></i><span>Nampan</span></a>
                        </li>
                        <li><a href="/admin/scanbarcode"><i data-feather="camera"></i><span>Scan Barcode</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Pelanggan & Suplier</h6>
                    <ul>
                        <li><a href="/admin/pelanggan"><i data-feather="user"></i><span> Pelanggan</span></a></li>
                        <li><a href="/admin/suplier"><i data-feather="archive"></i><span> Suplier</span></a></li>
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
