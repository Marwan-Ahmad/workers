<?php

namespace App\Services\PostService;

use App\Models\admin;
use App\Models\post;
use App\Models\post_photo;
use App\Notifications\AdminPost;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class StoringPostService
{
    protected $model;
    public function __construct()
    {
        $this->model = new post();
    }


    public function adminpercent($price)
    {
        $discount = $price * 0.1;

        $priceafterdiscount = $price - $discount;

        return $priceafterdiscount;
    }


    public function storepost($data)
    {
        $data = $data->except('photos');
        $data['price'] = $this->adminpercent($data['price']);
        $data['worker_id'] = auth()->guard('worker')->id();

        $post = post::create($data);
        return $post;
    }
    public function storepostphotos($request, $postid)
    {
        $postphoto = $request->file('photos');
        // foreach ($postphoto as $photo) {
        post_photo::query()->create([
            'post_id' => $postid,
            'photo' => $request->file('photos')
        ]);
        // }
        // $postphoto = $request->file('photos');
        // foreach ($postphoto as $photo) {
        //     post_photo::query()->create([
        //         'post_id' => $postid,
        //         'photo' => $photo->store('posts')
        //     ]);
        // $postphotos = new post_photo();
        // $postphotos->post_id = $postid;
        // $postphotos->photo = $photo->store('posts');
        // $postphotos->save();
        // }
        // return $postphotos;
    }
    public function sendadminnotification($post)
    {
        $admin = admin::get();
        Notification::send($admin, new AdminPost(auth()->guard('worker')->user(), $post));
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $post = $this->storepost($request);
            if ($request->hasFile("photos")) {
                $this->storepostphotos($request, $post->id);
            }
            $this->sendadminnotification($post);
            DB::commit();
            return response()->json([
                'message' => "post has been created successfuly,your price after discount is {$post->price}",
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}