@extends('templates.master')

@section('content')

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Transaksi Penerimaan</h6>

                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex gap-3 align-items-center">
                        <div class="tambah-data">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#TambahData">
                                Tambah Transaksi Penerimaan </button>

                            <!-- Modal Tambah Data -->
                            <div class="modal fade" id="TambahData" tabindex="-1" aria-labelledby="TambahDataLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="TambahDataLabel">Tambah Data Transaksi
                                                Penerimaan
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="formCreate" method="POST" action="{{ url('/penerimaan/create') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 d-flex justify-content-center gap-3">
                                                        <div class="label">
                                                            Total Saldo Tersedia :
                                                        </div>
                                                        <div class="value-buget" id="budget-awal"
                                                            data-budget="{{ ($budget_awal->saldo_awal ?? 0) - ($budget_awal->saldo_digunakan ?? 0) }}">
                                                            {{ currency(($budget_awal->saldo_awal ?? 0) -
                                                            ($budget_awal->saldo_digunakan ?? 0)) }} </div>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-hover table-responsive mt-3"
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
                                                                <select class="form-control" name="kode[]" id="kode"
                                                                    required>
                                                                    <option value="">Pilih Barang</option>
                                                                    @foreach ($data_barang as $barang)
                                                                    <option value="{{ $barang->kode_barang }}">{{
                                                                        ucwords($barang->nama) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control harga currency-input" readonly>
                                                                <input type="hidden" class="harga-hidden"
                                                                    name="harga[]">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" class="form-control qty"
                                                                    name="qty[]" id="qty" required>
                                                                <input type="hidden" class="stok-hidden" value="">
                                                            </td>
                                                            <td>
                                                                <input type="hidden" class="form-control"
                                                                    name="total_harga[]" id="total_harga_hidden"
                                                                    readonly>
                                                                <input type="text" class="form-control" id="total_harga"
                                                                    readonly>
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
                                                                    <input type="text" class="form-control" readonly
                                                                        id="total_biaya" value="0">

                                                                    <input type="hidden" name="saldo_awal"
                                                                        class="form-control"
                                                                        value="{{ $budget_awal->saldo_awal ?? 0 }}">
                                                                    <input type="hidden" name="saldo_digunakan"
                                                                        class="form-control" id="total_biaya_hidden">
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
                                                    class="btn btn-primary simpan">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                    <table class="table table-hover table-bordered table-striped" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th>Sisa Saldo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <div class="edit">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#update{{ $item->id }}">
                                                Edit
                                            </button>
                                        </div>

                                        <div class="hapus">
                                            <form action="{{ url('/penerimaan/hapus/'.$item->id) }}" method="post">
                                                @csrf

                                                <button type="submit" class="btn btn-sm btn-danger">
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

@endsection

{{-- @foreach ($datas as $item)
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
@endforeach --}}

@section('js')
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Event delegation untuk handle dinamis
        const tableBody = document.querySelector("#table-create tbody");

        tableBody.addEventListener("change", function (e) {
            if (e.target && e.target.matches("select[name='kode[]']")) {
                const row = e.target.closest("tr");
                const kode = e.target.value;

                // AJAX ambil harga barang
                fetch(`/get-harga-barang/${kode}`)
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

        tableBody.addEventListener("input", function (e) {
            if (e.target && e.target.matches("input[name='qty[]']")) {
                const row = e.target.closest("tr");
                const qtyInput = e.target;
                const qty = parseInt(qtyInput.value) || 0;
                const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;

                if (qty > stok) {
                    alert("Qty melebihi stok tersedia: " + stok);
                    qtyInput.classList.add("is-invalid");
                    qtyInput.setCustomValidity("Qty melebihi stok");

                    // Nonaktifkan tombol
                    document.querySelector("button[onclick='TambahRow()']").disabled = true;
                    document.querySelector(".simpan").disabled = true;
                } else {
                    qtyInput.classList.remove("is-invalid");
                    qtyInput.setCustomValidity("");

                    updateTotalBiaya(); // tetap update
                }

                updateTotalPerRow(row);
            }
        });
    });

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

        const isQtyValid = [...document.querySelectorAll("input[name='qty[]']")].every(input => {
            const row = input.closest("tr");
            const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;
            const qty = parseInt(input.value) || 0;
            return qty <= stok;
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

    function getTotalBiaya() {
        let total = 0;
        document.querySelectorAll("#table-create tbody tr").forEach(row => {
            const harga = parseAngka(row.querySelector(".harga-hidden").value);
            const qty = parseInt(row.querySelector("input[name='qty[]']").value) || 0;
            total += harga * qty;
        });
        return total;
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
</script>
@endsection