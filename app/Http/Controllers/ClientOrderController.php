<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientOrderRequest;
use App\Interfaces\CrudRepoInterfaceInterface;
use App\Models\ClientOrder;
use App\Models\post;
use App\Repository\ClientOrderRepo;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    protected $crudrepo;

    public function __construct(ClientOrderRepo $crudrepo)
    {

        $this->crudrepo = $crudrepo;
    }
    public function addorder(ClientOrderRequest $request)
    {
        return $this->crudrepo->store($request);
    }
    public function workerorder()
    {
        return $this->crudrepo->show();
    }

    public function updatestatusorder($id, Request $request)
    {
        // $request->validate([
        //     'status' => 'required'
        // ]);
        // $order = ClientOrder::query()->findOrFail($id);

        // $order->setAttribute("status", $request->status)->save();

        // return response()->json([
        //     "message" => "updated successfuly"
        // ]);

        return $this->crudrepo->update($id, $request);
    }
}
