<?php

namespace App\Http\Controllers;

use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields=$request->validate([
            'nom' => 'required|string|max:255',
            'sexe' => 'required|string|max:1',
            'prenom' => 'required|string|max:255',
            'age' => 'required|integer|max:100',
            'telephone' => 'required|string||max:15|unique:users',
            'username' => 'required|string||max:15|unique:users',
            'password' => 'required|string||min:8',
        ]);
//        $fields['password'] = bcrypt($fields['password']);
        $fields = array_merge($fields,["id_user" => $this ->generateID()]);
        $user = UserModel::create($fields);
        $token = $user->createToken($request->nom)->plainTextToken;
        return [
            "user"=>$user,
            "token"=>$token
        ];
    }
    public function login(Request $request){
        $request->validate([
            'username' => 'required|string||max:15|exists:users',
            'password' => 'required|string||min:8',
        ]);
        $user = UserModel::where('username', $request->username)->first();
        if(!$user ||!Hash::check($request->password, $user->password)){
            return [
                'message'=>'The provided credentials are incorrect.',
            ];
        }
        $token = $user->createToken($user->nom);
        return response()->json(
            [
            'user'=>$user,
            'token'=>$token->plainTextToken
        ], 200);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return [
            'message'=>'You have been logged out.',
        ];
    }
    function generateID()
    {
        $id = rand(100,999);
        $user= UserModel::where('id_user', $id)->first();
        if($user){
            return $this->generateID();
        }
        return $id;
    }
}
