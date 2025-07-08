@extends('templates.master')

@section('content')
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Stock Opname</h6>
                </div>

                @if (Auth::user()->jabatan_id == 3) {{-- pengguna barang --}}

                @else
                <div class="row mt-5 mb-3">
                    <div class="col-12">
                        <form action="{{ url('/stock-opname') }}" method="post">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    @php $tahunSekarang = date('Y'); @endphp
                                    <label for="tanggal" class="form-label">Tahun</label>
                                    <select name="tahun" id="tanggal" class="form-control" required>
                                        @for ($i = 0; $i <= 10; $i++) <option value="{{ $tahunSekarang - $i }}" {{
                                            $tahun==$tahunSekarang - $i ? 'selected' : '' }}>
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
                    <div class="col-12 mt-3">
                        <form action="{{ url('stock-opname/ambil-data') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ date('Y') }}" name="tahun">
                            <button type="submit" class="btn btn-sm btn-warning w-40" onclick="GetData(this,{{ date('Y') }})">Ambil Data Stok Akhir</button>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        {{-- @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif --}}
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="table">
                        <thead>
                            <tr class="text-center align-middle">
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Tahun</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Sisa Stok</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->kode_barang }}</td>
                                <td>{{ $data->tanggal }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->Kategori->nama_kategori }}</td>
                                <td>{{ $data->satuan }}</td>
                                <td>{{ currency($data->harga) }}</td>
                                <td>{{ $data->qty_sisa }}</td>
                                <td>{{ currency($data->jumlah) }}</td>
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

    function GetData(button, tahun) {
        event.preventDefault();

        const form = button.closest('form');

        Swal.fire({
            title: "Apakah anda yakin mengambil data stok tahun " + tahun + " ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, ambil !",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

</script>
@endsection