<?php

namespace App\Http\Controllers;

use App\Exports\WorkerExport;
use App\Imports\postImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Exportcontroller extends Controller
{
    public function export()
    {
        return Excel::download(new WorkerExport, 'workerpost.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new postImport, request()->file('excilefile'), null, \Maatwebsite\Excel\Excel::XLSX);
        return response()->json([
            "message" => "Excel File Has been added successfuly"
        ]);
    }
}
