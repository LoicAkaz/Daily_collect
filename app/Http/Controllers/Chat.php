<?php

namespace App\Http\Controllers;

use App\Models\chat as chatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Chat extends Controller
{
    public function store(Request $request)
    {
//        return response()->json($request, 200);
        $fields = $request->validate([
            'participant1' => 'required|string|max:255',
            'participant2' => 'required|string|max:255',
        ]);
        $participant1 = $request->input('participant1');
        $participant2 = $request->input('participant2');
//        $client = Client::create($fields);
        $chat = DB::table('chat')->where('participant1', "=", $participant1)
            ->where('participant2', "=", $participant2)
            ->orWhere('participant1', "=", $participant2)
            ->where('participant2', "=", $participant1);
       if(!$chat){
           $client = chatModel::create($fields);
           return response()->json($client, 200);
       }else{
           return response()->json($chat, 200);
       }

    }
}
