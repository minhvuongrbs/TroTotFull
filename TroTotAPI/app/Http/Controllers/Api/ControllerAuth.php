<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\GroupAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ControllerAuth extends ApiController
{
  public function register(Request $request)
  {
    // echo "test register";
      $data = $request->only('name', 'email', 'phone', 'username', 'address');
      $credentials = $request->only('email', 'password');
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'username' => 'required|string|max:255|unique:users',
          'address' => 'required|string|max:255',
          'phone' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:6|confirmed'
      ]);
      if ($validator->fails()) {
          return $this->setStatusCode(400)->setErrors($validator->messages())->withError($validator->messages()->first());
      }else{
          try{
              $data['password'] = bcrypt($request->password);
              $user=User::create($data);
              GroupAccount::create(['group_id'=>3,'user_id'=>$user->id]);
              $token = $this->guard()->attempt($credentials);
              return $this->respondWithToken($token);
          }catch (\Exception $e){
              return $this->setStatusCode(500)->withError($e->getMessage());
          }
      }
  }
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(400)->setErrors($validator->messages())->withError($validator->messages()->first());
        }else {
            $credentials = $request->only('email', 'password');

            if(isset($request->loginType) && $request->loginType == 'web'){
                if ($token = $this->guard('web')->attempt($credentials)) {
                    return $this->respondWithToken($token);
                }
            }else{
                if ($token = $this->guard()->attempt($credentials)) {
                    return $this->respondWithToken($token);
                }
            }


            return $this->setStatusCode(400)->withError(trans('auth.failed'));
        }
  }
}
