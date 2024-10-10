<?php

namespace App\Services\WorkerService\WorkerAuth;

use App\Models\worker;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class WorkerLoginService
{

    protected $model;

    function __construct()
    {
        $this->model = new worker();
    }
    public function validation($request)
    {
        $validator   =   Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $validator;
    }

    public function isvaliddata($data)
    {
        if (! $token = auth()->guard('worker')->attempt($data->validated())) {
            return response()->json(['error' => 'unvalid data'], 401);
        }

        return $token;
    }

    public function getstatus($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $status = $worker->status;


        //   if($status<=0){
        //     return response()->json([
        //         'error'=> 'your account is pending'
        //     ]);
        //    }
        return $status;
    }
    public function isverified($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $verified_at = $worker->verified_at;




        return $verified_at;
    }


    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }


    public function login($request)
    {

        $data =  $this->validation($request);

        $token  =   $this->isvaliddata($data);

        if ($this->isverified($request->email) == Null) {
            return response()->json([
                'error' => 'your account is not active'
            ], 422);
        } else if ($this->getstatus($request->email) == 0) {
            return response()->json([
                'error' => 'your account is pending'
            ], 422);
        }



        return $this->createNewToken($token);
    }
}