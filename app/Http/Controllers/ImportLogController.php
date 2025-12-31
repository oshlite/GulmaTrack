<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportLog;

class ImportLogController extends Controller
{
    public function getByPeriod(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer',
            'minggu' => 'required|integer',
        ]);

        $import = ImportLog::where([
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'minggu' => $request->minggu,
            'status' => 'success',
        ])->latest()->first();

        if (!$import) {
            return response()->json([
                'success' => false,
                'message' => 'Import log tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $import
        ]);
    }
}
