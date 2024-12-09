<?php

namespace App\Events;

use App\Models\message;
use DateTime;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

//class ChatMessageEvent implements ShouldBroadcast
//{
//    use Dispatchable, InteractsWithSockets, SerializesModels;
//
//
//}
class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $contenu;
    public string $envoyeur;
    public string $receptioneur;

//    public  $created_at;
    public function __construct($receptioneur,$envoyeur ,$contenu)
    {
        $this->contenu = $contenu;
        $this->envoyeur = $envoyeur;
        $this->receptioneur = $receptioneur;
//        $this->created_at = $created_at;
    }

    public function broadcastOn(): array
    {
        return ['chats'];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
