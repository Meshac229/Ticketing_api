<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Model\Event;

/**
 * @OA\Schema(
 *     schema="TicketType",
 *     required={"ticket_type_event_id", "ticket_type_name", "ticket_type_price", "ticket_type_quantity", "ticket_type_real_quantity", "ticket_type_total_quantity", "ticket_type_description"},
 *     @OA\Property(property="ticket_type_id", type="integer", description="ID du type de ticket"),
 *     @OA\Property(property="ticket_type_event_id", type="integer", description="ID de l'événement associé"),
 *     @OA\Property(property="ticket_type_name", type="string", maxLength=50, description="Nom du type de ticket"),
 *     @OA\Property(property="ticket_type_price", type="integer", description="Prix du type de ticket"),
 *     @OA\Property(property="ticket_type_quantity", type="integer", description="Quantité disponible du type de ticket"),
 *     @OA\Property(property="ticket_type_real_quantity", type="integer", description="Quantité réelle du type de ticket"),
 *     @OA\Property(property="ticket_type_total_quantity", type="integer", description="Quantité totale du type de ticket"),
 *     @OA\Property(property="ticket_type_description", type="string", maxLength=16777215, description="Description du type de ticket"),
 *     @OA\Property(property="event", ref="#/components/schemas/Event", description="Événement associé au type de ticket")
 * )
 */

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_type_event_id',
        'ticket_type_name',
        'ticket_type_price',
        'ticket_type_quantity',
        'ticket_type_real_quantity',
        'ticket_type_total_quantity',
        'ticket_type_description'
    ];

    // Relation inverse vers Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'ticket_type_event_id', 'event_id');
    }

}
