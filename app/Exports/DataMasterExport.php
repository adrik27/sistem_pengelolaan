<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

// jika export pdf
class DataMasterExport implements FromView
// // jika export excel
// class DataMasterExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($datas)
    {
        $this->data = $datas;
    }

    // jika export pdf
    public function view(): View
    {
        return view('Admin.DataMaster.export', [
            'datas' => $this->data
        ]);
    }

    // // jika export excel
    // public function collection()
    // {
    //     return collect($this->data)->map(function ($item, $index) {
    //         return [
    //             $index,
    //             $item->kode_barang,
    //             $item->nama,
    //             $item->satuan,
    //             $item->qty_awal,
    //             $item->harga,
    //             $item->qty_awal * $item->harga,
    //         ];
    //     });
    // }

    // public function headings(): array
    // {
    //     // Header baris ke-2 (yang tampil di atas data)
    //     return [
    //         'No',
    //         'Kode Barang',
    //         'Nama Barang',
    //         'Satuan',
    //         'Qty',
    //         'Harga',
    //         'Nilai'
    //     ];
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet;

    //             // Baris 1
    //             $sheet->setCellValue('A1', 'No');
    //             $sheet->setCellValue('B1', 'Kode Barang');
    //             $sheet->setCellValue('C1', 'Nama Barang');
    //             $sheet->setCellValue('D1', 'Satuan');
    //             $sheet->setCellValue('E1', 'Saldo Awal');

    //             // Merge cell
    //             $sheet->mergeCells('E1:G1'); // Saldo Awal
    //             $sheet->mergeCells('A1:A2');
    //             $sheet->mergeCells('B1:B2');
    //             $sheet->mergeCells('C1:C2');
    //             $sheet->mergeCells('D1:D2');

    //             // Baris 2
    //             $sheet->setCellValue('E2', 'Qty');
    //             $sheet->setCellValue('F2', 'Harga');
    //             $sheet->setCellValue('G2', 'Nilai');

    //             // Gaya dan border
    //             $sheet->getStyle('A1:G2')->applyFromArray([
    //                 'font' => ['bold' => true],
    //                 'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    //                 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
    //             ]);
    //         },
    //     ];
    // }
}
