<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="OrderIntent",
 *     type="object",
 *     @OA\Property(property="order_intent_id", type="integer"),
 *     @OA\Property(property="order_intent_price", type="integer"),
 *     @OA\Property(property="order_intent_type", type="string"),
 *     @OA\Property(property="user_email", type="string", format="email"),
 *     @OA\Property(property="user_phone", type="string"),
 *     @OA\Property(property="expiration_date", type="string", format="date"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class OrderIntent extends Model
{
    use HasFactory;

    // Nom de la table associée
    protected $table = 'orders_intents';

    // Clé primaire associée à la table
    protected $primaryKey = 'order_intent_id';

    // Désactiver l'incrémentation automatique si order_intent_id n'est pas auto-incrémenté
    // public $incrementing = false;

    // Définir les attributs qui sont mass assignable
    protected $fillable = [
        'order_intent_price',
        'order_intent_type',
        'user_email',
        'user_phone',
        'expiration_date',
    ];

    // Définir le type de certains attributs
    protected $casts = [
        'order_intent_price' => 'integer',
        'expiration_date' => 'datetime',
    ];

    // Relation avec la table Events (si nécessaire)
    // public function event()
    // {
    //     return $this->belongsTo(Event::class, 'event_id', 'id');
    // }

    // Relation avec la table Orders (si nécessaire)
    // public function orders()
    // {
    //     return $this->hasMany(Order::class, 'order_intent_id', 'order_intent_id');
    // }
}
