<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostStatusRequest;
use App\Models\post;
use App\Notifications\AdminPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function changestatus(PostStatusRequest $request)
    {
        $post = post::find($request->post_id);
        $post->update([
            "status" => $request->status,
            'rejected_reason' => $request->rejected_reason
        ]);
        Notification::send($post->worker, new AdminPost($post->worker, post: $post));
        return response()->json([
            'message' => 'post has been changes'
        ]);
    }
}