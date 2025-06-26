@extends('templates.master')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Hello, Welcome to Dashboard {{ ucwords(Auth::user()->Department->nama) }}, Tahun {{
            date('Y') }}</h4>
    </div>
    {{-- <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
            <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle=""><i
                    data-feather="calendar" class="text-primary"></i></span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date"
                data-input="">
        </div>
        <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="printer"></i>
            Print
        </button>
        <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="download-cloud"></i>
            Download Report
        </button>
    </div> --}}
</div>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <h6 class="card-title mb-0">Saldo Awal</h6>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 col-md-12 col-xl-12 text-center">
                                <h4 class="mb-2" id="saldo-awal-display">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <h6 class="card-title mb-0">Total Saldo</h6>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 col-md-12 col-xl-12 text-center">
                                <h4 class="mb-2">Rp 3.000.000</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <h6 class="card-title mb-0">Sisa Saldo</h6>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 col-md-12 col-xl-12 text-center">
                                <h4 class="mb-2">Rp 2.000.000</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <h6 class="card-title mb-0">Total Saldo Terpakai</h6>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 col-md-12 col-xl-12 text-center">
                                <h4 class="mb-2">Rp 1.000.000</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- row -->

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Daftar Laporan Persediaan (Tahun)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table-dashboard">
                        <thead>
                            <tr>
                                <th class="pt-0">No</th>
                                <th class="pt-0">Tahun</th>
                                <th class="pt-0">Saldo Terpakai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2025</td>
                                <td>Rp 2.000.000</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2024</td>
                                <td>Rp 2.000.000</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>2023</td>
                                <td>Rp 2.000.000</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>2022</td>
                                <td>Rp 2.000.000</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>2021</td>
                                <td>Rp 2.000.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> <!-- row -->

@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#table-dashboard').DataTable();

        fetchSaldoAwal();

        // Setiap 5 menit (300000 ms)
        setInterval(fetchSaldoAwal, 300000);
    });
    
    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(angka);
    }

    function fetchSaldoAwal() {
        $.ajax({
            url: '/get-saldo-awal',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#saldo-awal-display').text(formatRupiah(response.data));
            },
            error: function(xhr, status, error) {
                console.error('Gagal memuat saldo:', error);
            }
        });
    }
</script>
@endsection