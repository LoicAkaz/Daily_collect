<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    //
    use HasFactory;
    protected $table = 'messages';
    protected $primaryKey = 'id_message';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = true;


    protected $fillable = [
        'id_message',
        'contenu',
        'envoyeur',
        'receptioneur',
        'chatId'
    ];
}
