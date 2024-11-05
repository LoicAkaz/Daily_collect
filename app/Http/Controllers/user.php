<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User as UserModel;
use Illuminate\Support\Facades\Validator as Validator;

class user extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = UserModel::all();
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(UserModel $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserModel $user)
    {
        $fields=$request->validate([
            'nom' => 'required|string|max:255',
            'sexe' => 'required|string|max:1',
            'prenom' => 'required|string|max:255',
            'age' => 'required|integer|max:100',
            'telephone' => 'required|string||max:15',
            'username' => 'required|string||max:25',
            'password' => 'required|string||min:8',
        ]);
//        $fields['password'] = bcrypt($fields['password']);
        $user->update($fields);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserModel $user)
    {
        $user->delete();
        return ["message"=> "User has been deleted"];
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
