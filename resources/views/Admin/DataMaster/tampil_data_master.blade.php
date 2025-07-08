@php
use Illuminate\Support\Str;
@endphp
@extends('templates.master')


@section('content')
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Data Master Barang</h6>
                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex gap-3 align-items-center">
                        <div class="tambah-data">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#TambahData">
                                Tambah Data </button>

                            <!-- Modal Tambah Data -->
                            <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="TambahDataLabel">Tambah Data Barang
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="formCreate" method="POST" action="{{ url('/data-master') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <table class="table table-bordered table-hover table-responsive mt-3"
                                                    id="table-create">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Nama</th>
                                                            <th>Kategori</th>
                                                            <th>Satuan</th>
                                                            <th>Harga</th>
                                                            <th>Stok Awal</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control" name="kode[]"
                                                                    id="kode" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="nama[]"
                                                                    id="nama" required>
                                                            </td>
                                                            <td>
                                                                <select name="kategori[]" id="kategori"
                                                                    class="form-control" required>
                                                                    <option value="">Pilih Kategori</option>
                                                                    @foreach ($kategoris as $kategori)
                                                                    <option value="{{ $kategori->id }}">{{
                                                                        strtoupper($kategori->nama_kategori) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="satuan[]"
                                                                    id="satuan" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control currency-input"
                                                                    required>
                                                                <input type="hidden" name="harga[]"
                                                                    class="harga-hidden">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" class="form-control"
                                                                    name="qty[]" id="qty" required>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="HapusRow(this)">X</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
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
                                                <button type="submit" form="formCreate" class="btn btn-primary"
                                                    onclick="createform(this)">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tambah-stok">
                            <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#TambahStok">
                                Tambah Stok </button>

                            <!-- Modal Tambah Data -->
                            <div class="modal fade" id="TambahStok" tabindex="-1" aria-labelledby="TambahStokLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="TambahStokLabel">Tambah Stok Barang
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="formCreateStok" method="POST"
                                            action="{{ url('/data-master/tambah-stok') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <table class="table table-bordered table-hover table-responsive mt-3"
                                                    id="table-create-stok">
                                                    <thead>
                                                        <tr>
                                                            <th width="60%">Nama</th>
                                                            <th width="30%">Tambah Stok</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <select class="form-control" name="kode[]" id="kode"
                                                                    required>
                                                                    <option value="">Pilih Barang</option>
                                                                    @foreach ($datas as $data)
                                                                    <option value="{{ $data->kode_barang }}">{{
                                                                        ucwords($data->nama) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" class="form-control"
                                                                    name="qty[]" id="qty" required>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="HapusRowStok(this)">X</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3">
                                                                <button type="button"
                                                                    class="btn btn-primary d-block text-left"
                                                                    onclick="TambahRowStok()">+ Tambah
                                                                    Baris</button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" form="formCreateStok" class="btn btn-primary"
                                                    onclick="createform(this)">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Riwayat transaksi">
                            <a href="{{ url('/riwayat-transaksi') }}" class="btn btn-info text-white"
                                data-bs-target="#TambahStok">
                                Riwayat Transaksi </a>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
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

                <div class="table-responsive">
                    {{-- <div class="row">
                        <div class="col-12">
                            <a href="{{ url('/export-excel') }}" class="btn btn-success">
                                <i class="fa fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div> --}}
                    <table class="table table-hover table-bordered table-striped" id="table">
                        <thead>
                            <tr class="text-center align-middle">
                                <th rowspan="0">No</th>
                                {{-- <th rowspan="2">TanggaL</th> --}}
                                <th rowspan="0">Kode Barang</th>
                                <th rowspan="0">Nama Barang</th>
                                <th rowspan="0">Kategori</th>
                                <th rowspan="0">Satuan</th>
                                <th colspan="0">Qty sisa</th>
                                <th colspan="0">Harga</th>
                                <th colspan="0">Jumlah</th>
                                <th rowspan="0">Aksi</th>
                            </tr>
                            {{-- <tr class="text-center">
                                <th>Qty_sisa</th>
                                <th>Harga</th>
                                <th>Nilai</th>
                            </tr> --}}
                        </thead>
                        <tbody>
                            @foreach ($datas as $item)
                            {{-- @dd($item) --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                {{-- <td>{{ $item->tgl_buat->format('d-m-Y') }}</td> --}}
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ Str::limit($item->nama, 20) }}</td>
                                <td>{{ Str::limit($item->kategori->nama_kategori, 20) }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ (int) $item->qty_sisa }}</td>
                                <td>{{ currency($item->harga) }}</td>
                                <td>{{ currency($item->jumlah) }}</td>
                                {{-- <td>{{ currency($item->qty_awal * $item->harga) }}</td> --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <div class="edit">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#update{{ $item->id }}">
                                                Edit
                                            </button>
                                        </div>

                                        <div class="hapus">
                                            <form action="{{ url('/data-master/hapus/'.$item->id) }}" method="post">
                                                @csrf

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="deleteform(this)">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
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

@foreach ($datas as $item)
<!-- Modal Update-->
<div class="modal fade" id="update{{ $item->id }}" tabindex="-1" aria-labelledby="update{{ $item->id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="update{{ $item->id }}Label">Update Data Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdate{{ $item->id }}" method="POST" action="{{ url('/data-master/edit/'.$item->id) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <table class="table table-bordered  table-responsive mt-3" id="table-update">
                        <thead>
                            <tr>
                                <th>Kode <span class="text-danger" style="font-size: 8px;">*tidak bisa di ubah</span>
                                </th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="kode" id="kode"
                                        value="{{ $item->kode_barang }}" readonly
                                        style="background: #00000024; cursor: not-allowed">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        value="{{ $item->nama }}" required>
                                </td>
                                <td>
                                    <select class="form-control" name="kategori" id="kategori" required>
                                        @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ $item->kategori_id == $kategori->id ? 'selected' : '' }}>{{ ucwords($kategori->nama_kategori) }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="kategori" id="kategori"
                                        value="{{ $item->kategori->nama_kategori }}" required> --}}
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="satuan" id="satuan"
                                        value="{{ $item->satuan }}" required>
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control currency-input @error('harga') is-invalid @enderror"
                                        value="{{ currency($item->harga) }}" required>
                                    <input type="hidden" name="harga" value="{{ $item->harga }}" class="harga-hidden">
                                </td>
                                <td>
                                    <input type="text" class="form-control @error('qty_sisa') is-invalid @enderror"
                                        value="{{ $item->qty_sisa }}" name="qty_sisa" readonly >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="formUpdate{{ $item->id }}"
                        onclick="updateform(this)">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection


@section('js')
{{-- sweet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

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

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".currency-input").forEach(input => {
            input.addEventListener("input", function () {
                formatRupiahInput(this);
            });
        });
    });

    function formatRupiahInput(input) {
        let value = input.value.replace(/[^0-9]/g, "");
        let formatted = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(value);

        input.value = formatted;

        // Set hidden input value
        const hiddenInput = input.closest("td").querySelector(".harga-hidden");
        hiddenInput.value = value;
    }

    function TambahRow() {
        const table = document.querySelector("#table-create tbody");
        const newRow = table.rows[0].cloneNode(true);

        newRow.querySelectorAll("input").forEach(el => el.value = "");
        newRow.querySelectorAll(".currency-input").forEach(el => {
            el.value = "";
            el.addEventListener("input", function () {
                formatRupiahInput(this);
            });
        });
        newRow.querySelectorAll(".harga-hidden").forEach(el => el.value = "");

        table.appendChild(newRow);
    }
    
    function HapusRow(button) {
        const table = document.querySelector("#table-create tbody");
        if (table.rows.length > 1) {
            button.closest("tr").remove();
        } else {
            alert("Minimal satu baris harus ada!");
        }
    }

    function TambahRowStok() {
        const table = document.querySelector("#table-create-stok tbody");
        const newRow = table.rows[0].cloneNode(true);

        newRow.querySelectorAll("select").forEach(el => el.value = "");
        newRow.querySelectorAll(".currency-input").forEach(el => {
            el.value = "";
            el.addEventListener("input", function () {
                formatRupiahInput(this);
            });
        });
        newRow.querySelectorAll(".harga-hidden").forEach(el => el.value = "");

        table.appendChild(newRow);
    }
    
    function HapusRowStok(button) {
        const table = document.querySelector("#table-create-stok tbody");
        if (table.rows.length > 1) {
            button.closest("tr").remove();
        } else {
            alert("Minimal satu baris harus ada!");
        }
    }
</script>
@endsection