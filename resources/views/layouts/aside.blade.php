<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ Request::is('/') ? 'active' : '' }}"
                        href="{{ url('/') }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ Request::is('/barangs') ? 'active' : '' }}" href="{{ url('/barangs') }}">
                        <i class="mdi mdi-cart"></i>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li> -->

               




                @if (in_array(Auth::user()->role, ['super-admin', 'owner', 'kasir']))
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark {{ Request::is('transaksi/*') ? 'active' : '' }}"
                            href="javascript:void(0)">
                            <i class="mdi mdi-cart"></i>
                            <span class="hide-menu">Transaksi</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Request::is('transaksi/*') ? 'in' : '' }}">
                            <li class="sidebar-item">
                                <a href="{{ url('/transaksi/pembelian') }}"
                                    class="sidebar-link {{ Request::is('transaksi/pembelian*') ? 'active' : '' }}">
                                    <i class="mdi mdi-basket"></i>
                                    <span class="hide-menu"> Pembelian Gas </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ url('/transaksi/penjualan') }}"
                                    class="sidebar-link {{ Request::is('transaksi/penjualan*') ? 'active' : '' }}">
                                    <i class="mdi mdi-sale"></i>
                                    <span class="hide-menu"> Penjualan Gas </span>
                                </a>
                            </li>
                            {{-- <li class="sidebar-item">
                                <a href="{{ url('/transaksi/pengembalian') }}"
                                    class="sidebar-link {{ Request::is('transaksi/pengembalian*') ? 'active' : '' }}">
                                    <i class="mdi mdi-backup-restore"></i>
                                    <span class="hide-menu"> Pengembalian Gas </span>
                                </a>
                            </li> --}}
                        </ul>
                    </li>

                    
                @endif

                @if (in_array(Auth::user()->role, ['super-admin', 'owner']))
                    <li class="sidebar-item">
                        <a href="{{ url('/stok') }}"
                            class="sidebar-link waves-effect waves-dark {{ Request::is('stok*') ? 'active' : '' }}">
                            <i class="mdi mdi-gas-cylinder"></i>
                            <span class="hide-menu">Stok Gas</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ url('/mutasi') }}"
                            class="sidebar-link waves-effect waves-dark {{ Request::is('mutasi*') ? 'active' : '' }}">
                            <i class="mdi mdi-transfer"></i>
                            <span class="hide-menu">Mutasi Stok</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark {{ Request::is('master-data/*') ? 'active' : '' }}"
                            href="javascript:void(0)">
                            <i class="mdi mdi-database"></i>
                            <span class="hide-menu">Master Data</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Request::is('master-data/*') ? 'in' : '' }}">
                            <li class="sidebar-item">
                                <a href="{{ url('/master-data/tipe-gas') }}"
                                    class="sidebar-link {{ Request::is('master-data/tipe-gas*') ? 'active' : '' }}">
                                    <i class="mdi mdi-gas-cylinder"></i>
                                    <span class="hide-menu"> Tipe Gas </span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Laporan Menu dengan Submenu -->
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark {{ Request::is('laporan*') ? 'active' : '' }}"
                            href="javascript:void(0)">
                            <i class="mdi mdi-file-document-box"></i>
                            <span class="hide-menu">Laporan</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Request::is('laporan*') ? 'in' : '' }}">

                            <li class="sidebar-item">
                                <a href="{{ route('laporan.harian') }}"
                                    class="sidebar-link {{ Request::is('laporan/harian*') ? 'active' : '' }}">
                                    <i class="mdi mdi-calendar"></i>
                                    <span class="hide-menu"> Laporan Harian </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('laporan.mingguan') }}"
                                    class="sidebar-link {{ Request::is('laporan/mingguan*') ? 'active' : '' }}">
                                    <i class="mdi mdi-calendar"></i>
                                    <span class="hide-menu"> Laporan Mingguan </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('laporan.bulanan') }}"
                                    class="sidebar-link {{ Request::is('laporan/bulanan*') ? 'active' : '' }}">
                                    <i class="mdi mdi-calendar"></i>
                                    <span class="hide-menu"> Laporan Bulanan </span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark {{ Request::is('manajemen-user/*') ? 'active' : '' }}"
                            href="javascript:void(0)">
                            <i class="mdi mdi-account-multiple"></i>
                            <span class="hide-menu">Manajemen User</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Request::is('manajemen-user/*') ? 'in' : '' }}">
                            <li class="sidebar-item">
                                <a href="{{ url('/manajemen-user/karyawan') }}"
                                    class="sidebar-link {{ Request::is('manajemen-user/karyawan*') ? 'active' : '' }}">
                                    <i class="mdi mdi-account"></i>
                                    <span class="hide-menu"> Karyawan </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ url('/manajemen-user/vendor') }}"
                                    class="sidebar-link {{ Request::is('manajemen-user/vendor*') ? 'active' : '' }}">
                                    <i class="mdi mdi-truck"></i>
                                    <span class="hide-menu"> Vendor </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ url('/manajemen-user/pelanggan') }}"
                                    class="sidebar-link {{ Request::is('manajemen-user/pelanggan*') ? 'active' : '' }}">
                                    <i class="mdi mdi-truck"></i>
                                    <span class="hide-menu"> Pelanggan </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
