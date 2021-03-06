<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){


        $email = request('email');

        $searchUserNotFound = User::where('email', '=', $email)->get();


        if(count($searchUserNotFound ) === 0)
        {
            return response()->json([
                    "status" => ["error" => "user not found"]
            ], 404);
        }//end if search user

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();

            $success['token'] =  $user->createToken('Laravel_Rest_API')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {

        $email = $request->input('email');

        $searchUserAlreadyExist = User::where('email', '=', $email)->get();


        if(count($searchUserAlreadyExist) >= 1)
        {
           throw new \Exception("User already exist");
        }//end if search User already exist


        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required|email',
            'password'     => 'required',
            'c_password'   => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input              = $request->all();
        $input['password']  = bcrypt($input['password']);
        $user               = User::create($input);
        $success['token']   = $user->createToken('Laravel_Rest_API')-> accessToken;
        $success['name']    = $user->name;

        return response()->json(['success'=>$success], $this-> successStatus);

    }//end function register
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        $user = Auth::user();

        return response()->json(['success' => $user], $this-> successStatus);
    }//end function details


    public function update(Request $request, $id)
    {

        $user = Auth::user();

        if($id != $user->id)
        {
            return response()->json([
                    "status" => ["error" => "Unauthorized"]
            ], 401);
        }//end if validate user update

        $searchUser = User::find($id);


        if($searchUser)
        {
            $newPassword = bcrypt($request->input('password'));

            $dataUpdate = [
                "password" => $newPassword
            ];

            $searchUser->update($dataUpdate);

            return response()->json([
                "status" => ["sucess" => "user edited"]
            ], 201);
        } else {
            return response()->json([
                    "status" => ["error" => "user not found"]
            ], 404);
        }


    }//end function update

    public function delete(Request $request, $id)
    {
        $user = Auth::user();


        if($id != $user->id)
        {
            return response()->json([
                "status" => ["error" => "Unauthorized"]
            ], 401);
        }//end if validate user update

        $searchUser = User::find($id);

        if($searchUser)
        {
            $searchUser->delete();

            return response()->json([
                "status" => ["sucess" => "user deleted"]
            ], 201);
        } else {
            return response()->json([
                "status" => ["error" => "user not found"]
            ], 404);
        }


    }//end function delete
}//end class