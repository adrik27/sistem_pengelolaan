<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwal;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoAwalController extends Controller
{
    public function tampil_saldo_awal()
    {
        $datas = SaldoAwal::query()->with('Department')->get();
        $departments = Department::where('status', 'aktif')->whereNot('id', 1)->get();
        return view('Admin.SaldoAwal.tampil_saldo_awal',[
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
            'tahun.*'         => 'required|integer|min:2000',
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
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        if (!empty($data)) {
            DB::table('saldo_awals')->insert($data);
        }

        return redirect()->back()->with('success', 'Saldo awal berhasil ditambahkan.');
    }
}
