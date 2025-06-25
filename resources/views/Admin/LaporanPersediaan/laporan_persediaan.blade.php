@extends('templates.master')

@section('content')
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Transaksi Pengeluaran</h6>
                </div>


                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="table">
                        <thead>
                            <tr class="text-center align-middle">
                                <th rowspan="2">No</th>
                                <th rowspan="2">Department</th>
                                <th rowspan="2">Saldo Awal</th>
                                <th colspan="6">Penerimaan</th>
                                <th colspan="6">Pengeluaran</th>
                                <th rowspan="2">Sisa Stok Persediaan</th>
                                <th rowspan="2">Sisa Saldo</th>
                            </tr>
                            <tr class="text-center align-middle">
                                {{-- penerimaan --}}
                                <th>Tanggal</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah Barang</th>
                                <th>Saldo Penerimaan</th>
                                {{-- pengeluaran --}}
                                <th>Tanggal</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah Barang</th>
                                <th>Saldo Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td> {{-- No --}}
                                <td></td> {{-- Department --}}
                                <td></td> {{-- saldo awal --}}
                                {{-- penerimaan --}}
                                <td></td> {{-- Tanggal --}}
                                <td></td> {{-- kode --}}
                                <td></td> {{-- nama --}}
                                <td></td> {{-- harga --}}
                                <td></td> {{-- jumlah --}}
                                <td></td> {{-- saldo --}}
                                {{-- pengeluaran --}}
                                <td></td> {{-- tanggal --}}
                                <td></td> {{-- kode --}}
                                <td></td> {{-- nama --}}
                                <td></td> {{-- harga --}}
                                <td></td> {{-- jumlah --}}
                                <td></td> {{-- saldo --}}
                                <td></td> {{-- sisa stok --}}
                                <td></td> {{-- sisa saldo --}}
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection