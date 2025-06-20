@extends('templates.master')

@section('content')

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Saldo Awal</h6>

                </div>

                <div class="row  mt-4 mb-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#TambahSaldo">
                            Tambah Saldo </button>

                        <!-- Modal -->
                        <div class="modal fade" id="TambahSaldo" tabindex="-1" aria-labelledby="TambahSaldoLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="TambahSaldoLabel">Saldo Awal</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formSaldo" method="POST" action="{{ url('/saldo-awal') }}">
                                            @csrf
                                            <table class="table table-bordered table-hover table-responsive mt-3"
                                                id="table-create">
                                                <thead>
                                                    <tr>
                                                        <th>Department</th>
                                                        <th>Tahun</th>
                                                        <th>Saldo</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select name="department_id[]" class="form-control"
                                                                required>
                                                                <option value="">Pilih Department</option>
                                                                @foreach ($departments as $item)
                                                                <option value="{{ $item->id }}">{{ ucwords($item->nama)
                                                                    }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            @php
                                                            $tahunSekarang = date('Y');
                                                            @endphp
                                                            <select name="tahun[]" class="form-control" required>
                                                                <option value="">Pilih Tahun</option>
                                                                @for ($i = 0; $i <= 10; $i++) <option
                                                                    value="{{ $tahunSekarang - $i }}">{{
                                                                    $tahunSekarang - $i }}</option>
                                                                    @endfor
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control currency-input"
                                                                required>
                                                            <input type="hidden" name="saldo[]" class="saldo-hidden">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="HapusRow(this)">X</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4">
                                                            <button type="button"
                                                                class="btn btn-primary d-block text-left"
                                                                onclick="TambahRow()">+ Tambah
                                                                Baris</button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" form="formSaldo" class="btn btn-primary">Simpan</button>
                                    </div>
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
                            <tr>
                                <th>No</th>
                                <th>Department</th>
                                <th>Tahun</th>
                                <th>Saldo Awal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->Department->nama }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ currency($item->saldo_awal) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ url('/saldo-awal/edit/', $item->id) }}"
                                            class="btn btn-sm btn-primary ">
                                            Edit
                                            {{-- <i class="btn-icon-prepend" data-feather="edit"></i> --}}
                                        </a>
                                        <a href="{{ url('/saldo-awal/hapus/', $item->id) }}"
                                            class="btn btn-sm btn-danger ">
                                            Hapus
                                            {{-- <i class="btn-icon-prepend" data-feather="trash"></i> --}}
                                        </a>
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
        const hiddenInput = input.closest("td").querySelector(".saldo-hidden");
        hiddenInput.value = value;
    }

    function TambahRow() {
        const table = document.querySelector("#table-create tbody");
        const newRow = table.rows[0].cloneNode(true);

        newRow.querySelectorAll("select").forEach(el => el.value = "");
        newRow.querySelectorAll(".currency-input").forEach(el => {
            el.value = "";
            el.addEventListener("input", function () {
                formatRupiahInput(this);
            });
        });
        newRow.querySelectorAll(".saldo-hidden").forEach(el => el.value = "");

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