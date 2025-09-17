<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pemeriksaan Fisik Persediaan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .container { width: 90%; margin: auto; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-capitalize { text-transform: capitalize; }
        h4, h5 { margin: 0; }
        .header { margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { text-align: center; }
        .no-border, .no-border td { border: none; }
        .signature-table { margin-top: 3rem; }
        .signature-box { width: 50%; }

        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center header">
            <h4>BERITA ACARA PEMERIKSAAN FISIK PERSEDIAAN</h4>
            <h5>BERDASARKAN SALDO TERAKHIR</h5>
            <h5>DINAS PERTANIAN DAN PANGAN KABUPATEN KUDUS</h5>
        </div>

        <p>Pada hari ini, {{ $tanggalCetakFormatted }} yang bertanda tangan di bawah ini:</p>
        
        <table class="no-border" style="width: 50%; margin-left: 2rem;">
            <tr>
                <td style="width: 30%;">Nama</td>
                <td>: {{ $pejabat['pengguna_barang']->nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{ $pejabat['pengguna_barang']->nip }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $pejabat['pengguna_barang']->jabatan }}</td>
            </tr>
        </table>
        
        <p>Mengadakan Pemeriksaan Fisik persediaan pada Dinas Pertanian Dan Pangan Kabupaten Kudus melakukan pemeriksaan terhadap:</p>

        <table class="no-border" style="width: 50%; margin-left: 2rem;">
             <tr>
                <td style="width: 30%;">Nama</td>
                <td>: {{ $pejabat['pengurus_barang']->nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{ $pejabat['pengurus_barang']->nip }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $pejabat['pengurus_barang']->jabatan }}</td>
            </tr>
        </table>

        <p>Menyatakan bahwa telah melakukan Pemeriksaan Fisik Barang Persediaan sebagaimana tercantum dalam Lampiran Berita Acara Pemeriksaan Fisik Barang Persediaan.</p>

        <p>Adapun total nilai Barang Persediaan per tanggal {{ $tanggalAkhirFormatted }} sebesar **Rp. {{ number_format($grandTotal, 2, ',', '.') }}** ({{ ucwords($grandTotalTerbilang) }}).</p>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kelompok Barang / Nama Barang</th>
                    <th>Satuan</th>
                    <th>Qty Sisa</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedPersediaan as $kode_kelompok => $items)
                    <tr style="background-color: #f2f2f2;">
                        <td><strong>{{ $kode_kelompok }}</strong></td>
                        <td colspan="5"><strong>{{ $items->first()->nama_kelompok ?? 'Kelompok Barang' }}</strong></td>
                    </tr>
                    @php $subTotal = 0; @endphp
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-center">{{ $item->satuan }}</td>
                            <td class="text-center">{{ number_format($item->qty_sisa, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($item->qty_sisa * $item->harga_satuan, 2, ',', '.') }}</td>
                        </tr>
                        @php $subTotal += ($item->qty_sisa * $item->harga_satuan); @endphp
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-end"><strong>JUMLAH</strong></td>
                        <td class="text-end"><strong>{{ number_format($subTotal, 2, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
                <tr style="background-color: #e3e3e3;">
                    <td colspan="5" class="text-end"><strong>JUMLAH TOTAL KESELURUHAN</strong></td>
                    <td class="text-end"><strong>{{ number_format($grandTotal, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <table class="no-border signature-table">
            <tr>
                <td class="text-center signature-box">
                    <p>Yang diperiksa,<br>(Pengurus Barang)</p>
                    <br><br><br>
                    <p><strong><u>{{ $pejabat['pengurus_barang']->nama }}</u></strong><br>{{ $pejabat['pengurus_barang']->pangkat }}<br>NIP. {{ $pejabat['pengurus_barang']->nip }}</p>
                </td>
                <td class="text-center signature-box">
                    <p>Kudus, {{ $tanggalCetakSingkat }}<br>Yang memeriksa,<br>(Pengguna Barang)</p>
                    <br><br><br>
                    <p><strong><u>{{ $pejabat['pengguna_barang']->nama }}</u></strong><br>{{ $pejabat['pengguna_barang']->pangkat }}<br>NIP PPK. {{ $pejabat['pengguna_barang']->nip }}</p>
                </td>
            </tr>
        </table>
         <button onclick="window.print()" class="no-print" style="margin-top: 2rem; padding: 10px 20px;">Cetak</button>
    </div>
</body>
</html>