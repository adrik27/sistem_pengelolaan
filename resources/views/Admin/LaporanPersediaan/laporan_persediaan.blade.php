@extends('templates.master')

@section('content')
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Laporan Persediaan</h6>
                </div>

                @if (Auth::user()->jabatan_id == 3) {{-- pengguna barang --}}

                @else
                <div class="row mt-5 mb-3">
                    <div class="col-12">
                        <form action="{{ url('/laporan-persediaan') }}" method="post">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="deparment" class="form-label">Departments</label>
                                    <select name="department_id" id="deparment" class="form-control">
                                        @foreach ($departments as $item)
                                        <option value="{{ $item->id }}" {{ $req_departments==$item->id ? 'selected' : ''
                                            }}>
                                            {{ ucwords($item->nama) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    @php $tahunSekarang = date('Y'); @endphp
                                    <label for="from" class="form-label">Range From</label>
                                    <select name="tahun_from" id="from" class="form-control" required>
                                        @for ($i = 0; $i <= 10; $i++) <option value="{{ $tahunSekarang - $i }}" {{
                                            $tahun_from==$tahunSekarang - $i ? 'selected' : '' }}>
                                            {{ $tahunSekarang - $i }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="to" class="form-label">Range To</label>
                                    <select name="tahun_to" id="to" class="form-control" required>
                                        @for ($i = 0; $i <= 10; $i++) <option value="{{ $tahunSekarang - $i }}" {{
                                            $tahun_to==$tahunSekarang - $i ? 'selected' : '' }}>
                                            {{ $tahunSekarang - $i }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-success w-100">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

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
                            @foreach($laporan as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row['department_id'] }}</td>
                                <td>{{ number_format($row['saldo_awal'], 0, ',', '.') }}</td>

                                <td>{{ optional($row['tgl_masuk'])->format('d M Y') }}</td>
                                <td>{{ $row['kode_masuk'] }}</td>
                                <td>{{ $row['nama_masuk'] }}</td>
                                <td>{{ number_format($row['harga_masuk'], 0, ',', '.') }}</td>
                                <td>{{ $row['qty_masuk'] }}</td>
                                <td><strong>{{ number_format($row['saldo_masuk'], 0, ',', '.') }}</strong></td>

                                <td>{{ optional($row['tgl_keluar'])->format('d M Y') }}</td>
                                <td>{{ $row['kode_keluar'] }}</td>
                                <td>{{ $row['nama_keluar'] }}</td>
                                <td>{{ number_format($row['harga_keluar'], 0, ',', '.') }}</td>
                                <td>{{ $row['qty_keluar'] }}</td>
                                <td><strong>{{ number_format($row['saldo_keluar'], 0, ',', '.') }}</strong></td>

                                <td>{{ $row['sisa_stok'] }}</td>
                                <td><strong>{{ number_format($row['sisa_saldo'], 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- sweet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>
@endsection