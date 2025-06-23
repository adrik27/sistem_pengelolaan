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
                        @if (Auth::user()->jabatan_id == 2) {{-- jabatan_id == 2 (pengurus barang) --}}

                        @else
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
                                                <button type="submit" form="formCreate" class="btn btn-primary simpan"
                                                    onclick="createform(this)">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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

                    @if (Auth::user()->jabatan_id == 2) {{-- jabatan_id == 2 (pengurus barang) --}}

                    @else
                    <div class="col-12 mt-2">
                        <form action="{{ url('/penerimaan') }}" method="post">
                            <div class="row d-flex gap-2">
                                @csrf
                                <div class="col-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ $req_status=='pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="verifikasi" {{ $req_status=='verifikasi' ? 'selected' : '' }}>
                                            Terverifikasi</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    @php
                                    $tahunSekarang = date('Y');
                                    @endphp
                                    <label for="year">Tahun</label>
                                    <select name="tahun" id="year" class="form-control" required>
                                        @for ($i = 0; $i <= 10; $i++) <option value="{{ $tahunSekarang - $i }}" {{
                                            $req_year==$tahunSekarang - $i ? 'selected' : '' }}>{{
                                            $tahunSekarang - $i }}</option>
                                            @endfor
                                    </select>
                                </div>
                                <div class="col align-self-center pt-3">
                                    <button type="submit" class="btn btn-sm btn-success">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
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
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            {{-- @dd($item) --}}
                            <tr>
                                <td>{{ $item->tgl_transaksi }}</td>
                                <td>{{ $item->Department->nama }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->nama_satuan }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ currency($item->harga_satuan) }}</td>
                                <td>{{ currency($item->total_harga) }}</td>
                                <td>
                                    {{ currency(($budget_awal->saldo_awal ?? 0) - ($budget_awal->saldo_digunakan ?? 0))
                                    }}
                                </td>
                                <td>
                                    @if ($item->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @else
                                    <span class="badge bg-primary">Terverifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if (Auth::user()->jabatan_id == 3) {{-- pengurus barang --}}
                                        <div class="edit">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#update{{ $item->id }}" {{ $item->status == 'verifikasi'
                                                ? 'disabled' : '' }}>
                                                Edit
                                            </button>
                                        </div>

                                        <div class="hapus">
                                            <form action="{{ url('/penerimaan/hapus/'.$item->id) }}" method="post">
                                                @csrf

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="deleteform(this)" {{ $item->status == 'verifikasi' ?
                                                    'disabled' : '' }}>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <div class="verifikasi">
                                            <form form="verifikasiForm"
                                                action="{{ url('/penerimaan/verifikasi/'.$item->id) }}" method="post">
                                                @csrf

                                                <button type="submit" form="verifikasiForm"
                                                    class="btn btn-sm btn-danger" onclick="verifikasiform(this)">
                                                    Verifikasi
                                                </button>
                                            </form>
                                        </div>
                                        @endif
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



@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

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
                const stok = parseInt(row.querySelector(".stok-hidden").value) || 0;
                const kodeBarang = row.querySelector("select[name='kode[]']").value;
                const totalQty = getTotalQtyPerBarang(kodeBarang);

                if (totalQty > stok) {
                    alert(`Total Qty untuk barang ini melebihi stok tersedia (${stok}). Total saat ini: ${totalQty}`);
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