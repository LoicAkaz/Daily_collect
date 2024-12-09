<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Models\message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function sendMessage(Request $request): \Illuminate\Http\JsonResponse
    {
        $envoyeur = $request->input("envoyeur");
        $receptioneur = $request->input("receptioneur");
        $contenu = $request->input("contenu");
        $created_at = $request->input("created_at");
        $fields = $request->validate([
            'contenu' => 'required|string|max:255',
            'envoyeur' => 'required|string|max:255',
            'receptioneur' => 'required|string|max:255',
        ]);
//        $data = [
//        'contenu' : $contenu,
//            ];
        $createMessage = message::create($fields);
        event(new ChatMessageEvent($receptioneur, $envoyeur, $contenu));
        return response()->json($createMessage, 200);
    }

    public function getMessages(Request $request): \Illuminate\Http\JsonResponse
    {
        $envoyeur = $request->input("envoyeur");
        $receptioneur = $request->input("receptioneur");
        $msg = DB::table('messages')
            ->where('envoyeur', "=", $envoyeur)
            ->where('receptioneur', "=", $receptioneur)
            ->orWhere('receptioneur', "=", $envoyeur)
            ->where('envoyeur', "=", $receptioneur)
            ->get();
//        $messages = message::all()->where('envoyeur',"=",$envoyeur)->orWhere('receptioneur',"=",$receptioneur);
        return response()->json($msg, 200);
    }
}
