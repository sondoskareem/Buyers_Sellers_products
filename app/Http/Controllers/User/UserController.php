<?php

namespace App\Http\Controllers\User;
use App\User;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Illuminate\Contracts\Auth\Factory;
use Tymon\JWTAuth\Facades\JWTAuth;
class UserController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['store','login']]);
    }
    

    public function show(User $user)
    {
        return $this->showOne($user);
    }

    // public function index()
    // {
    //     $users = User::all();
    //     return $this->showAll($users);
    // }
    
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:4',
        ];
        $this->validate($request ,$rules );
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
        $user = User::create($data);

        // $token = JWTAuth::fromUser($user);

        return response()->json(compact('user'),201);
        // return \response()->json(['data' => 'user added successfuly']);

    }

//    
    public function login(Request $request){ 
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return $this->respondWithToken($token);
    }
//
    public function me()
    {
        return response()->json($this->guard()->user());
    }
//
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
//
/**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }
//

    public function update(Request $request,User $user)
    {
        $rules = [
            'email' => 'email|unique:users,email' . $user->id,
            'password' => 'min:6|confirmed',
            // 'user_id' => 'required',
            'admin' => 'in:'. User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];
        // $this->validate($request ,$rules );
        if($request->has('admin')){
            if(!$user->isVerified()){
                // return (['var' =>User::VERIFIED_USER , 'user' =>$user ,'verifief'=>$user->verified, 'result' =>User::VERIFIED_USER ===$user->isVerified()]);
                return $this->errorResponse('only verified users can modify the admin field' ,  409);
            }
            $user->admin = $request->admin;
        }
        if ($request->has('name')){
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email != $request->email){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        if(!$user->isDirty()){
            return  $this->errorResponse('You need to specify a different value' , 409);
        }
        $user->save();

        return $this->showOne($user);
    }

   
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user_id' => JWTAuth::user()->id,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
    }
    public function guard()
    {
        return Auth::guard();
    }
}
