@extends('templates.master')

@section('css')
    {{-- CSS untuk DataTables dan Flatpickr (Date Picker) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Style tambahan agar form lebih rapi */
        .form-filter-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .form-filter-row .form-label {
            min-width: 180px; /* Lebar label agar sejajar */
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4">Laporan Penerimaan Barang</h6>

                {{-- FORM FILTER --}}
                <div id="filter-form">
                    @can('admin')
                    <div class="form-filter-row">
                        <label for="bidang_id" class="form-label">Pilih Bidang</label>
                        <select class="form-select" id="bidang_id" name="bidang_id">
                            <option value="">-- Tampilkan Semua Bidang --</option>
                            @foreach ($bidangList as $bidang)
                                <option value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endcan
                    <div class="form-filter-row">
                        <label for="tanggal_awal" class="form-label">Tanggal Awal Hitung</label>
                        <input type="text" class="form-control date-picker" id="tanggal_awal" name="tanggal_awal" placeholder="dd/mm/yyyy">
                    </div>
                    <div class="form-filter-row">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir Hitung</label>
                        <input type="text" class="form-control date-picker" id="tanggal_akhir" name="tanggal_akhir" placeholder="dd/mm/yyyy">
                    </div>
                    <div class="form-filter-row">
                        <label for="tanggal_cetak" class="form-label">Tanggal Cetak Berita Acara</label>
                        <input type="text" class="form-control date-picker" id="tanggal_cetak" name="tanggal_cetak" placeholder="dd/mm/yyyy">
                    </div>
                    <div class="form-filter-row">
                        <label class="form-label"></label> {{-- Label kosong untuk alignment --}}
                        <div>
                            <button id="filter-btn" class="btn btn-success">Tampilkan Data</button>
                            <button id="cetak-btn" class="btn btn-primary" disabled>Cetak BA</button>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- TABEL UNTUK DATATABLES --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="laporan-penerimaan-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tgl Pembukuan</th>
                                <th>Supplier</th>
                                <th>No. Nota</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Konten akan diisi oleh DataTables melalui AJAX --}}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    {{-- JS untuk jQuery, DataTables, dan Flatpickr --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script> {{-- Bahasa Indonesia untuk kalender --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {
            // 1. Inisialisasi Flatpickr (Date Picker)
            flatpickr(".date-picker", {
                altInput: true,
                altFormat: "d/m/Y", // Format yang dilihat user
                dateFormat: "Y-m-d", // Format yang dikirim ke server
                locale: "id" // Bahasa Indonesia
            });

            // 2. Inisialisasi DataTables
            const table = $('#laporan-penerimaan-table').DataTable({
                processing: true,
                serverSide: false, // Kita gunakan client-side karena data diambil sekaligus
                ajax: {
                    url: "{{ route('laporan.penerimaan.data') }}",
                    type: "GET",
                    // Fungsi untuk mengirim parameter filter tambahan
                    data: function(d) {
                        d.tanggal_awal = $('#tanggal_awal').val();
                        d.tanggal_akhir = $('#tanggal_akhir').val();

                        // Kirim bidang_id jika elemennya ada di halaman
                        if ($('#bidang_id').length) {
                            d.bidang_id = $('#bidang_id').val();
                        }
                        
                        return d;
                    },
                    // Fungsi untuk menangani error
                    error: function(xhr, error, code) {
                         // Kosongkan tabel dan tampilkan notifikasi jika ada error
                        $('#laporan-penerimaan-table').dataTable().fnClearTable();
                        $('#cetak-btn').prop('disabled', true); // Non-aktifkan tombol cetak
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: 'Pastikan rentang tanggal sudah terisi dengan benar.',
                        });
                    }
                },
                // Hentikan proses load data saat halaman pertama kali dibuka
                deferLoading: 0,
                columns: [
                    { data: 'tanggal_pembukuan', name: 'tanggal_pembukuan',
                      render: function(data, type, row) {
                        // Format tanggal menjadi dd-mm-yyyy
                        const date = new Date(data);
                        return ('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear();
                      }
                    },
                    { data: 'supplier', name: 'supplier' },
                    { data: 'no_nota', name: 'no_nota' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'qty', name: 'qty', className: 'text-center' },
                    { data: 'harga_satuan', name: 'harga_satuan', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ') },
                    {
                        data: null,
                        name: 'total_harga',
                        className: 'text-end',
                        render: function(data, type, row) {
                            // Hitung total harga dari qty * harga_satuan
                            const total = parseFloat(row.qty) * parseFloat(row.harga_satuan);
                            return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(total);
                        }
                    }
                ],
                // Aktifkan tombol cetak setelah data berhasil dimuat
                "drawCallback": function( settings ) {
                    const api = this.api();
                    if (api.rows( {page:'current'} ).data().length > 0) {
                        $('#cetak-btn').prop('disabled', false);
                    } else {
                        $('#cetak-btn').prop('disabled', true);
                    }
                }
            });

            // 3. Event handler untuk tombol "Tampilkan Data"
            $('#filter-btn').on('click', function(e) {
                e.preventDefault();
                // Muat ulang data tabel dengan parameter filter baru
                table.ajax.reload();
            });

            // 4. Event handler untuk tombol "Cetak BA"
            $('#cetak-btn').on('click', function(e) {
            e.preventDefault();
            const tglAkhir = $('#tanggal_akhir').val();
            const tglCetak = $('#tanggal_cetak').val();
            let bidangId = '';

            // Cek apakah filter bidang ada dan punya nilai
            if ($('#bidang_id').length) {
                bidangId = $('#bidang_id').val();
            }
            
            // Validasi dasar di frontend
            if (!tglAkhir || !tglCetak) {
                Swal.fire('Input Kurang', 'Harap isi Tanggal Akhir Hitung dan Tanggal Cetak BA.', 'warning');
                return;
            }

            // Buat URL dengan parameter
            const printUrl = `{{ route('laporan.penerimaan.cetak') }}?tanggal_akhir=${tglAkhir}&tanggal_cetak=${tglCetak}&bidang_id=${bidangId}`;

            // Buka URL di tab baru
            window.open(printUrl, '_blank');
        });
        });
    </script>
@endsection