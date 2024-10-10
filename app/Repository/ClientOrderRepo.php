<?php

namespace App\Repository;

use App\Models\ClientOrder;
use App\Interfaces\CrudRepoInterfaceInterface;

class ClientOrderRepo implements CrudRepoInterfaceInterface
{
    function store($request)
    {
        $data = $request->all();
        $data["client_id"] = auth()->guard('client')->user()->id;

        $existbefoar = ClientOrder::where('client_id', $data['client_id'])->where("post_id", $data['post_id'])->first();
        if ($existbefoar) {
            return response()->json([
                'message' => "The Order you request is requested befoar"
            ], 406);
        }

        $order = ClientOrder::create($data);
        return response()->json([
            'message' => 'the order requested successfuly'
        ]);
    }

    function show()
    {
        $clientorder = ClientOrder::query()->with(['client:id,name', 'post:id,content'])->whereStatus('pending')
            ->whereHas("post", function ($query) {
                $query->where('worker_id', auth()->guard('worker')->user()->id);
            })->get();
        return response()->json([
            "order" => $clientorder
        ]);
    }

    function update($id, $request)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $order = ClientOrder::query()->findOrFail($id);

        $order->setAttribute("status", $request->status)->save();

        return response()->json([
            "message" => "updated successfuly"
        ]);
    }
}
