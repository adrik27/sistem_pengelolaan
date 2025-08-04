@extends('templates.master')

@section('css')
    <style>
        /* A little custom style to match the clean look */
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .btn-green {
            background-color: #e6f7f0;
            border-color: #a3e9d4;
            color: #0d6efd;
            font-weight: 500;
        }

        .btn-green:hover {
            background-color: #d1f3e5;
            border-color: #89ddc3;
            color: #0a58ca;
        }

        .total-footer {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .form-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection


@section('content')
    <div class="row">
        <div class="container">
            <div class="card p-4">
                <div class="card-body">
                    <form id="formUpdate" action="{{ url('/penerimaan/update/' . $data->id) }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="bookingDay" class="col-sm-4 col-form-label">TANGGAL PEMBUKUAN</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="23" id="bookingDay"
                                                name="bookingDay" value="{{ $data->tanggal_pembukuan->format('d') }}">
                                            <select class="form-select" id="bookingMonth" name="bookingMonth">
                                                <option value="01"
                                                    {{ $data->tanggal_pembukuan->format('m') == '01' ? 'selected' : '' }}>
                                                    Januari</option>
                                                <option value="02"
                                                    {{ $data->tanggal_pembukuan->format('m') == '02' ? 'selected' : '' }}>
                                                    Februari</option>
                                                <option value="03"
                                                    {{ $data->tanggal_pembukuan->format('m') == '03' ? 'selected' : '' }}>
                                                    Maret</option>
                                                <option value="04"
                                                    {{ $data->tanggal_pembukuan->format('m') == '04' ? 'selected' : '' }}>
                                                    April</option>
                                                <option value="05"
                                                    {{ $data->tanggal_pembukuan->format('m') == '05' ? 'selected' : '' }}>
                                                    Mei</option>
                                                <option value="06"
                                                    {{ $data->tanggal_pembukuan->format('m') == '06' ? 'selected' : '' }}>
                                                    Juni</option>
                                                <option value="07"
                                                    {{ $data->tanggal_pembukuan->format('m') == '07' ? 'selected' : '' }}>
                                                    Juli</option>
                                                <option value="08"
                                                    {{ $data->tanggal_pembukuan->format('m') == '08' ? 'selected' : '' }}>
                                                    Agustus</option>
                                                <option value="09"
                                                    {{ $data->tanggal_pembukuan->format('m') == '09' ? 'selected' : '' }}>
                                                    September</option>
                                                <option value="10"
                                                    {{ $data->tanggal_pembukuan->format('m') == '10' ? 'selected' : '' }}>
                                                    Oktober</option>
                                                <option value="11"
                                                    {{ $data->tanggal_pembukuan->format('m') == '11' ? 'selected' : '' }}>
                                                    November</option>
                                                <option value="12"
                                                    {{ $data->tanggal_pembukuan->format('m') == '12' ? 'selected' : '' }}>
                                                    Desember</option>
                                            </select>
                                            <input type="text" class="form-control" placeholder="2025" id="bookingYear"
                                                name="bookingYear" value="{{ $data->tanggal_pembukuan->format('Y') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="supplier" class="col-sm-4 col-form-label">SUPPLIER</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="supplier" name="supplier"
                                            value="{{ $data->supplier }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="invoiceNo" class="col-sm-4 col-form-label">NO. FAKTUR</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="invoiceNo" name="no_faktur"
                                            value="{{ $data->no_faktur }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="receiptStatus" class="col-sm-4 col-form-label">STATUS PENERIMAAN</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="receiptStatus" name="status_penerimaan">
                                            <option value=""{{ $data->status_penerimaan == '' ? 'selected' : '' }}>
                                                Pilih Status</option>
                                            <option value="Pembelian"
                                                {{ $data->status_penerimaan == 'Pembelian' ? 'selected' : '' }}>Pembelian
                                            </option>
                                            <option value="Hibah"
                                                {{ $data->status_penerimaan == 'Hibah' ? 'selected' : '' }}>
                                                Hibah</option>
                                            <option value="Mutasi"
                                                {{ $data->status_penerimaan == 'Mutasi' ? 'selected' : '' }}>Mutasi
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="noteNo" class="col-sm-4 col-form-label">NO NOTA</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="noteNo" name="no_nota"
                                            value="{{ $data->no_nota }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="receiveNo" class="col-sm-4 col-form-label">NO. TERIMA</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="receiveNo" name="no_terima"
                                            value="{{ $data->no_terima }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fundingSource" class="col-sm-4 col-form-label">SUMBER DANA</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="fundingSource" name="sumber_dana">
                                            <option value="" {{ $data->sumber_dana == '' ? 'selected' : '' }}>Pilih
                                                Sumber Dana</option>
                                            <option value="DAU/APBD"
                                                {{ $data->sumber_dana == 'DAU/APBD' ? 'selected' : '' }}>
                                                DAU/APBD</option>
                                            <option value="BLUD" {{ $data->sumber_dana == 'BLUD' ? 'selected' : '' }}>
                                                BLUD</option>
                                            <option value="BOK" {{ $data->sumber_dana == 'BOK' ? 'selected' : '' }}>BOK
                                            </option>
                                            <option value="BOS" {{ $data->sumber_dana == 'BOS' ? 'selected' : '' }}>BOS
                                            </option>
                                            <option value="Droping"
                                                {{ $data->sumber_dana == 'Droping' ? 'selected' : '' }}>
                                                Droping</option>
                                            <option value="Hibah" {{ $data->sumber_dana == 'Hibah' ? 'selected' : '' }}>
                                                Hibah</option>
                                            <option value="Lain - Lain"
                                                {{ $data->sumber_dana == 'Lain - Lain' ? 'selected' : '' }}>Lain - Lain
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="keterangan" class="col-sm-4 col-form-label">KETERANGAN</label>
                                    <div class="col-sm-8">
                                        <textarea name="keterangan" id="keterangan" class="form-control">{!! $data->keterangan !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <hr class="my-4">

                        <div class="table-responsive mt-4">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">NAMA BARANG (SATUAN)</th>
                                        <th scope="col" class="text-center">QTY</th>
                                        <th scope="col" class="text-center">HARGA SATUAN</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <tr>
                                        <td>
                                            <select name="kode_barang" id="NamaBarang"
                                                class="form-control nama-barang-select">
                                                @if ($data->barang)
                                                    <option value="{{ $data->barang->id }}" selected>
                                                        {{ $data->barang->nama_barang }} -
                                                        ({{ $data->barang->kode_barang }})
                                                    </option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="qty" name="qty"
                                                value="{{ $data->qty }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="harga_satuan"
                                                name="harga_satuan" value="{{ $data->harga_satuan }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" form="formUpdate" class="btn btn-sm btn-primary"
                                    onclick="updateform(this)">Update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // fungsi update
        $(document).ready(function() {
            $('.nama-barang-select').each(function() {
                const $select = $(this);

                $select.select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(document.body),
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

        function updateform(button) {
            event.preventDefault();

            const form = document.getElementById(button.getAttribute("form"));

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: "Apakah anda yakin ingin melakukan update ini ?",
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
    </script>
@endsection
