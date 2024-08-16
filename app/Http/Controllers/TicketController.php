<?php

namespace App\Http\Controllers;

use App\Models\Order;
use PDF;

// Assurez-vous d'avoir installé une bibliothèque PDF comme 'barryvdh/laravel-dompdf'

class TicketController extends Controller
{
    public function downloadTickets($orderId)
    {
        try {
            // Récupérer la commande en utilisant l'identifiant de la commande
            $order = Order::where('order_number', $orderId)->firstOrFail();

            // Récupérer les tickets associés à cette commande
            $tickets = $order->tickets;

            if ($tickets->isEmpty()) {
                throw new \Exception('Aucun ticket trouvé pour cette commande.');
            }

            $pdf = PDF::loadView('tickets.pdf', ['order' => $order, 'tickets' => $tickets]);

            // Définir le nom du fichier
            $fileName = 'tickets_' . $order->order_number . '.pdf';

            // Retourner le PDF pour le téléchargement
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du téléchargement des tickets',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

}
