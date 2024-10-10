<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'content',
        'price',
        'status',
        'rejected_reason',
    ];

    public function worker()
    {
        return $this->belongsTo(worker::class);
    }
    public function reviews()
    {
        return $this->hasMany(WorkerReview::class);
    }
}
