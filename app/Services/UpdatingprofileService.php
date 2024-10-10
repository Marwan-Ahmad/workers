<?php

namespace App\Services;

use App\Models\post;
use App\Models\worker;
use GuzzleHttp\Psr7\UploadedFile as Psr7UploadedFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\returnSelf;

class UpdatingprofileService
{

    protected $model;
    public function __construct()
    {
        $this->model = worker::query()->find(auth()->guard('worker')->id());
    }

    public function password($data)
    {
        if (request()->has('password')) {
            $data['password'] = Hash::make(request()->password);
            return $data['password'];
        }
        $data['password'] = $this->model->password;
        return $data['password'];
    }

    public function photo($data)
    {
        if (request()->has('photo')) {
            $data['photo'] = (request()->file('phot o') instanceof UploadedFile) ?
                request()->file('photo')->store('workers') : $this->model->photo;
            return $data['photo'];
        }
        $data['photo'] = null;
        return $data['photo'];
    }

    public function updata($request)
    {
        $data = $request->all();
        $data['password'] = $this->password($data);
        $data['photo'] = $this->photo($data);

        $this->model->update($data);
        return response()->json([
            'message' => 'update'
        ]);
    }
}
