<?php

namespace App\Http\Controllers;

use App\filter\postfilter;
use App\Models\post;
use App\Models\postphoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Posts\storingpostrequest;
use App\Services\PostService\StoringPostService;

class postcontroller extends Controller
{
    function store(storingpostrequest $request)
    {

        return (new StoringPostService())->store($request);
    }

    public function index()
    {
        $post = post::all();
        return response()->json([
            'posts' => $post
        ]);
    }

    // this method for get the approved posts
    public function approved()
    {
        $posts = QueryBuilder::for(post::class)
            ->allowedFilters(
                (new postfilter)->filter()
            )
            ->with('worker:id,name')
            ->where('status', 'approved')
            ->get();
        //$post = post::query()->with('worker:id,name')->where('status', 'approved')->get();
        return response()->json([
            'posts' => $posts
        ]);
    }
}
