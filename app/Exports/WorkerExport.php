<?php

namespace App\Exports;

use App\Models\post;
use App\Models\worker;
use Maatwebsite\Excel\Concerns\FromCollection;

class WorkerExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return post::where('worker_id', 1)->get();
    }
}
