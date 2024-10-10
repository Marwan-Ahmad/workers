<?php

namespace App\Http\Controllers;

use App\Http\Requests\Worker\WorkerReviewRequest;
use App\Http\Resources\WorkerReviewResource;
use App\Models\WorkerReview;
use Illuminate\Http\Request;

class WorkerReviewController extends Controller
{
    public function store(WorkerReviewRequest $request)
    {
        $data = $request->all();
        $data['client_id'] = auth()->guard('client')->user()->id;
        $reviews = WorkerReview::query()->create($data);
        return response()->json([
            'message' => $reviews
        ]);
    }


    public function postrate($id)
    {
        $reviews = WorkerReview::query()->wherePostId($id);
        $avg = $reviews->sum('rate') / $reviews->count();

        return response()->json([
            'total_rate' => round($avg, 1),
            'data' => \App\Http\Resources\Worker\WorkerReviewResource::collection($reviews->get())
        ]);
    }
}