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
                    <form id="mainReceiptForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="bookingDay" class="col-sm-4 col-form-label">TANGGAL PEMBUKUAN</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="{{ date('d') }}"
                                                id="bookingDay" name="bookingDay" onchange="Filtering()">
                                            <select class="form-select" id="bookingMonth" name="bookingMonth"
                                                onchange="Filtering()">
                                                <option {{ date('m') == 1 ? 'selected' : '' }}>Januari</option>
                                                <option {{ date('m') == 2 ? 'selected' : '' }}>Februari</option>
                                                <option {{ date('m') == 3 ? 'selected' : '' }}>Maret</option>
                                                <option {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                                                <option {{ date('m') == 5 ? 'selected' : '' }}>Mei</option>
                                                <option {{ date('m') == 6 ? 'selected' : '' }}>Juni</option>
                                                <option {{ date('m') == 7 ? 'selected' : '' }}>Juli</option>
                                                <option {{ date('m') == 8 ? 'selected' : '' }}>Agustus</option>
                                                <option {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                                                <option {{ date('m') == 10 ? 'selected' : '' }}>Oktober</option>
                                                <option {{ date('m') == 11 ? 'selected' : '' }}>November</option>
                                                <option {{ date('m') == 12 ? 'selected' : '' }}>Desember</option>
                                            </select>
                                            <input type="text" class="form-control" placeholder="{{ date('Y') }}"
                                                id="bookingYear" name="bookingYear" onchange="Filtering()">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="supplier" class="col-sm-4 col-form-label">SUPPLIER</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="supplier" name="supplier"
                                            onchange="Filtering()">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="invoiceNo" class="col-sm-4 col-form-label">NO. FAKTUR</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="invoiceNo" name="invoiceNo"
                                            onchange="Filtering()">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="receiptStatus" class="col-sm-4 col-form-label">STATUS PENERIMAAN</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="receiptStatus" name="receiptStatus"
                                            onchange="Filtering()">
                                            <option value="">Pilih Status</option>
                                            <option value="Pembelian">Pembelian</option>
                                            <option value="Hibah">Hibah</option>
                                            <option value="Mutasi">Mutasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="noteNo" class="col-sm-4 col-form-label">NO NOTA</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="noteNo" name="noteNo"
                                            onchange="Filtering()">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="receiveNo" class="col-sm-4 col-form-label">NO. TERIMA</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="receiveNo" name="receiveNo"
                                            onchange="Filtering()">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fundingSource" class="col-sm-4 col-form-label">SUMBER DANA</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="fundingSource" name="fundingSource"
                                            onchange="Filtering()">
                                            <option value="">Pilih Sumber Dana</option>
                                            <option value="DAU/APBD">DAU/APBD</option>
                                            <option value="BLUD">BLUD</option>
                                            <option value="BOK">BOK</option>
                                            <option value="BOS">BOS</option>
                                            <option value="Droping">Droping</option>
                                            <option value="Hibah">Hibah</option>
                                            <option value="Lain - Lain">Lain - Lain</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>



                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-outline-primary" id="btnNewTransaction">Transaksi
                                Baru</button>
                            <button type="button" class="btn btn-green" data-bs-toggle="modal"
                                data-bs-target="#addItemModal">
                                Tambah Item
                            </button>
                        </div>
                        <div>
                            <a href="{{ url('/penerimaan') }}" class="btn btn-outline-warning">Kembali ke Menu
                                Penerimaan</a>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">TANGGAL</th>
                                    <th scope="col">NAMA BARANG (SATUAN)</th>
                                    <th scope="col" class="text-end">QTY</th>
                                    <th scope="col" class="text-end">HARGA SATUAN</th>
                                    <th scope="col" class="text-end">HARGA TOTAL</th>
                                    <th scope="col" class="text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted">tidak ada data</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end total-footer border-0">TOTAL PENERIMAAN BARANG</td>
                                    <td id="grandTotal" class="text-end total-footer border-0">0,00</td>
                                    <td class="border-0"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>




        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Penambahan Item Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addItemForm">
                            <div class="mb-3">
                                <label for="itemName" class="form-label">NAMA BARANG</label>
                                <select class="form-control" name="itemName" id="itemName" style="width: 100%;">
                                </select>
                            </div>
                            <div>
                                <input type="hidden" class="form-control" id="kelompokBarang" name="kelompokBarang"
                                    placeholder="kelompok barang" readonly>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="itemQty" class="form-label">QTY</label>
                                    <input type="text" class="form-control" id="itemQty" name="itemQty"
                                        placeholder="00,00">
                                </div>
                                <div class="col">
                                    <label for="itemPrice" class="form-label">HARGA SATUAN</label>
                                    <input type="text" class="form-control" id="itemPrice" name="itemPrice"
                                        placeholder="0000,00">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="itemDescription" class="form-label">KETERANGAN</label>
                                <textarea class="form-control" id="itemDescription" name="itemDescription" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btnSaveItem">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="btn-text">Simpan Data</span>
                        </button>
                    </div>
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
        // const kelompokBarang = document.getElementById('kelompokBarang'); // Anda bisa hapus ini jika menggunakan jQuery
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        $(document).ready(function() {
            $('#itemName').select2({
                // Tentukan tema yang akan digunakan
                theme: 'bootstrap-5',

                // Targetkan elemen modal sebagai induk dari dropdown
                dropdownParent: $('#addItemModal'),

                // Teks placeholder
                placeholder: 'Ketik untuk mencari barang...',

                // Pengaturan AJAX untuk mengambil data
                ajax: {
                    url: '/seluruh_data_barang', // Route yang dituju
                    dataType: 'json',
                    delay: 250,

                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },

                    // Fungsi untuk memformat data yang diterima dari server
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: `${item.kode_barang} - ${item.nama_barang}`,
                                    // Pastikan data tambahan ini disertakan
                                    kode_kelompok: item.kode_kelompok
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // =======================================================
            // ==          TAMBAHAN: LOGIKA UNTUK MENGISI INPUT       ==
            // =======================================================

            // Event listener saat item dipilih dari Select2
            $('#itemName').on('select2:select', function(e) {
                // Ambil seluruh data dari item yang dipilih
                var data = e.params.data;

                // Cek jika data kode_kelompok ada, lalu isi inputnya
                if (data && data.kode_kelompok) {
                    $('#kelompokBarang').val(data.kode_kelompok);
                }
            });

            // Opsional: Jika Anda ingin input kembali kosong saat pilihan dihapus
            $('#itemName').on('select2:unselect', function(e) {
                $('#kelompokBarang').val('');
            });

            Filtering()
        });


        function Filtering() {
            const day = document.getElementById('bookingDay').value;
            const monthName = document.getElementById('bookingMonth').value;
            const year = document.getElementById('bookingYear').value;

            // Mapping bulan ke angka
            const monthMap = {
                "Januari": "01",
                "Februari": "02",
                "Maret": "03",
                "April": "04",
                "Mei": "05",
                "Juni": "06",
                "Juli": "07",
                "Agustus": "08",
                "September": "09",
                "Oktober": "10",
                "November": "11",
                "Desember": "12"
            };

            const month = monthMap[monthName];
            if (!month || !day || !year) return; // validasi minimal

            const tanggal = `${year}-${month}-${day.padStart(2, '0')}`;

            $.ajax({
                url: `/ambil_penerimaan`,
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_pembukuan: tanggal,
                    supplier: document.getElementById('supplier').value,
                    no_faktur: document.getElementById('invoiceNo').value,
                    status_penerimaan: document.getElementById('receiptStatus').value,
                    no_nota: document.getElementById('noteNo').value,
                    no_terima: document.getElementById('receiveNo').value,
                    sumber_dana: document.getElementById('fundingSource').value,
                },
                success: function(response) {
                    let html = '';
                    let total = 0;

                    if (response.length > 0) {
                        response.forEach((item, index) => {
                            console.log(item);

                            const hargaTotal = item.qty * item.harga_satuan;
                            total += hargaTotal;

                            html += `
                        <tr>
                            <td>
                                ${new Date(item.tanggal_pembukuan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                            </td>
                            <td>${item.nama_barang} (${item.kode_barang})</td>
                            <td class="text-end">${item.qty}</td>
                            <td class="text-end">${formatRupiah(item.harga_satuan)}</td>
                            <td class="text-end">${formatRupiah(hargaTotal)}</td>
                            <td class="text-center">
                                 <button class="btn btn-sm btn-outline-danger btn-delete">Hapus</button>
                            </td>
                        </tr>
                    `;
                        });
                    } else {
                        html = `<tr><td colspan="6" class="text-center text-muted">tidak ada data</td></tr>`;
                    }

                    $('#itemsTableBody').html(html);
                    $('#grandTotal').text(formatRupiah(total));
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan:", error);
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        
            // Get references to elements
            const btnNewTransaction = document.getElementById('btnNewTransaction');
            const mainReceiptForm = document.getElementById('mainReceiptForm');
            const itemsTableBody = document.getElementById('itemsTableBody');
            const grandTotalElement = document.getElementById('grandTotal');

            const btnSaveItem = document.getElementById('btnSaveItem');
            const addItemForm = document.getElementById('addItemForm');
            const addItemModalEl = document.getElementById('addItemModal');
            const addItemModal = new bootstrap.Modal(addItemModalEl);






            btnSaveItem.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah aksi default

                 // --- Aktifkan loader & disable tombol ---
    const spinner = btnSaveItem.querySelector('.spinner-border');
    const btnText = btnSaveItem.querySelector('.btn-text');
    spinner.classList.remove('d-none');
    btnText.textContent = 'Menyimpan...';
    btnSaveItem.disabled = true;

                // Gabungkan data dari form utama dan form modal
                const mainFormData = new FormData(mainReceiptForm);
                const modalFormData = new FormData(addItemForm);

                // Buat objek data untuk dikirim
                let dataToSend = {};
                mainFormData.forEach((value, key) => dataToSend[key] = value);
                modalFormData.forEach((value, key) => dataToSend[key] = value);

                // Tambahkan data yang tidak ada di form secara langsung
                dataToSend['bookingDay'] = document.getElementById('bookingDay').value;
                dataToSend['bookingMonth'] = document.getElementById('bookingMonth').value;
                dataToSend['bookingYear'] = document.getElementById('bookingYear').value;
                dataToSend['receiptStatus'] = document.getElementById('receiptStatus').value;
                dataToSend['fundingSource'] = document.getElementById('fundingSource').value;

                // Dapatkan text dari item yang dipilih (PENTING untuk controller)
                const selectedItem = $('#itemName').select2('data')[0];
                if (selectedItem) {
                    dataToSend['itemNameText'] = selectedItem.text;
                }


                // Kirim data ke server menggunakan Fetch API
                fetch("{{ route('penerimaan.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    })
                    .then(response => response.json())
                    .then(result => {
                        // --- Matikan loader & aktifkan tombol lagi ---
        spinner.classList.add('d-none');
        btnText.textContent = 'Simpan Data';
        btnSaveItem.disabled = false;

                        if (result.success) {
                            // Jika server merespons sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: result.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Ambil data yang dikembalikan server
                            const newItem = result.data;

                            // Hitung total harga item
                            const itemTotal = parseFloat(newItem.qty) * parseFloat(newItem
                                .harga_satuan);

                            // Buat baris baru di tabel HTML
                            const newRow = document.createElement('tr');
                            newRow.innerHTML = `
                        <td>${new Date(newItem.tanggal_pembukuan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                        <td>${newItem.nama_barang} (${newItem.kode_barang})</td>
                        <td class="text-end">${formatCurrency(newItem.qty)}</td>
                        <td class="text-end">${formatCurrency(newItem.harga_satuan)}</td>
                        <td class="text-end item-total">${formatCurrency(itemTotal)}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger btn-delete">Hapus</button>
                        </td>
                    `;

                            // Hapus pesan "tidak ada data" jika ada
                            const noDataRow = itemsTableBody.querySelector('td[colspan="6"]');
                            if (noDataRow) {
                                itemsTableBody.innerHTML = '';
                            }
                            itemsTableBody.appendChild(newRow);

                            // Update grand total
                            updateGrandTotal();

                            // Reset modal & tutup
                            addItemForm.reset();
                            $('#itemName').val(null).trigger('change'); // Reset Select2
                            addItemModal.hide();

                        } else {
                            // Jika ada error validasi dari server
                            let errorMessages = '';
                            for (const key in result.errors) {
                                errorMessages += `${result.errors[key][0]}<br>`;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: errorMessages
                            });
                        }
                    })
                    .catch(error => {
                        // Jika ada error jaringan atau server
                        console.error('Error:', error);

                        spinner.classList.add('d-none');
        btnText.textContent = 'Simpan Data';
        btnSaveItem.disabled = false;
        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Jaringan',
                            text: 'Tidak dapat terhubung ke server.'
                        });
                    });
            });

            // --- Functionality for "Hapus" button using Event Delegation ---
            itemsTableBody.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-delete')) {
                    // Find the row and remove it
                    const rowToRemove = event.target.closest('tr');
                    rowToRemove.remove();

                    // Update the grand total after removal
                    updateGrandTotal();

                    // If table is empty, show "tidak ada data" message again
                    if (itemsTableBody.children.length === 0) {
                        itemsTableBody.innerHTML =
                            '<tr><td colspan="6" class="text-center text-muted">tidak ada data</td></tr>';
                    }
                }
            });


            // --- Helper Functions ---

            // Function to update the grand total
            function updateGrandTotal() {
                let total = 0;
                const itemTotals = itemsTableBody.querySelectorAll('.item-total');
                itemTotals.forEach(td => {
                    total += parseFloat(td.textContent.replace(/\./g, '').replace(',', '.'));
                });
                grandTotalElement.textContent = formatCurrency(total);
            }

            // Function to format numbers as Indonesian currency
            function formatCurrency(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number);
            }
        });
    </script>
    <script>
        // Pastikan DOM sudah siap dimuat
        document.addEventListener('DOMContentLoaded', function() {

            // Ambil elemen tombol dan form berdasarkan ID-nya
            const btnNewTransaction = document.getElementById('btnNewTransaction');
            const mainReceiptForm = document.getElementById('mainReceiptForm');

            // Tambahkan event listener untuk 'click' pada tombol
            btnNewTransaction.addEventListener('click', function() {
                // Tampilkan notifikasi konfirmasi (menggunakan SweetAlert jika ada)
                Swal.fire({
                    title: 'Mulai Transaksi Baru?',
                    text: "Semua data pada form ini akan dikosongkan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, mulai baru!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    // Jika user menekan tombol "Ya"
                    if (result.isConfirmed) {
                        // Gunakan fungsi reset() bawaan dari form
                        mainReceiptForm.reset();

                        // (Opsional) Tampilkan pesan sukses
                        Swal.fire(
                            'Berhasil!',
                            'Form telah direset. Siap untuk transaksi baru.',
                            'success'
                        );
                    }
                });
            });

        });
    </script>
@endsection
