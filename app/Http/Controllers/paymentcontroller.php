<?php

namespace App\Http\Controllers;

use App\Models\client;
use Illuminate\Http\Request;

class paymentcontroller extends Controller
{
    public function pay()
    {


        // return response()->json([
        //     'paylink' => client::first()->charge(100, 'Action Figure')
        // ]);
        return view('pay', [
            'paylink' => client::first()->charge(100, 'Action Figure')
        ]);
    }
}
