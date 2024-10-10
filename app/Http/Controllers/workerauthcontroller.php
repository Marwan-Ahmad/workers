<?php

namespace App\Http\Controllers;

use App\Models\worker;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\WorkerService\WorkerAuth\WorkerLoginService;
use App\Services\WorkerService\WorkerAuth\WorkerRegisterService;

class workerauthcontroller extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register', 'verify']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // if (! $token = auth()->guard('worker')->attempt($validator->validated())) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $this->createNewToken($token);

        return (new WorkerLoginService())->login($request);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|between:2,100',
        //     'email' => 'required|string|email|max:100|unique:workers',
        //     'password' => 'required|string|min:6',
        //     'phone'=>'required|string|max:10',
        //     'photo'=>'required|image|mimes:jpg,png,jpeg',
        //     'location'=>'required|string'
        // ]);

        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }

        // $user = worker::create(array_merge(
        //             $validator->validated(),
        //             [
        //                 'password' => bcrypt($request->password),
        //                 'photo'=>$request->file('photo')->store('workers')
        //                 ]
        //         ));

        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $user
        // ], 201);


        return (new WorkerRegisterService())->register($request);
    }


    public function verify($token)
    {

        $worker = worker::where('verification_token', $token)->first();

        if (!$worker) {
            return response()->json([
                'message' => 'Your account is invalied'
            ]);
        }

        $worker->verification_token = null;
        $worker->verified_at = now();
        $worker->save();
        return response()->json([
            'message' => 'your account is verfied'
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('worker')->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->guard('worker')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }
}
