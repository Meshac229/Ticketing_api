<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     description="Modèle représentant une commande",
 *     @OA\Property(
 *         property="order_id",
 *         type="integer",
 *         description="ID unique de la commande"
 *     ),
 *     @OA\Property(
 *         property="order_number",
 *         type="string",
 *         description="Numéro unique de la commande"
 *     ),
 *     @OA\Property(
 *         property="order_event_id",
 *         type="integer",
 *         description="ID de l'événement associé à la commande"
 *     ),
 *     @OA\Property(
 *         property="order_price",
 *         type="integer",
 *         description="Prix de la commande"
 *     ),
 *     @OA\Property(
 *         property="order_type",
 *         type="string",
 *         description="Type de commande"
 *     ),
 *     @OA\Property(
 *         property="order_payment",
 *         type="string",
 *         description="Méthode de paiement utilisée pour la commande"
 *     ),
 *     @OA\Property(
 *         property="order_info",
 *         type="string",
 *         description="Informations supplémentaires sur la commande",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="api_key",
 *         type="string",
 *         description="Clé API utilisée pour créer cette commande"
 *     ),
 *     @OA\Property(
 *         property="order_created_on",
 *         type="string",
 *         format="date-time",
 *         description="Date et heure de création de la commande"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date et heure de la dernière mise à jour de la commande"
 *     )
 * )
 */

class Order extends Model
{
    use HasFactory;

    // Nom de la table associée
    protected $table = 'orders';

    // Clé primaire associée à la table
    protected $primaryKey = 'order_id';

    // Désactiver l'horodatage automatique car nous avons une colonne personnalisée pour la création
    public $timestamps = false;

    // Définir les attributs qui sont mass assignable
    protected $fillable = [
        'order_number',
        'order_event_id',
        'order_price',
        'order_type',
        'order_payment',
        'order_info',
        'api_key',
    ];

    // Définir le type de certains attributs
    protected $casts = [
        'order_price' => 'integer',
        'order_created_on' => 'datetime',
    ];

    // Définir la colonne de timestamp personnalisée pour la création
    const CREATED_AT = 'order_created_on';

    // Relation avec la table Events
    public function event()
    {
        return $this->belongsTo(Event::class, 'order_event_id', 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_order_id', 'order_id');
    }

}
