@extends('templates.master')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        .dropdown .dropdown-toggle::after {
            display: none;
        }

        .table-responsive {
            overflow: visible !important;
        }

        .dropdown {
            position: relative;
            z-index: 1000;
        }

        .dropdown-menu {
            z-index: 1050;
            /* lebih tinggi dari konten biasa */
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Manajemen Pengeluaran Barang Persediaan</h6>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 mt-2">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                        {{-- jabatan_id == 2 (pengurus barang) --}}
                        {{-- @if (Auth::user()->jabatan_id == 2)

                    @else --}}
                        <div class="col-12 mt-2">
                            <form action="{{ url('/pengeluaran') }}" method="post">
                                <div class="row d-flex gap-0">
                                    @csrf
                                    {{-- <div class="col-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="selesai" {{ $req_status=='selesai' ? 'selected' : '' }}>
                                            Terverifikasi</option>
                                        <option value="tolak" {{ $req_status=='tolak' ? 'selected' : '' }}>
                                            Tolak</option>
                                    </select>
                                </div> --}}
                                    <div class="col-2 align-self-center pt-3">
                                        <h5>Pilih Bulan Aktif</h5>
                                    </div>
                                    <div class="col-3 pt-3">
                                        <label for="bulan" class="d-none">Bulan</label>
                                        <select name="bulan" id="bulan" class="form-control" style="font-size: 14px;">
                                            <option value="" disabled selected>Silahkan pilih bulan aktif</option>
                                            <option value="1" {{ $req_month == 1 ? 'selected' : '' }}>Januari</option>
                                            <option value="2" {{ $req_month == 2 ? 'selected' : '' }}>Februari</option>
                                            <option value="3" {{ $req_month == 3 ? 'selected' : '' }}>Maret</option>
                                            <option value="4" {{ $req_month == 4 ? 'selected' : '' }}>April</option>
                                            <option value="5" {{ $req_month == 5 ? 'selected' : '' }}>Mei</option>
                                            <option value="6" {{ $req_month == 6 ? 'selected' : '' }}>Juni</option>
                                            <option value="7" {{ $req_month == 7 ? 'selected' : '' }}>Juli</option>
                                            <option value="8" {{ $req_month == 8 ? 'selected' : '' }}>Agustus</option>
                                            <option value="9" {{ $req_month == 9 ? 'selected' : '' }}>September
                                            </option>
                                            <option value="10" {{ $req_month == 10 ? 'selected' : '' }}>Oktober
                                            </option>
                                            <option value="11" {{ $req_month == 11 ? 'selected' : '' }}>November
                                            </option>
                                            <option value="12" {{ $req_month == 12 ? 'selected' : '' }}>Desember
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-3 d-none">
                                        @php
                                            $tahunSekarang = date('Y');
                                        @endphp
                                        <label for="year">Tahun</label>
                                        <select name="tahun" id="year" class="form-control" required>
                                            @for ($i = 0; $i <= 10; $i++)
                                                <option value="{{ $tahunSekarang - $i }}"
                                                    {{ $req_year == $tahunSekarang - $i ? 'selected' : '' }}>
                                                    {{ $tahunSekarang - $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col align-self-center pt-3">
                                        <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- @endif --}}
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex gap-3 align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#TambahData" {{ Auth::user()->jabatan_id == 3 ? '' : 'disabled' }}>
                                Tambah Data </button>

                            <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="TambahDataLabel">Penambahan Buku
                                                Pengeluaran
                                                Persediaan Barang
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="formCreate" method="POST" action="{{ url('/pengeluaran/store') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6>Tanggal Pembukuan</h6>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                                        <input type="number" class="form-control"
                                                                            name="tanggal" id="tanggal" min="1"
                                                                            max="31" value="{{ date('d') }}"
                                                                            required>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                                        <select class="form-select" id="bulan"
                                                                            name="bulan">
                                                                            <option value="1"
                                                                                {{ date('m') == 1 ? 'selected' : '' }}>
                                                                                Januari</option>
                                                                            <option value="2"
                                                                                {{ date('m') == 2 ? 'selected' : '' }}>
                                                                                Februari</option>
                                                                            <option value="3"
                                                                                {{ date('m') == 3 ? 'selected' : '' }}>
                                                                                Maret</option>
                                                                            <option value="4"
                                                                                {{ date('m') == 4 ? 'selected' : '' }}>
                                                                                April</option>
                                                                            <option value="5"
                                                                                {{ date('m') == 5 ? 'selected' : '' }}>
                                                                                Mei</option>
                                                                            <option value="6"
                                                                                {{ date('m') == 6 ? 'selected' : '' }}>
                                                                                Juni</option>
                                                                            <option value="7"
                                                                                {{ date('m') == 7 ? 'selected' : '' }}>
                                                                                Juli</option>
                                                                            <option value="8"
                                                                                {{ date('m') == 8 ? 'selected' : '' }}>
                                                                                Agustus</option>
                                                                            <option value="9"
                                                                                {{ date('m') == 9 ? 'selected' : '' }}>
                                                                                September</option>
                                                                            <option value="10"
                                                                                {{ date('m') == 10 ? 'selected' : '' }}>
                                                                                Oktober</option>
                                                                            <option value="11"
                                                                                {{ date('m') == 11 ? 'selected' : '' }}>
                                                                                November</option>
                                                                            <option value="12"
                                                                                {{ date('m') == 12 ? 'selected' : '' }}>
                                                                                Desember</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                                        <input type="number" class="form-control"
                                                                            name="tahun" id="tahun"
                                                                            value="{{ date('Y') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6>Status Pengeluaran</h6>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <select name="status_pengeluaran"
                                                                            id="status_pengeluaran" class="form-control">
                                                                            <option value="" selected>Pilih Status
                                                                            </option>
                                                                            <option value="pemakaian">Pemakaian</option>
                                                                            <option value="penghapusan">Penghapusan
                                                                            </option>
                                                                            <option value="mutasi">Mutasi</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6>Nama Barang</h6>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <select name="kode_barang" id="NamaBarang"
                                                                            class="form-control">
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6>QTY</h6>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="number" class="form-control"
                                                                            name="qty" id="qty" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6>Keterangan</h6>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <textarea class="form-control" name="keterangan" id="keterangan"> </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" form="formCreate"
                                                    class="btn btn-sm btn-primary simpan"
                                                    onclick="createform(this)">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="table">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-dark">TGL PEMBUKUAN</th>
                                        <th class="text-dark">NAMA BARANG (SATUAN)</th>
                                        <th class="text-dark">STATUS</th>
                                        <th class="text-dark">QTY</th>
                                        <th class="text-dark">KETERANGAN</th>
                                        <th class="text-dark">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="text-center">
                                            <td class="align-middle text-secondary">
                                                {{ $item->tanggal_pembukuan->format('d-m-Y') }}</td>
                                            <td class="align-middle text-secondary">
                                                {{ $item->kode_barang }}
                                                <br>
                                                {{ $item->nama_barang }}
                                            </td>
                                            <td class="align-middle text-secondary">
                                                {{ strtoupper($item->status_pengeluaran) }}</td>
                                            <td class="align-middle text-secondary">{{ $item->qty }}</td>
                                            <td class="align-middle text-secondary">{{ $item->keterangan }}</td>
                                            <td class="align-middle text-secondary">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light p-1 dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i data-feather="more-vertical"
                                                            style="width:16px;height:16px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button type="button" data-bs-toggle="modal"
                                                                data-bs-target="#ModalUpdate{{ $item->id }}"
                                                                class="dropdown-item">
                                                                <i class="bi bi-pencil-fill me-2"></i> Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form action="{{ url('pengeluaran/delete/' . $item->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <button onClick="deleteform(this)"
                                                                    class="dropdown-item text-danger">
                                                                    <i class="bi bi-trash-fill me-2"></i> Delete
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
        </div> <!-- row -->

        <!-- Modal Update Data -->
        @foreach ($data as $item)
            <div class="modal fade" id="ModalUpdate{{ $item->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="formUpdate" action="{{ url('pengeluaran/update/' . $item->id) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Tanggal Pembukuan</h6>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                        <input type="number" class="form-control" name="tanggal"
                                                            id="tanggal" min="1" max="31"
                                                            value="{{ $item->tanggal_pembukuan->format('d') }}" required>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                        <select class="form-select" id="bulan" name="bulan">
                                                            <option value="1"
                                                                {{ $item->tanggal_pembukuan->format('m') == 1 ? 'selected' : '' }}>
                                                                Januari</option>
                                                            <option value="2"
                                                                {{ $item->tanggal_pembukuan->format('m') == 2 ? 'selected' : '' }}>
                                                                Februari</option>
                                                            <option value="3"
                                                                {{ $item->tanggal_pembukuan->format('m') == 3 ? 'selected' : '' }}>
                                                                Maret</option>
                                                            <option value="4"
                                                                {{ $item->tanggal_pembukuan->format('m') == 4 ? 'selected' : '' }}>
                                                                April</option>
                                                            <option value="5"
                                                                {{ $item->tanggal_pembukuan->format('m') == 5 ? 'selected' : '' }}>
                                                                Mei</option>
                                                            <option value="6"
                                                                {{ $item->tanggal_pembukuan->format('m') == 6 ? 'selected' : '' }}>
                                                                Juni</option>
                                                            <option value="7"
                                                                {{ $item->tanggal_pembukuan->format('m') == 7 ? 'selected' : '' }}>
                                                                Juli</option>
                                                            <option value="8"
                                                                {{ $item->tanggal_pembukuan->format('m') == 8 ? 'selected' : '' }}>
                                                                Agustus</option>
                                                            <option value="9"
                                                                {{ $item->tanggal_pembukuan->format('m') == 9 ? 'selected' : '' }}>
                                                                September</option>
                                                            <option value="10"
                                                                {{ $item->tanggal_pembukuan->format('m') == 10 ? 'selected' : '' }}>
                                                                Oktober</option>
                                                            <option value="11"
                                                                {{ $item->tanggal_pembukuan->format('m') == 11 ? 'selected' : '' }}>
                                                                November</option>
                                                            <option value="12"
                                                                {{ $item->tanggal_pembukuan->format('m') == 12 ? 'selected' : '' }}>
                                                                Desember</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                        <input type="number" class="form-control" name="tahun"
                                                            id="tahun"
                                                            value="{{ $item->tanggal_pembukuan->format('Y') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Status Pengeluaran</h6>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <select name="status_pengeluaran" id="status_pengeluaran"
                                                            class="form-control">
                                                            <option value=""
                                                                {{ $item->status_pengeluaran == '' ? 'selected' : '' }}>
                                                                Pilih Status
                                                            </option>
                                                            <option value="pemakaian"
                                                                {{ $item->status_pengeluaran == 'pemakaian' ? 'selected' : '' }}>
                                                                Pemakaian</option>
                                                            <option value="penghapusan"
                                                                {{ $item->status_pengeluaran == 'penghapusan' ? 'selected' : '' }}>
                                                                Penghapusan
                                                            </option>
                                                            <option value="mutasi"
                                                                {{ $item->status_pengeluaran == 'mutasi' ? 'selected' : '' }}>
                                                                Mutasi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Nama Barang</h6>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <select name="kode_barang" id="NamaBarang{{ $item->id }}"
                                                            class="form-control nama-barang-select"
                                                            data-modal="#ModalUpdate{{ $item->id }}">
                                                            @if ($item->barang)
                                                                <option value="{{ $item->barang->id }}" selected>
                                                                    {{ $item->barang->kode_barang }} -
                                                                    {{ $item->barang->nama_barang }}</option>
                                                            @endif
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>QTY</h6>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="number" class="form-control" name="qty"
                                                            id="qty" value="{{ $item->qty }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Keterangan</h6>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <textarea class="form-control" name="keterangan" id="keterangan"> {{ $item->keterangan }} </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" form="formUpdate" class="btn btn-sm btn-primary simpan"
                                    onclick="updateform(this)">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection



    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#table').DataTable();
            });

            $(document).ready(function() {
                // Inisialisasi Select2 untuk dropdown NamaBarang
                $('#NamaBarang').select2({
                    // Tentukan tema yang akan digunakan
                    theme: 'bootstrap-5',

                    // Targetkan elemen modal sebagai induk dari dropdown
                    // Ini SANGAT PENTING agar search box di dalam modal berfungsi
                    dropdownParent: $('#TambahData'),

                    // Teks placeholder
                    placeholder: 'Ketik untuk mencari barang...',

                    // Pengaturan AJAX untuk mengambil data
                    ajax: {
                        url: '/seluruh_data_barang', // Route yang dituju
                        dataType: 'json',
                        delay: 250, // Jeda sebelum request dikirim setelah user mengetik

                        // Fungsi untuk memproses data yang akan dikirim ke server
                        data: function(params) {
                            return {
                                search: params
                                    .term // Kirim teks yang diketik user sebagai parameter 'search'
                            };
                        },

                        // Fungsi untuk memformat data yang diterima dari server
                        processResults: function(data) {

                            // Ubah format data dari server agar sesuai dengan format Select2
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id, // ID barang
                                        text: `${item.kode_barang} - ${item.nama_barang}` // Teks yang ditampilkan
                                    }
                                })
                            };
                        },
                        cache: true // Aktifkan cache untuk request yang sama
                    }
                });
            });

            // fungsi update
            $(document).ready(function() {
                $('.nama-barang-select').each(function() {
                    const $select = $(this);
                    const modalSelector = $select.data('modal');

                    $select.select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $(modalSelector),
                        placeholder: 'Ketik untuk mencari barang...',
                        ajax: {
                            url: '/seluruh_data_barang',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            id: item.id,
                                            text: `${item.kode_barang} - ${item.nama_barang}`
                                        };
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                });
            });


            function tolakForm(button) {
                event.preventDefault();

                const form = button.closest("form");

                Swal.fire({
                    title: "Apakah Anda yakin menolak data ini ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Tolak!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            function verifikasiform(button) {
                event.preventDefault();

                const form = button.closest("form");

                Swal.fire({
                    title: "Apakah Anda yakin verifikasi data ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Verifikasi!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            function createform(button) {
                event.preventDefault();

                const form = document.getElementById(button.getAttribute("form"));

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "Apakah Anda yakin menambah data ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, save!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            function updateform(button) {
                event.preventDefault();

                const form = document.getElementById(button.getAttribute("form"));

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "Apakah Anda yakin melakukan update ini ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, update!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            function deleteform(button) {
                event.preventDefault();

                const form = button.closest('form');

                Swal.fire({
                    title: "Apakah Anda yakin menghapus data ?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        </script>
    @endsection
