@extends('templates.master')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
                                    <div class="col-3">
                                        <label for="bulan">Bulan</label>
                                        <select name="bulan" id="bulan" class="form-control" style="font-size: 14px;">
                                            <option>Silahkan pilih bulan aktif</option>
                                            <option value="1" selected="">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
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
                                        <form id="formCreate" method="POST" action="{{ url('/pengeluaran/create') }}">
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
                                                                            max="31" required>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                                        <input type="number" class="form-control"
                                                                            name="bulan" id="bulan" disabled>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                                                        <input type="number" class="form-control"
                                                                            name="year" id="year"
                                                                            value="{{ date('Y') }}" disabled>
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
                                                                        <select name="status" id="status"
                                                                            class="form-control">
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
                                                                        <select name="nama_barang" id="NamaBarang"
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
                            {{-- @if (Auth::user()->jabatan_id == 3)
                                <div class="tambah-data">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#TambahData">
                                        Tambah Data </button>

                                    <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="TambahDataLabel">Tambah Data Transaksi
                                                        Pengeluaran
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form id="formCreate" method="POST"
                                                    action="{{ url('/pengeluaran/create') }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12 d-flex justify-content-center gap-3">
                                                                <div class="label">
                                                                    Total Saldo Tersedia :
                                                                </div>
                                                                <div class="value-buget" id="budget-awal"
                                                                    data-budget="{{ $budget_awal->saldo_digunakan ?? 0 }}">
                                                                    {{ currency($budget_awal->saldo_digunakan ?? 0) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <table
                                                            class="table table-bordered table-hover table-responsive mt-3"
                                                            id="table-create">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nama</th>
                                                                    <th>Harga</th>
                                                                    <th>Qty</th>
                                                                    <th>Total Harga</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-control" name="kode[]"
                                                                            id="kode" required>
                                                                            <option value="">Pilih Barang</option>
                                                                            @foreach ($data_barang as $barang)
                                                                                <option value="{{ $barang->kode_barang }}">
                                                                                    {{ ucwords($barang->nama) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control harga currency-input"
                                                                            readonly>
                                                                        <input type="hidden" class="harga-hidden"
                                                                            name="harga[]">
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" min="1"
                                                                            class="form-control qty" name="qty[]"
                                                                            id="qty" required>
                                                                        <input type="hidden" class="stok-hidden"
                                                                            value="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" class="form-control"
                                                                            name="total_harga[]" id="total_harga_hidden"
                                                                            readonly>
                                                                        <input type="text" class="form-control"
                                                                            id="total_harga" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger"
                                                                            onclick="HapusRow(this)">X</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="bg-light">
                                                                    <td colspan="3" class="text-end">
                                                                        Total Biaya
                                                                    </td>
                                                                    <td>
                                                                        <div class="total-biaya">
                                                                            <input type="text" class="form-control"
                                                                                readonly id="total_biaya" value="0">

                                                                            <input type="hidden" name="saldo_awal"
                                                                                class="form-control"
                                                                                value="{{ $budget_awal->saldo_awal ?? 0 }}">
                                                                            <input type="hidden" name="saldo_digunakan"
                                                                                class="form-control"
                                                                                id="total_biaya_hidden">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7">
                                                                        <button type="button"
                                                                            class="btn btn-primary d-block text-left"
                                                                            onclick="TambahRow()">+ Tambah
                                                                            Baris</button>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" form="formCreate"
                                                            class="btn btn-primary simpan"
                                                            onclick="createform(this)">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                            @endif --}}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="table">
                            <thead>
                                <tr class="text-center">
                                    <th>TGL PEMBUKUAN</th>
                                    <th>NAMA BARANG(SATUAN)</th>
                                    <th>STATUS</th>
                                    <th>QTY</th>
                                    <th>KETERANGAN</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->

    <!-- Modal Update Data -->
    @foreach ($data as $item)
        <div class="modal fade" id="update{{ $item->id }}" tabindex="-1"
            aria-labelledby="update{{ $item->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="update{{ $item->id }}Label">Edit Data Transaksi Pengeluaran
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formUpdate{{ $item->id }}" method="POST"
                        action="{{ url('/pengeluaran/update/' . $item->id) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center gap-3">
                                    <div class="label">
                                        Total Saldo Tersedia :
                                    </div>
                                    <div class="value-buget" id="budget-awal-{{ $item->id }}"
                                        data-budget="{{ $budget_awal->saldo_digunakan ?? 0 }}">
                                        {{ currency($budget_awal->saldo_digunakan ?? 0) }}
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-responsive mt-3"
                                id="table-update-{{ $item->id }}">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control" name="kode" id="kode-{{ $item->id }}"
                                                required disabled>
                                                <option value="">Pilih Barang</option>
                                                @foreach ($data_barang as $barang)
                                                    <option value="{{ $barang->kode_barang }}"
                                                        {{ $barang->kode_barang == $item->kode_barang ? 'selected' : '' }}
                                                        data-stok="{{ $barang->qty_digunakan ?? 0 }}">
                                                        {{ ucwords($barang->nama) }}
                                                    </option>

                                                    {{-- <input type="hidden" class="stok-hidden"
                                            value="{{ $barang->qty_digunakan ?? 0  }}"> --}}
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control harga currency-input"
                                                value="{{ currency($item->harga_satuan) }}" readonly>
                                            <input type="hidden" class="harga-hidden" name="harga"
                                                value="{{ $item->harga_satuan }}">
                                        </td>
                                        <td>
                                            <input type="number" min="1" class="form-control qty" name="qty"
                                                id="qty-{{ $item->id }}" value="{{ $item->qty }}" required>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control" name="total_harga"
                                                id="total_harga_hidden-{{ $item->id }}"
                                                value="{{ $item->total_harga }}">
                                            <input type="text" class="form-control"
                                                id="total_harga-{{ $item->id }}"
                                                value="{{ currency($item->total_harga) }}" readonly>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-end">Total Biaya</td>
                                        <td>
                                            <div class="total-biaya">
                                                <input type="text" class="form-control" readonly
                                                    id="total_biaya-{{ $item->id }}"
                                                    value="{{ currency($item->total_harga) }}">
                                                <input type="hidden" name="saldo_awal" class="form-control"
                                                    value="{{ $budget_awal->saldo_awal ?? 0 }}">
                                                <input type="hidden" name="saldo_digunakan" class="form-control"
                                                    id="total_biaya_hidden-{{ $item->id }}"
                                                    value="{{ $item->total_harga }}">
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" form="formUpdate{{ $item->id }}" class="btn btn-primary simpan"
                                onclick="updateform(this, {{ $item->id }})">Simpan</button>
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

        document.addEventListener("DOMContentLoaded", function() {
            // modal tambah
            const tableBody = document.querySelector("#table-create tbody");

            tableBody.addEventListener("change", function(e) {
                if (e.target && e.target.matches("select[name='kode[]']")) {
                    const row = e.target.closest("tr");
                    const kode = e.target.value;

                    // AJAX ambil harga barang
                    fetch(`/get-harga-barang-keluar/${kode}`)
                        .then(res => res.json())
                        .then(data => {
                            const hargaInput = row.querySelector(".harga");
                            const hargaHidden = row.querySelector(".harga-hidden");
                            const stokHidden = row.querySelector(".stok-hidden");

                            hargaHidden.value = data.harga;
                            hargaInput.value = formatRupiah(data.harga);
                            stokHidden.value = data.sisa_qty;

                            updateTotalPerRow(row);
                            updateTotalBiaya();
                        })
                }
            });

            tableBody.addEventListener("input", function(e) {
                if (e.target && e.target.matches("input[name='qty[]']")) {
                    const row = e.target.closest("tr");
                    const qtyInput = e.target;
                    const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;
                    const kodeBarang = row.querySelector("select[name='kode[]']").value;
                    const totalQty = getTotalQtyPerBarang(kodeBarang);

                    if (totalQty > stok) {
                        alert(
                            `Total Qty untuk barang ini melebihi stok tersedia (${stok}). Total saat ini: ${totalQty}`
                        );
                        qtyInput.classList.add("is-invalid");
                        qtyInput.setCustomValidity("Qty melebihi stok");

                        document.querySelector("button[onclick='TambahRow()']").disabled = true;
                        document.querySelector(".simpan").disabled = true;
                    } else {
                        qtyInput.classList.remove("is-invalid");
                        qtyInput.setCustomValidity("");

                        updateTotalPerRow(row);
                        updateTotalBiaya();
                    }
                }
            });
            // end modal tambah

            // modal update
            document.querySelectorAll("table[id^='table-update-']").forEach(table => {
                const tbody = table.querySelector("tbody");
                const id = table.id.split("table-update-")[1];

                tbody.addEventListener("change", function(e) {
                    if (e.target.matches("select[name='kode']")) {
                        handleSelectUpdate(table, id, e.target);
                    }
                });
                tbody.addEventListener("input", function(e) {
                    if (e.target.matches("input[name='qty']")) {
                        handleInputUpdate(table, id, e.target);
                    }
                });
            });
            // end modal update

        });

        // modal tambah
        function getTotalBiaya() {
            let total = 0;
            document.querySelectorAll("#table-create tbody tr").forEach(row => {
                const harga = parseAngka(row.querySelector(".harga-hidden").value);
                const qty = parseInt(row.querySelector("input[name='qty[]']").value) || 0;
                total += harga * qty;
            });
            return total;
        }

        function getTotalQtyPerBarang(kodeBarang) {
            let total = 0;
            document.querySelectorAll("#table-create tbody tr").forEach(row => {
                const kode = row.querySelector("select[name='kode[]']").value;
                const qty = parseInt(row.querySelector("input[name='qty[]']").value) || 0;
                if (kode === kodeBarang) {
                    total += qty;
                }
            });
            return total;
        }

        function updateTotalPerRow(row) {
            const harga = parseAngka(row.querySelector(".harga-hidden").value);
            const qty = parseInt(row.querySelector("input[name='qty[]']").value) || 0;
            const total = harga * qty;

            row.querySelector("#total_harga_hidden").value = total;
            row.querySelector("#total_harga").value = formatRupiah(total);
        }

        function updateTotalBiaya() {
            const total = getTotalBiaya();
            const budget = parseInt(document.querySelector("#budget-awal").dataset.budget);

            const btnTambah = document.querySelector("button[onclick='TambahRow()']");
            const btnSimpan = document.querySelector(".simpan");

            const isQtyValid = [...document.querySelectorAll("#table-create tbody tr")].every(row => {
                const kode = row.querySelector("select[name='kode[]']").value;
                const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;
                const totalQty = getTotalQtyPerBarang(kode);
                return totalQty <= stok;
            });

            document.querySelector("#total_biaya").value = formatRupiah(total);
            document.querySelector("#total_biaya_hidden").value = total;

            if (total > budget || !isQtyValid) {
                btnTambah.disabled = true;
                btnSimpan.disabled = true;
                document.querySelector("#total_biaya").style.color = 'red';
            } else {
                btnTambah.disabled = false;
                btnSimpan.disabled = false;
                document.querySelector("#total_biaya").style.color = 'black';
            }
        }

        function TambahRow() {
            const totalBiaya = getTotalBiaya(); // jumlah total harga semua baris
            const budget = parseInt(document.querySelector("#budget-awal").dataset.budget);

            if (totalBiaya >= budget) {
                alert("Total biaya sudah mencapai batas budget. Tidak bisa menambah baris lagi.");
                return;
            }

            const table = document.querySelector("#table-create tbody");
            const newRow = table.rows[0].cloneNode(true);

            newRow.querySelector("select").value = "";
            newRow.querySelector(".harga").value = "";
            newRow.querySelector(".harga-hidden").value = "";
            newRow.querySelector(".stok-hidden").value = "";
            newRow.querySelector("input[name='qty[]']").value = "";
            newRow.querySelector("#total_harga").value = "";
            newRow.querySelector("#total_harga_hidden").value = "";

            table.appendChild(newRow);
            updateTotalBiaya();
        }

        function HapusRow(button) {
            const table = document.querySelector("#table-create tbody");
            if (table.rows.length > 1) {
                button.closest("tr").remove();
                updateTotalBiaya();
            } else {
                alert("Minimal satu baris harus ada!");
            }
        }
        // end modal tambah

        function formatRupiah(angka) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0
            }).format(angka);
        }

        function parseAngka(str) {
            return parseInt(str.replace(/[^\d]/g, "")) || 0;
        }
        // _________________________________________________________________

        // modal update
        function updateTotalPerRowUpdate(row, id) {
            const harga = parseAngka(row.querySelector(".harga-hidden").value);
            const qty = parseInt(row.querySelector("input[name='qty']").value) || 0;
            const total = harga * qty;

            row.querySelector(`#total_harga_hidden-${id}`).value = total;
            row.querySelector(`#total_harga-${id}`).value = formatRupiah(total);
        }

        function updateTotalBiayaUpdate(id) {
            const table = document.querySelector(`#table-update-${id}`);
            const rows = table.querySelectorAll("tbody tr");

            let total = 0;
            let isValid = true;

            rows.forEach(row => {
                const harga = parseAngka(row.querySelector(".harga-hidden").value);
                const qty = parseInt(row.querySelector("input[name='qty']").value) || 0;
                const kode = row.querySelector("select[name='kode']").value;
                const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;

                if (qty > stok) {
                    isValid = false;
                    row.querySelector("input[name='qty']").classList.add("is-invalid");
                    row.querySelector("input[name='qty']").setCustomValidity("Qty melebihi stok");
                } else {
                    row.querySelector("input[name='qty']").classList.remove("is-invalid");
                    row.querySelector("input[name='qty']").setCustomValidity("");
                }

                total += harga * qty;

                updateTotalPerRowUpdate(row, id); // perbarui tampilan total di setiap baris
            });

            const budget = parseInt(document.querySelector(`#budget-awal-${id}`).dataset.budget);
            const btn = document.querySelector(`#update${id} .simpan`);
            const totalBiayaInput = document.querySelector(`#total_biaya-${id}`);
            const totalHiddenInput = document.querySelector(`#total_biaya_hidden-${id}`);

            totalBiayaInput.value = formatRupiah(total);
            totalHiddenInput.value = total;

            if (total > budget || !isValid) {
                btn.disabled = true;
                totalBiayaInput.style.color = 'red';
            } else {
                btn.disabled = false;
                totalBiayaInput.style.color = 'black';
            }
        }

        function handleSelectUpdate(table, id, selectEl) {
            const row = selectEl.closest("tr");
            const kode = selectEl.value;

            // Reset qty & total saat barang diganti
            row.querySelector("input[name='qty']").value = "";
            row.querySelector(".harga-hidden").value = "";
            row.querySelector(".harga").value = "";
            row.querySelector(".stok-hidden").value = "";
            row.querySelector(`#total_harga_hidden-${id}`).value = "";
            row.querySelector(`#total_harga-${id}`).value = "0";

            fetch(`/get-harga-barang-keluar/${kode}`)
                .then(res => res.json())
                .then(data => {
                    row.querySelector(".harga-hidden").value = data.harga;
                    row.querySelector(".harga").value = formatRupiah(data.harga);
                    row.querySelector(".stok-hidden").value = data.sisa_qty;

                    // Ambil ulang qty jika sudah diisi, lalu hitung total
                    const qty = parseInt(row.querySelector("input[name='qty']").value) || 0;
                    if (qty > 0) {
                        updateTotalPerRowUpdate(row);
                        updateTotalBiayaUpdate(id);
                    }
                });
        }


        function handleInputUpdate(table, id, qtyInput) {
            const row = qtyInput.closest("tr");
            // const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;
            // const kode = row.querySelector("select[name='kode']").value;
            const select = document.getElementById('kode-{{ $item->id ?? '' }}');
            const selectedOption = select.options[select.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok');
            const totalQty = parseInt(qtyInput.value) || 0;

            if (totalQty > stok) {
                alert(`Qty melebihi stok tersedia (${stok}).`);
                qtyInput.classList.add("is-invalid");
                qtyInput.setCustomValidity("Qty melebihi stok");
                document.querySelector("button[onclick='updateform(this, {{ $item->id ?? '' }})']").disabled = true;
            } else {
                qtyInput.classList.remove("is-invalid");
                qtyInput.setCustomValidity("");
                document.querySelector("button[onclick='updateform(this, {{ $item->id ?? '' }})']").disabled = false;

                updateTotalPerRowUpdate(row, id);
                updateTotalBiayaUpdate(id);
            }
        }
        // end modal update
    </script>
@endsection
