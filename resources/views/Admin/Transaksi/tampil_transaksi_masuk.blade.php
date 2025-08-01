@extends('templates.master')

@section('content')

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Riwayat Transaksi Penerimaan</h6>
                </div>

                {{-- Menampilkan notifikasi sukses atau error --}}
                <div class="row">
                    <div class="col-12 mt-2">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Form Filter Data --}}
                <div class="row my-4">
                    <div class="col-12">
                        {{-- Menggunakan method GET untuk filter --}}
                        <form action="{{ url('/penerimaan') }}" method="GET">
                            <div class="row d-flex align-items-center">
                                <div class="col-md-2">
                                    <strong>Filter Data:</strong>
                                </div>
                                <div class="col-md-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-select form-select-sm">
                                        {{-- Menggunakan request() untuk mendapatkan nilai filter aktif --}}
                                        <option value="1" @selected(request('bulan') == 1)>Januari</option>
                                        <option value="2" @selected(request('bulan') == 2)>Februari</option>
                                        <option value="3" @selected(request('bulan') == 3)>Maret</option>
                                        <option value="4" @selected(request('bulan') == 4)>April</option>
                                        <option value="5" @selected(request('bulan') == 5)>Mei</option>
                                        <option value="6" @selected(request('bulan') == 6)>Juni</option>
                                        <option value="7" @selected(request('bulan', date('m')) == 7)>Juli</option>
                                        <option value="8" @selected(request('bulan') == 8)>Agustus</option>
                                        <option value="9" @selected(request('bulan') == 9)>September</option>
                                        <option value="10" @selected(request('bulan') == 10)>Oktober</option>
                                        <option value="11" @selected(request('bulan') == 11)>November</option>
                                        <option value="12" @selected(request('bulan') == 12)>Desember</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    @php
                                        $tahunSekarang = date('Y');
                                    @endphp
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select name="tahun" id="tahun" class="form-select form-select-sm" required>
                                        @for ($i = 0; $i <= 5; $i++)
                                            <option value="{{ $tahunSekarang - $i }}" @selected(request('tahun', $tahunSekarang) == $tahunSekarang - $i)>
                                                {{ $tahunSekarang - $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2 align-self-end">
                                    <button type="submit" class="btn btn-sm btn-success">Tampilkan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tombol Tambah Data --}}
                <a href="{{ url('/penerimaan/create') }}" class="btn btn-primary mb-3">
                    <i class="bi bi-plus-circle-fill me-2"></i> Tambah Penerimaan
                </a>

                {{-- Tabel Data --}}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>TGL PEMBUKUAN</th>
                                <th>NO NOTA</th>
                                <th>SUPPLIER</th>
                                <th>NAMA BARANG</th>
                                <th>QTY</th>
                                <th>HARGA SATUAN</th>
                                <th>HARGA TOTAL</th>
                                <th>SUMBER DANA</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td class="text-center">{{ $item->tanggal_pembukuan->format('d-m-Y') }}</td>
                                <td>{{ $item->no_nota }}</td>
                                <td>{{ $item->supplier }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-end">{{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item->qty * $item->harga_satuan, 2, ',', '.') }}</td>
                                <td class="text-center">{{ $item->sumber_dana }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        {{-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </button> --}}
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                            {{-- Aksi Detail --}}
                                            <li><a class="dropdown-item" href="{{ url('/penerimaan/' . $item->id) }}"><i class="bi bi-eye-fill me-2"></i>Detail</a></li>

                                            {{-- Aksi Edit --}}
                                            <li><a class="dropdown-item" href="{{ url('/penerimaan/' . $item->id . '/edit') }}"><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>

                                            {{-- Aksi Hapus --}}
                                            <li>
                                                <form action="{{ url('/penerimaan/' . $item->id) }}" method="POST" class="d-inline form-hapus">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="deleteform(this)">
                                                        <i class="bi bi-trash-fill me-2"></i>Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
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
{{-- SweetAlert2 untuk notifikasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Pastikan Anda sudah memuat jQuery dan DataTables di template utama Anda --}}
<script>
    // Inisialisasi DataTables
    $(document).ready(function() {
        $('#table').DataTable({
            "language": {
                // Arahkan ke file lokal menggunakan helper asset()
                "url": "{{ asset('assets/js/id.json') }}"
            }
        });
    });

    // Fungsi konfirmasi hapus data
    function deleteform(button) {
        // Mencegah form dikirim langsung
        event.preventDefault();

        const form = button.closest('form');

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user menekan "Ya, hapus!", maka kirim form-nya
                form.submit();
            }
        });
    }
</script>
@endsection