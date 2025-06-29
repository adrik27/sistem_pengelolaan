<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwal;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaldoAwalController extends Controller
{
    public function tampil_saldo_awal()
    {
        if (Auth::user()->jabatan_id == 3) {
            $datas = SaldoAwal::where('department_id', Auth::user()->department_id)
                ->with('User')
                ->with('Department')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $datas = SaldoAwal::query()
                ->with('User')
                ->with('Department')
                ->get();
        }

        $departments = Department::where('status', 'aktif')->whereNot('id', 1)->get();
        return view('Admin.SaldoAwal.tampil_saldo_awal', [
            'datas' => $datas,
            'departments' => $departments,
        ]);
    }


    public function create_saldo_awal(Request $request)
    {
        $request->validate([
            'department_id'   => 'required|array',
            'department_id.*' => 'required|exists:departments,id',
            'tahun'           => 'required|array',
            'tahun.*'         => 'required|integer',
            'saldo'           => 'required|array',
            'saldo.*'         => 'required|numeric|min:0',
        ]);

        $inputClear = [];
        $InputDuplikat = [];

        foreach ($request->department_id as $index => $department_id) {
            $tahun = $request->tahun[$index];
            $key = $department_id . '-' . $tahun;
            $find_department = Department::find($department_id);
            if (in_array($key, $inputClear)) {
                $InputDuplikat[] = "Departemen: $find_department->nama, Tahun: $tahun";
            } else {
                $inputClear[] = $key;
            }
        }

        if (!empty($InputDuplikat)) {
            return redirect()->back()->with('error', 'Terdapat duplikasi input pada: ' . implode(', ', $InputDuplikat));
        }

        // Proses insert jika tidak ada duplikat
        $data = [];

        foreach ($request->department_id as $index => $department_id) {
            $tahun = $request->tahun[$index];

            // Cek duplikat di DB
            $exists = DB::table('saldo_awals')
                ->where('department_id', $department_id)
                ->where('tahun', $tahun)
                ->exists();
            $find_department = Department::find($department_id);

            if ($exists) {
                return redirect()->back()->with('error', "Data dengan Nama Departemen: $find_department->nama dan Tahun: $tahun sudah ada di database.");
            }

            $data[] = [
                'department_id' => $department_id,
                'tahun'         => $tahun,
                'saldo_awal'    => $request->saldo[$index],
                'pembuat_id'    => Auth::user()->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        if (!empty($data)) {
            DB::table('saldo_awals')->insert($data);
        }

        return redirect()->back()->with('success', 'Saldo awal berhasil ditambahkan.');
    }

    public function update_saldo_awal(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'tahun'         => 'required|integer',
            'saldo'         => 'required|min:0',
        ]);

        // cek apakah saldo yang masuk lebih kecil
        $cek_saldo = DB::table('saldo_awals')
            ->select('saldo_awal')
            ->where('id', $id)
            ->first();

        if ($request->saldo <= $cek_saldo->saldo_awal) {
            return redirect()->back()->with('error', "Terdapat kesalahan : Saldo tidak boleh lebih kecil atau sama dengan dari saldo sebelumnya.");
        }

        // Cek duplikat di DB
        $exists = DB::table('saldo_awals')
            ->where('department_id', $request->department_id)
            ->where('tahun', $request->tahun)
            ->where('saldo_awal', $request->saldo)
            ->exists();

        $find_department = Department::find($request->department_id);
        if ($exists) {
            return redirect()->back()->with('error', "Data dengan Nama Departemen: $find_department->nama dan Tahun: $request->tahun sudah ada di database.");
        }

        $data = [
            'department_id' => $request->department_id,
            'tahun'         => $request->tahun,
            'saldo_awal'    => $request->saldo,
            'pembuat_id'    => Auth::user()->id,
            'updated_at'    => now(),
        ];

        SaldoAwal::where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Saldo awal berhasil diperbarui.');
    }

    public function hapus_saldo_awal($id)
    {
        $saldo_awal = SaldoAwal::find($id);
        if ($saldo_awal) {
            SaldoAwal::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Saldo awal berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Saldo awal tidak ditemukan.');
        }
    }
}
