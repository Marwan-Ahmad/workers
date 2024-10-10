<?php

namespace App\Imports;

use App\Models\post;
use Maatwebsite\Excel\Concerns\ToModel;

class postImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // foreach ($rows as $row) {
        $post = new post([
            "worker_id" => $row[0],
            "content" => $row[1],
            "price" => $row[2],
            "status" => $row[3],
            "rejected_reason" => $row[4],
            "created_at" => $row[5],
            "updated_at" => $row[6]

        ]);
        // }
        return $post;
    }
}