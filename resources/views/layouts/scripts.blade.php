<script>var hostUrl = "assets/";</script>
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/modals/select-location.js') }}"></script>
<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/js/custom/modals/create-app.js') }}"></script>
<script src="{{ asset('assets/js/custom/modals/upgrade-plan.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            document.querySelectorAll('.confirm-delete-anggota').forEach(button => {
                button.addEventListener('click', function() {
                    const anggotaId = this.dataset.anggotaId;
                    const anggotaNama = this.dataset.anggotaNama;

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: `Anda akan menghapus anggota keluarga "${anggotaNama}". Data ini tidak dapat dikembalikan!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            confirmButton: 'btn btn-danger', 
                            cancelButton: 'btn btn-light'    
                        },
                        buttonsStyling: false 
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-anggota-form-${anggotaId}`).submit();
                        }
                    });
                });
            });

            @if(session('success'))
                Swal.fire({ 
                    text: "{{ session('success') }}",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, mengerti!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    text: "{{ session('error') }}",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, mengerti!",
                    customClass: {
                        confirmButton: "btn btn-danger"
                    }
                });
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.form-select[data-control="select2"]').select2();

            const statusVerifikasiSelect = $('#status_verifikasi'); 
            const alasanPenolakanContainer = $('#alasan_penolakan_container'); 
            const alasanPenolakanInput = $('#alasan_penolakan'); 

            function toggleAlasanPenolakan() {
                const selectedStatus = statusVerifikasiSelect.val();
                console.log('Status Verifikasi dipilih:', selectedStatus); 

                if (selectedStatus === 'ditolak') {
                    alasanPenolakanContainer.show(); 
                    alasanPenolakanInput.prop('required', true); 
                    console.log('Alasan penolakan ditampilkan dan required.'); 
                } else {
                    alasanPenolakanContainer.hide(); 
                    alasanPenolakanInput.prop('required', false); 
                    alasanPenolakanInput.val(''); 
                    console.log('Alasan penolakan disembunyikan dan required dihapus.'); 
                }
            }

            toggleAlasanPenolakan();

            statusVerifikasiSelect.on('change', function() {
                toggleAlasanPenolakan();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault(); 
                var form = this;

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus data penerima bantuan ini?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batalkan",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        form.submit();
                    }
                });
            });

            const table = document.querySelector('#kt_table_penerima_bantuan');
            const searchInput = document.querySelector('[data-kt-penerima-bantuan-table-filter="search"]');

            if (searchInput && table) {
                searchInput.addEventListener('keyup', function (e) {
                    const value = e.target.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(value)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.delete-form-penyaluran', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus data penyaluran bantuan ini?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batalkan",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        form.submit();
                    }
                });
            });

            const table = document.querySelector('#kt_table_penyaluran_bantuan');
            const searchInput = document.querySelector('[data-kt-penyaluran-bantuan-table-filter="search"]');

            if (searchInput && table) {
                searchInput.addEventListener('keyup', function (e) {
                    const value = e.target.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(value)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
        $(document).ready(function() {
            $(document).on('submit', '.delete-form-keluarga', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus data Keluarga ini?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batalkan",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        form.submit();
                    }
                });
            });

            const table = document.querySelector('#kt_table_keluarga');
            const searchInput = document.querySelector('[data-kt-keluarga-table-filter="search"]');

            if (searchInput && table) {
                searchInput.addEventListener('keyup', function (e) {
                    const value = e.target.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(value)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

            const table = document.querySelector('#kt_table_survei');
            const searchInput = document.querySelector('[data-kt-survei-table-filter="search"]');

            if (searchInput && table) {
                searchInput.addEventListener('keyup', function (e) {
                    const value = e.target.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(value)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
    </script>
    @stack('scripts')