<?php

namespace App\filter;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class postfilter
{
    public function filter()
    {
        return
            [
                'price',
                'content',
                'worker.name',
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('price', 'like', "%{$value}%")
                        ->orWhere('content', 'like', "%{$value}%")
                        ->orWhereHas('worker', function (Builder $query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                        });
                }),
            ];
    }
}
