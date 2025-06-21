@extends('templates.master')

@section('content')

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Data Master Barang</h6>

                </div>

                <div class="row  mt-4 mb-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#TambahData">
                            Tambah Data </button>

                        <!-- Modal -->
                        <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="TambahDataLabel">Data Master</h1>
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
                                                            <input type="number" class="form-control" name="kode[]"
                                                                id="kode" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="nama[]"
                                                                id="nama" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="kategori[]"
                                                                id="kategori" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="satuan[]"
                                                                id="satuan" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control currency-input"
                                                                required>
                                                            <input type="hidden" name="harga[]" class="harga-hidden">
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
                                            <button type="submit" form="formCreate"
                                                class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <table class="table table-hover table-bordered table-striped" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Stok Awal</th>
                                <th>Jumlah Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $item)
                            {{-- @dd($item) --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ currency($item->harga) }}</td>
                                <td>{{ $item->RiwayatStok->qty_awal }}</td>
                                <td>{{ currency($item->RiwayatStok->qty_awal * $item->harga) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <div class="edit">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#update{{ $item->id }}">
                                                Edit
                                            </button>
                                        </div>

                                        {{-- <div class="hapus">
                                            <form action="{{ url('/data-master/hapus/'.$item->id) }}" method="post">
                                                @csrf

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div> --}}
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

@endsection

@foreach ($datas as $item)
<!-- Modal -->
<div class="modal fade" id="update{{ $item->id }}" tabindex="-1" aria-labelledby="update{{ $item->id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="update{{ $item->id }}Label">Update Data Master</h1>
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
                                <th>Stok Awal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="number" class="form-control" name="kode" id="kode"
                                        value="{{ $item->kode_barang }}" readonly
                                        style="background: #00000024; cursor: not-allowed">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        value="{{ $item->nama }}" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="kategori" id="kategori"
                                        value="{{ $item->kategori }}" required>
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
                                    <input type="number" min="1" class="form-control" name="qty" id="qty"
                                        value="{{ $item->RiwayatStok->qty_awal }}" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@section('js')
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

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
</script>
@endsection