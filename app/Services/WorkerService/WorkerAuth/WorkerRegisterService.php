<?php

namespace App\Services\WorkerService\WorkerAuth;

use App\Mail\VerificationEmail;
use App\Models\worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class WorkerRegisterService
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

    public function store($data, $request)
    {
        $worker = $this->model->create(array_merge(
            $data->validated(),
            [
                'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('workers')
            ]
        ));
        return $worker->email;
    }
    public function generateToken($email)
    {

        $token = substr(md5(rand(0, 9) . $email . time()), 0, 32);

        $worker = $this->model->where('email', $email)->first();
        $worker->verification_token = $token;
        $worker->save();
        return $worker;
    }


    public function sendEmail($worker)
    {

        Mail::to($worker->email)->send(new VerificationEmail($worker));
    }


    public function register($request)
    {
        try {

            DB::beginTransaction();
            $data = $this->validation($request);

            $email = $this->store($data, $request);

            $storetoken = $this->generateToken($email);

            $this->sendEmail($storetoken);

            DB::commit();
            return response()->json([
                'message' => 'account created successfuly'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}