<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class client extends Model
{
    use HasFactory;
    protected $table = 'client';
    protected $primaryKey = 'id_client';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;


    protected $fillable = [
        'id_client',
        'nom_client',
        'prenom_client',
        'addresse_client',
        "telephone_client",
        "sexe_client",
        "age",
        "cni_client",
        "photo_client",
        "id_user"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(transaction::class);
    }
}
