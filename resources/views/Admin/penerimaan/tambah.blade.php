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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection


@section('content')

<div class="row">
    <div class="container">
        <div class="card p-4">
            <div class="card-body">
                <form id="mainReceiptForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bookingDate" class="form-label">TANGGAL PEMBUKUAN</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="12" id="bookingDay">
                                    <select class="form-select" id="bookingMonth">
                                        <option>Januari</option>
                                        <option>Februari</option>
                                        <option>Maret</option>
                                        <option>April</option>
                                        <option>Mei</option>
                                        <option>Juni</option>
                                        <option>Juli</option>
                                        <option>Agustus</option>
                                        <option>September</option>
                                        <option>Oktober</option>
                                        <option>November</option>
                                        <option selected>Desember</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="2025" id="bookingYear">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="supplier" class="form-label">SUPPLIER</label>
                                <input type="text" class="form-control" id="supplier" value="MAHARANI">
                            </div>
                            <div class="mb-3">
                                <label for="invoiceNo" class="form-label">NO. FAKTUR</label>
                                <input type="text" class="form-control" id="invoiceNo" value="-">
                            </div>
                            <div class="mb-3">
                                <label for="receiptStatus" class="form-label">STATUS PENERIMAAN</label>
                                <input type="text" class="form-control" id="receiptStatus" value="Pembelian">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="noteNo" class="form-label">NO NOTA</label>
                                <input type="text" class="form-control" id="noteNo" value="545436">
                            </div>
                            <div class="mb-3">
                                <label for="receiveNo" class="form-label">NO. TERIMA</label>
                                <input type="text" class="form-control" id="receiveNo" value="43243243">
                            </div>
                            <div class="mb-3">
                                <label for="fundingSource" class="form-label">SUMBER DANA</label>
                                <input type="text" class="form-control" id="fundingSource" value="BOK">
                            </div>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-outline-primary" id="btnNewTransaction">Transaksi Baru</button>
                        <button type="button" class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addItemModal">
                            Tambah Item
                        </button>
                    </div>
                    <div>
                        <a href="#" class="btn btn-outline-warning">Kembali ke Menu Penerimaan</a>
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


    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
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
                            <select class="form-control" id="itemName" style="width: 100%;">
                                </select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="itemQty" class="form-label">QTY</label>
                                <input type="text" class="form-control" id="itemQty" placeholder="00,00">
                            </div>
                            <div class="col">
                                <label for="itemPrice" class="form-label">HARGA SATUAN</label>
                                <input type="text" class="form-control" id="itemPrice" placeholder="0000,00">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="itemDescription" class="form-label">KETERANGAN</label>
                            <textarea class="form-control" id="itemDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSaveItem">Simpan Data</button>
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
    $(document).ready(function() {
    $('#itemName').select2({
        // Tentukan tema yang akan digunakan
        theme: 'bootstrap-5',
        
        // Targetkan elemen modal sebagai induk dari dropdown
        // Ini SANGAT PENTING agar search box di dalam modal berfungsi
        dropdownParent: $('#addItemModal'),

        // Teks placeholder
        placeholder: 'Ketik untuk mencari barang...',

        // Pengaturan AJAX untuk mengambil data
        ajax: {
            url: '/seluruh_data_barang', // Route yang dituju
            dataType: 'json',
            delay: 250, // Jeda sebelum request dikirim setelah user mengetik
            
            // Fungsi untuk memproses data yang akan dikirim ke server
            data: function (params) {
                return {
                    search: params.term // Kirim teks yang diketik user sebagai parameter 'search'
                };
            },
            
            // Fungsi untuk memformat data yang diterima dari server
            processResults: function (data) {
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

            // --- Functionality for "Transaksi Baru" Button ---
            btnNewTransaction.addEventListener('click', function() {
    
                
                alert('Form telah direset. Siap untuk transaksi baru.');
            });


            // --- Functionality for Modal "Simpan Data" Button ---
            btnSaveItem.addEventListener('click', function() {
                // Get values from the modal form
                const itemName = document.getElementById('itemName').value.trim();
                const itemQty = parseFloat(document.getElementById('itemQty').value.replace(',', '.')) || 0;
                const itemPrice = parseFloat(document.getElementById('itemPrice').value.replace(',', '.')) || 0;
                const bookingDate = `${document.getElementById('bookingDay').value}-${document.getElementById('bookingMonth').value.substring(0,3)}-${document.getElementById('bookingYear').value}`;

                // Simple validation
                if (!itemName || itemQty <= 0 || itemPrice <= 0) {
                    alert('Nama Barang, QTY, and Harga Satuan must be filled correctly.');
                    return;
                }

                // Calculate total for the item
                const itemTotal = itemQty * itemPrice;

                // Create a new table row
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${bookingDate}</td>
                    <td>${itemName} (Unit)</td>
                    <td class="text-end">${formatCurrency(itemQty)}</td>
                    <td class="text-end">${formatCurrency(itemPrice)}</td>
                    <td class="text-end item-total">${formatCurrency(itemTotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger btn-delete">Hapus</button>
                    </td>
                `;

                // Add the new row to the table
                const noDataRow = itemsTableBody.querySelector('td[colspan="6"]');
                if (noDataRow) {
                    itemsTableBody.innerHTML = ''; // Clear "tidak ada data" message
                }
                itemsTableBody.appendChild(newRow);

                // Update the grand total
                updateGrandTotal();

                // Reset the modal form and close it
                addItemForm.reset();
                addItemModal.hide();
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
                        itemsTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">tidak ada data</td></tr>';
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
@endsection