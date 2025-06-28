<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function tampil_dashboard()
    {

        $departments = Department::where('status', 'aktif')
            ->whereNot('id', 1)
            ->get();

        return view('Admin.Dashboard.dashboard_admin', [
            'departments' => $departments
        ]);
    }


    public function getDatas()
    {
        if (Auth::user()->jabatan_id == 3) { // pengguna barang
            $SaldoAwal = SaldoAwal::where('department_id', Auth::user()->department_id)
                ->where('tahun', date('Y'))
                ->first();

            $penambahan = Transaksi::where('department_id', Auth::user()->department_id)
                ->whereYear('tgl_transaksi', date('Y'))
                ->where('status', 'selesai')
                ->where('jenis_transaksi', 'masuk')
                ->sum('total_harga');

            $pengeluaran = Transaksi::where('department_id', Auth::user()->department_id)
                ->whereYear('tgl_transaksi', date('Y'))
                ->where('status', 'selesai')
                ->where('jenis_transaksi', 'keluar')
                ->sum('total_harga');


            return response()->json([
                'LimitSaldo'    => $SaldoAwal ? $SaldoAwal->saldo_awal : 0,
                'Penambahan'    => $penambahan ?? 0,
                'Pengurangan'   => $pengeluaran ?? 0,
            ]);
        } else { // pengurus barang
            $departments = Department::where('status', 'aktif')
                ->whereNot('id', 1)
                ->get();

            $penambahan = [];
            $pengeluaran = [];
            $saldoawal = [];
            $saldoterpakai = [];
            $sisasaldo = [];
            foreach ($departments as $key => $value) {
                // proses transaksi
                $penambahan[$value->id] = Transaksi::where('department_id', $value->id)
                    ->whereYear('tgl_transaksi', date('Y'))
                    ->where('status', 'selesai')
                    ->where('jenis_transaksi', 'masuk')
                    ->sum('total_harga');

                $pengeluaran[$value->id] = Transaksi::where('department_id', $value->id)
                    ->whereYear('tgl_transaksi', date('Y'))
                    ->where('status', 'selesai')
                    ->where('jenis_transaksi', 'keluar')
                    ->sum('total_harga');

                // proses saldo card
                $DataSaldoAwal = SaldoAwal::where('department_id', $value->id)
                    ->where('tahun', date('Y'))
                    ->first();

                $saldoawal[$value->id] = ($DataSaldoAwal->saldo_awal ?? 0);
                $saldoterpakai[$value->id] = ($DataSaldoAwal->saldo_digunakan ?? 0);
                $sisasaldo[$value->id] = ($DataSaldoAwal->saldo_awal ?? 0) - ($DataSaldoAwal->saldo_digunakan ?? 0);

                // proses saldo table
            }

            $SaldoAwalTable = SaldoAwal::query()->with('Department')->get();


            return response()->json([
                'penambahan' => $penambahan ?? 0,
                'pengeluaran' => $pengeluaran ?? 0,
                'saldoawal' => $saldoawal,
                'saldoterpakai' => $saldoterpakai,
                'sisasaldo' => $sisasaldo,
                'SaldoAwalTable' => $SaldoAwalTable,
            ]);
        }
    }
}
