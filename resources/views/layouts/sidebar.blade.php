<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
    <div class="aside-logo flex-column-auto pt-10 pt-lg-20" id="kt_aside_logo">
        <a href="{{ url('/') }}">
            <img alt="Logo" src="{{ asset('assets/media/icons/Kabupaten Kapuas.png') }}" class="h-40px" />
        </a>
    </div>
    <div class="aside-menu flex-column-fluid pt-0 pb-5 py-lg-5" id="kt_aside_menu">
        <div id="kt_aside_menu_wrapper" class="w-100 hover-scroll-overlay-y scroll-ps d-flex" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="0">
            <div id="kt_aside_menu" class="menu menu-column menu-title-gray-600 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-icon-gray-400 menu-arrow-gray-400 fw-bold fs-6" data-kt-menu="true">

                {{-- Dashboard --}}
                <div class="menu-item py-3">
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                                </svg>
                            </span>
                        </span>
                    </a>
                </div>
				
                @can('admin')
                <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
					<span class="menu-link {{ request()->routeIs(['desas.*', 'jenis_bantuans.*']) ? 'active' : '' }}" title="Master Data" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
						<span class="menu-icon">
							<span class="svg-icon svg-icon-2x">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path opacity="0.3" d="M19 22H5C4.47466 22 4.05315 21.657 4.00693 21.2155L2 4.5V2H22V4.5L20 21.2155C19.9538 21.657 19.5323 22 19 22Z" fill="black"/>
									<path d="M17 12H7C6.44772 12 6 12.4477 6 13C6 13.5523 6.44772 14 7 14H17C17.5523 14 18 13.5523 18 13C18 12.4477 17.5523 12 17 12Z" fill="black"/>
									<path d="M17 8H7C6.44772 8 6 8.44772 6 9C6 9.55228 6.44772 10 7 10H17C17.5523 10 18 9.55228 18 9C18 8.44772 17.5523 8 17 8Z" fill="black"/>
								</svg>
							</span>
						</span>
					</span>
					<div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
						<div class="menu-item">
							<div class="menu-content">
								<span class="menu-section fs-5 fw-bolder ps-1 py-1">Master Data</span>
							</div>
						</div>
						<div class="menu-item">
							<a class="menu-link {{ request()->routeIs('desas.*') ? 'active' : '' }}" href="{{ route('desas.index') }}">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Data Desa</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link {{ request()->routeIs('jenis_bantuans.*') ? 'active' : '' }}" href="{{ route('jenis_bantuans.index') }}">
								<span class="menu-bullet">
									<span class="bullet bullet-dot"></span>
								</span>
								<span class="menu-title">Jenis Bantuan</span>
							</a>
						</div>
					</div>
				</div>
                @endcan

                <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                    <span class="menu-link {{ request()->routeIs(['keluargas.*', 'surveis.*', 'penerima-bantuan.*', 'penyaluran-bantuan.*']) ? 'active' : '' }}" title="Manajemen Bantuan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM12 4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4ZM12 7C11.4477 7 11 7.44772 11 8V11H8C7.44772 11 7 11.4477 7 12C7 12.5523 7.44772 13 8 13H11V16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16V13H16C16.5523 13 17 12.5523 17 12C17 11.4477 16.5523 11 16 11H13V8C13 7.44772 12.5523 7 12 7Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Manajemen Bantuan</span>
                            </div>
                        </div>
                        @canany(['admin', 'surveyor', 'kepala'] )
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('keluargas.*') ? 'active' : '' }}" href="{{ route('keluargas.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Data Keluarga</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('surveis.*') ? 'active' : '' }}" href="{{ route('surveis.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Data Survei</span>
                            </a>
                        </div>
                        @endcanany
                        @canany(['admin', 'distributor', 'kepala'])
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('penerima-bantuan.*') ? 'active' : '' }}" href="{{ route('penerima-bantuan.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Penerima Bantuan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('penyaluran-bantuan.*') ? 'active' : '' }}" href="{{ route('penyaluran-bantuan.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Penyaluran Bantuan</span>
                            </a>
                        </div>
                        @endcanany
                    </div>
                </div>

                @can('admin')
                <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                    <span class="menu-link {{ request()->routeIs(['roles.*', 'users.*']) ? 'active' : '' }}" title="Manajemen Pengguna" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M6.28548 15.0861C7.34381 14.7041 8.51785 14.5 9.99999 14.5C11.4821 14.5 12.6562 14.7041 13.7145 15.0861C14.0671 15.2179 14.3721 15.4223 14.6291 15.6895C14.9082 15.9752 15.1324 16.2996 15.3044 16.656C15.4765 17.0124 15.6023 17.3897 15.6811 17.7801C15.7599 18.1705 15.7989 18.5733 15.7971 18.9765C15.7952 19.3798 15.7513 19.7824 15.6668 20.1736C15.5822 20.5649 15.4575 20.9442 15.2952 21.3006C15.1329 21.657 14.9351 21.9892 14.7052 22.2917C14.4753 22.5942 14.2144 22.8656 13.9287 23.1009C13.643 23.3362 13.3353 23.5358 13.0101 23.6938C12.6849 23.8519 12.3444 23.9678 11.9958 24.0384C11.6472 24.1091 11.2915 24.1337 10.9353 24.1118C10.5791 24.09 10.2241 24.0219 9.88001 23.9089C9.53594 23.7959 9.20456 23.639 8.89297 23.4419C8.58138 23.2448 8.29059 23.011 8.02641 22.7441C7.76222 22.4772 7.52558 22.1818 7.32168 21.865C7.11778 21.5482 6.94821 21.2123 6.81734 20.8655C6.68647 20.5187 6.59628 20.1624 6.55018 19.8021C6.50408 19.4418 6.50294 19.0784 6.54668 18.7176C6.59042 18.3568 6.67812 18.0003 6.80665 17.6534C6.93517 17.3065 7.10309 16.9739 7.30644 16.6631C7.50978 16.3523 7.74794 16.0652 8.01633 15.8089C8.28471 15.5526 8.58071 15.3308 8.89885 15.1509C9.217 14.9709 9.55462 14.8344 9.90481 14.7454C10.255 14.6565 10.6157 14.6163 10.9763 14.6267L10.9763 14.6267Z" fill="black" />
                                    <path d="M10.9763 0C10.9763 0 6.00001 5.02361 6.00001 11.2316C6.00001 17.4396 10.9763 22.4632 10.9763 22.4632C10.9763 22.4632 15.9526 17.4396 15.9526 11.2316C15.9526 5.02361 10.9763 0 10.9763 0Z" fill="black" />
                                </svg>
                            </span>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Manajemen Pengguna</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Roles</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Users</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endcan
                @canany(['admin', 'kepala'])
                <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                    <span class="menu-link {{ request()->routeIs(['reports.*']) ? 'active' : '' }}" title="Laporan & Analisis" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2x">
                                {{-- Anda bisa mengganti SVG ini dengan ikon laporan yang sesuai --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="black"/>
                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black"/>
                                    <path d="M18.5 13L15.4 10.3C15.0995 10.0381 14.6989 9.99997 14.4 10H12.5C12.2 10 12 10.2 12 10.5V14.5C12 14.8 12.2 15 12.5 15H17.5C17.8 15 18 14.8 18 14.5V13.5C18 13.2 18.2 13 18.5 13Z" fill="black"/>
                                    <path d="M12 17.5V12.5C12 12.2 12.2 12 12.5 12H17.5C17.8 12 18 12.2 18 12.5V17.5C18 17.8 17.8 18 17.5 18H12.5C12.2 18 12 17.8 12 17.5ZM13 13.5H16.5C16.8 13.5 17 13.7 17 14V16.5C17 16.8 16.8 17 16.5 17H13.5C13.2 17 13 16.8 13 16.5V14C13 13.7 13.2 13.5 13.5 13.5Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Laporan & Analisis</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.keluarga.index') ? 'active' : '' }}" href="{{ route('reports.keluarga.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Data Keluarga</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.survey.index') ? 'active' : '' }}" href="{{ route('reports.survey.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Hasil Survey</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.penerima_bantuan.index') ? 'active' : '' }}" href="{{ route('reports.penerima_bantuan.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Penerima Bantuan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.penyaluran_bantuan.index') ? 'active' : '' }}" href="{{ route('reports.penyaluran_bantuan.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Penyaluran Bantuan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.total_penyaluran_per_desa.index') ? 'active' : '' }}" href="{{ route('reports.total_penyaluran_per_desa.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Total Penyaluran per Desa</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.jadwal_penyaluran.index') ? 'active' : '' }}" href="{{ route('reports.jadwal_penyaluran.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Jadwal Penyaluran</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.detail_data_keluarga.index') ? 'active' : '' }}" href="{{ route('reports.detail_data_keluarga.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Laporan Detail Data Keluarga</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('reports.analisis_nominal.index') ? 'active' : '' }}" href="{{ route('reports.analisis_nominal.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Analisis Nominal Bantuan</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endcanany
            </div>
        </div>
    </div>
    <div class="aside-footer flex-column-auto pb-5 pb-lg-10" id="kt_aside_footer">
        <div class="d-flex flex-center w-100 scroll-px" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="click" title="Logout">
            <button type="button" class="btn btn-custom" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="top-start" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="svg-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.3" width="12" height="2" rx="1" transform="matrix(-1 0 0 1 15.5 11)" fill="black" />
                        <path d="M13.6313 11.6927L11.8756 10.2297C11.4054 9.83785 11.3732 9.12683 11.806 8.69401C12.1957 8.3043 12.8216 8.28591 13.2336 8.65206L16.1592 11.2526C16.6067 11.6504 16.6067 12.3496 16.1592 12.7474L13.2336 15.3479C12.8216 15.7141 12.1957 15.6957 11.806 15.306C11.3732 14.8732 11.4054 14.1621 11.8756 13.7703L13.6313 12.3073C13.8232 12.1474 13.8232 11.8526 13.6313 11.6927Z" fill="black" />
                        <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>