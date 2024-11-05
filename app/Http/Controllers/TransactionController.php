<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction =transaction::with('client')->get();
        return response()->json($transaction, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'montant' => 'required|integer|min:100',
            'longitude' => 'required|integer',
            'latitude' => 'required|integer',
            'id_user' => 'required|integer|exists:users,id_user',
            'id_client' => 'required|string|exists:client,id_client',
        ]);
//        $fields['password'] = bcrypt($fields['password']);
        $fields = array_merge($fields,["id_transaction" => $this ->generateID(), "type_trans"=>"Contrib"]);
        $transaction = transaction::create($fields);
        return $transaction;
    }

    /**
     * Display the specified resource.
     */
    public function show(transaction $transaction)
    {
        return response()->json($transaction, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transaction $transaction)
    {
        $fields=$request->validate([
            'montant' => 'required|integer|min:100'
        ]);
//        $fields['password'] = bcrypt($fields['password']);
        $transaction->update($fields);
        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transaction $transaction)
    {
        $transaction->delete();
        return ["message"=> "Client has been deleted"];
    }

    function generateID()
    {
        $datejour = Carbon::now()->format('ymd');
        $id = 'T'.rand(100,999).$datejour;
        $transaction= Transaction::where('id_transaction', $id)->first();
        if($transaction){
            return $this->generateID();
        }
        return $id;
    }
}
