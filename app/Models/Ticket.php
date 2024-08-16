<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Nom de la table associée
    protected $table = 'tickets';

    // Clé primaire associée à la table
    protected $primaryKey = 'ticket_id';

    // Désactiver l'horodatage automatique car nous avons une colonne personnalisée pour la création
    public $timestamps = false;

    // Définir les attributs qui sont mass assignable
    protected $fillable = [
        'ticket_event_id',
        'ticket_email',
        'ticket_phone',
        'ticket_price',
        'ticket_key',
        'ticket_ticket_type_id',
        'ticket_status',
        'ticket_order_id', // Ajout de ce champ
    ];

    // Définir le type de certains attributs
    protected $casts = [
        'ticket_price' => 'integer',
        'ticket_created_on' => 'datetime',
        'ticket_status' => 'string',
    ];

    // Définir la colonne de timestamp personnalisée pour la création
    const CREATED_AT = 'ticket_created_on';

    // Relation avec la table Events
    public function event()
    {
        return $this->belongsTo(Event::class, 'ticket_event_id', 'id');
    }

    // Relation avec la table Orders
    public function order()
    {
        return $this->belongsTo(Order::class, 'ticket_order_id', 'order_id');
    }

    // Relation avec la table TicketTypes (supposée)
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'ticket_ticket_type_id', 'id');
    }
}
