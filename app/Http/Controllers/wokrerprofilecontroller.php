<?php

namespace App\Http\Controllers;

use App\Http\Requests\Worker\updatingprofilerequest;
use App\Models\post;
use App\Models\worker;
use App\Models\WorkerReview;
use App\Services\UpdatingprofileService;
use Illuminate\Http\Request;

class wokrerprofilecontroller extends Controller
{
    public function workerProfile()
    {
        $workerid = auth()->guard('worker')->id();
        $worker = worker::query()->with('posts.reviews')->find($workerid)->makeHidden(['verification_token', 'verified_at', 'status']);

        $reviews = WorkerReview::query()->whereIn('post_id', $worker->posts()->pluck('id'));
        $rate = round($reviews->sum('rate') / $reviews->count(), 1);
        return response()->json([
            'data' => array_merge($worker->toArray(), ['rate' => $rate])

        ]);
    }

    public function showtoupdate()
    {
        $worker = worker::query()->findOrFail(auth()->guard('worker')->id())->makeHidden(['verification_token', 'verified_at', 'status']);
        return response()->json([
            'data' => $worker
        ]);
    }

    public function updateaftershow(updatingprofilerequest $request)
    {
        return (new UpdatingprofileService)->updata($request);
    }

    public function delete()
    {
        post::query()->where('worker_id', auth()->guard('worker')->id())->delete();
        return response()->json([
            'message' => 'deleted'
        ]);
    }
}
