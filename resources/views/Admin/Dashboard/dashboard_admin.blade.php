@extends('templates.master')

@section('content')
<style>
    :fullscreen .dashboard-container,
    :-webkit-full-screen .dashboard-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 85%;
        height: 100vh;
        overflow: auto;
        padding: 2rem;
    }

    :fullscreen .dashboard-container>.row,
    :-webkit-full-screen .dashboard-container>.row {
        width: 100%;
        /* max-width: 1200px; */
    }
</style>

<div class="dashboard-container" id="fullscreen-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Hello, Welcome to Dashboard {{ ucwords(Auth::user()->Department->nama) }}, Tahun {{
                date('Y') }}</h4>
        </div>
        <div class="">
            <button id="fullscreen-toggle" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                data-bs-custom-class="custom-tooltip" data-bs-title="Full Screen">
                <i class="link-icon" data-feather="maximize-2" style="display: block"></i>
                {{-- Full Screen --}}
            </button>
        </div>

    </div>

    @if (Auth::user()->jabatan_id == 3) {{-- jika user(pengguna barang) = jabatan 3 --}}
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center">
                                <h6 class="card-title mb-0">Limit Saldo</h6>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 col-md-12 col-xl-12 text-center">
                                    <h4 class="mb-2" id="user-limit-display">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center">
                                <h6 class="card-title mb-0">Penambahan</h6>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 col-md-12 col-xl-12 text-center">
                                    <h4 class="mb-2" id="user-penambahan-display">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center">
                                <h6 class="card-title mb-0">Pengurangan</h6>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 col-md-12 col-xl-12 text-center">
                                    <h4 class="mb-2" id="user-pengurangan-display">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @else {{-- jika admin(pengurus barang / super admin) = jabatan 1 dan 2 --}}

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                @foreach ($departments as $depart)
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-center align-items-center">
                                <h6 class="card-title mb-0">
                                    {{ strtoupper($depart->nama) }} ({{ date('Y') }})
                                </h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <div class="row ">
                                        <div class="col">
                                            <h6>Penambahan :</h6>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-2" id="penambahan-display-{{ $depart->id }}">0</h6>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col">
                                            <h6>Pengeluaran :</h6>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-2" id="pengeluaran-display-{{ $depart->id }}">0</h6>
                                        </div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="row ">
                                        <div class="col">
                                            <h6>Saldo Awal :</h6>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-2" id="total-saldo-display-{{ $depart->id }}">0</h6>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col">
                                            <h6>Saldo Terpakai :</h6>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-2" id="total-saldo-terpakai-display-{{ $depart->id }}">0</h6>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col">
                                            <h6>Sisa Saldo :</h6>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-2" id="sisa-saldo-display-{{ $depart->id }}">0</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div> <!-- row -->

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Daftar Laporan Saldo Terpakai (Tahun)</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="table-dashboard">
                            <thead>
                                <tr>
                                    <th class="pt-0">No</th>
                                    <th class="pt-0">Department</th>
                                    <th class="pt-0">Tahun</th>
                                    <th class="pt-0">Saldo Terpakai</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->

    @endif
</div>
@endsection

{{-- script fullscreen --}}

@section('js')


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('fullscreen-toggle');
        const header = document.querySelector('.navbar'); // sesuaikan jika berbeda
        const sidebar = document.querySelector('.sidebar'); // sesuaikan jika berbeda
        let isFullscreen = false;

        toggleBtn.addEventListener('click', () => {
            const docElm = document.documentElement;

            if (!isFullscreen) {
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                } else if (docElm.webkitRequestFullscreen) {
                    docElm.webkitRequestFullscreen();
                } else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }

            isFullscreen = !isFullscreen;
        });

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                // keluar dari fullscreen
                if (header) header.style.display = '';
                if (sidebar) sidebar.style.display = '';
                // if (toggleBtn) toggleBtn.textContent = 'Full Screen';
                if (toggleBtn) toggleBtn.style.display = 'block';

                isFullscreen = false;
            } else {
                // masuk fullscreen
                if (header) header.style.display = 'none';
                if (sidebar) sidebar.style.display = 'none';
                // if (toggleBtn) toggleBtn.textContent = 'Minimize Screen';
                if (toggleBtn) toggleBtn.style.display = 'none';
                isFullscreen = true;
            }
        });
    });

    $(document).ready(function() {
        $('#table-dashboard').DataTable({
            paging: false,
            info: false,            // nonaktifkan "Showing x of y entries"
            searching: false,        // tetap bisa melakukan pencarian (opsional)
            ordering: false,         // tetap bisa melakukan sorting (opsional)
            lengthChange: false     // nonaktifkan pilihan jumlah tampil data
        });

        fetchSaldoAwal();

        // Setiap 2,5 menit (150000 ms)
        setInterval(fetchSaldoAwal, 150000);
    });
    
    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(angka);
    }

    @if (Auth::user()->jabatan_id == 3)
        function fetchSaldoAwal() {
            $.ajax({
                url: '/get-data',
                type: 'GET',
                dataTypePengeluaran: 'json',
                success: function(response) {
                    // baris card
                    $('#user-limit-display').text(formatRupiah(response.LimitSaldo));
                    $('#user-penambahan-display').text(formatRupiah(response.Penambahan));
                    $('#user-pengurangan-display').text(formatRupiah(response.Pengurangan));
                },
                error: function(xhr, status, error) {
                    console.error('Gagal memuat saldo:', error);
                }
            });
        }
    @else
        function fetchSaldoAwal() {
            const departments = {!! $departments !!}
            
            $.ajax({
                url: '/get-data',
                type: 'GET',
                dataTypePengeluaran: 'json',
                success: function(response) {
                    const saldoTable = response.SaldoAwalTable;
    
                    departments.forEach(item => {
                        // baris card
                        $('#penambahan-display-' + item.id).text(formatRupiah(response.penambahan[item.id]));
                        $('#pengeluaran-display-' + item.id).text(formatRupiah(response.pengeluaran[item.id]));
                        $('#total-saldo-display-' + item.id).text(formatRupiah(response.saldoawal[item.id]));
                        $('#total-saldo-terpakai-display-' + item.id).text(formatRupiah(response.saldoterpakai[item.id]));
                        $('#sisa-saldo-display-' + item.id).text(formatRupiah(response.sisasaldo[item.id]));
                    });
    
                    let rows = '';
    
                    saldoTable.forEach((item, index) => {
                        const namaDepartemen = item.department?.nama || 'N/A';
                        const saldoTerpakai = item.saldo_digunakan || 0;
    
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${namaDepartemen}</td>
                                <td>${item.tahun}</td>
                                <td>${formatRupiah(saldoTerpakai)}</td>
                            </tr>
                        `;
                    });
    
                    $('#table-dashboard tbody').html(rows);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal memuat saldo:', error);
                }
            });
        }
    @endif
</script>
@endsection