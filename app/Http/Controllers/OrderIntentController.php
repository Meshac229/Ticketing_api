<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderIntent;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderIntentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/order-intents",
     *     security={{"apiAuth": {}}},
     *     summary="Récupérer toutes les intentions de commande",
     *     tags={"Order Intents"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des intentions de commande",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OrderIntent")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $orderIntents = OrderIntent::all();
        return response()->json($orderIntents);
    }

/**
 * @OA\Post(
 *     path="/api/order-intents",
 *     security={{"apiAuth": {}}},
 *     summary="Créer une nouvelle intention de commande",
 *     tags={"Order Intents"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"order_intent_price", "order_intent_type", "user_email", "user_phone", "expiration_date"},
 *             @OA\Property(property="order_intent_price", type="integer"),
 *             @OA\Property(property="order_intent_type", type="string", maxLength=50),
 *             @OA\Property(property="user_email", type="string", format="email", maxLength=100),
 *             @OA\Property(property="user_phone", type="string", maxLength=20),
 *             @OA\Property(property="expiration_date", type="string", format="date")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Intention de commande créée avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/OrderIntent")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Données invalides",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_intent_price' => 'required|integer|min:0|max:16777215',
                'order_intent_type' => 'required|string|max:50',
                'user_email' => 'required|email|max:100',
                'user_phone' => 'required|string|max:20',
                'expiration_date' => 'required|date',
            ]);

            $orderIntent = OrderIntent::create($validatedData);
            return response()->json($orderIntent, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'intention de commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

/**
 * @OA\Get(
 *     path="/api/order-intents/{id}",
 *     security={{"apiAuth": {}}},
 *     summary="Récupérer une intention de commande par ID",
 *     tags={"Order Intents"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'intention de commande",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Intention de commande trouvée",
 *         @OA\JsonContent(ref="#/components/schemas/OrderIntent")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Intention de commande non trouvée",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function show($id)
    {
        try {
            $orderIntent = OrderIntent::findOrFail($id);
            return response()->json($orderIntent);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Intention de commande non trouvée.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $orderIntent = OrderIntent::findOrFail($id);

            $validatedData = $request->validate([
                'order_intent_price' => 'sometimes|required|integer|min:0|max:16777215',
                'order_intent_type' => 'sometimes|required|string|max:50',
                'user_email' => 'sometimes|required|email|max:100',
                'user_phone' => 'sometimes|required|string|max:20',
                'expiration_date' => 'sometimes|required|date',
            ]);

            $orderIntent->update($validatedData);
            return response()->json($orderIntent);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de l\'intention de commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $orderIntent = OrderIntent::findOrFail($id);
            $orderIntent->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression de l\'intention de commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

/**
 * @OA\Post(
 *     path="/api/order-intents/clean-expired",
 *     security={{"apiAuth": {}}},
 *     summary="Nettoyer les intentions de commande expirées",
 *     tags={"Order Intents"},
 *     @OA\Response(
 *         response=200,
 *         description="Intentions de commande expirées nettoyées",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="deleted_count", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function cleanExpiredIntents()
    {
        try {
            $expiredIntents = OrderIntent::where('expiration_date', '<', now())->delete();
            return response()->json([
                'message' => 'Les intentions de commande expirées ont été nettoyées.',
                'deleted_count' => $expiredIntents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors du nettoyage des intentions de commande expirées.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

/**
 * @OA\Post(
 *     path="/api/validate-order-intent/{id}",
 *     security={{"apiAuth": {}}},
 *     summary="Valider une intention de commande",
 *     tags={"Order Intents"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'intention de commande",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Commande validée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="order_number", type="string"),
 *             @OA\Property(property="ticket_download_url", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur lors de la validation de la commande",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */
    public function validateOrderIntent(Request $request, $orderIntentId)
    {
        DB::beginTransaction();
        try {
            // Récupérer l'api_key de l'en-tête de la requête
            $apiKey = $request->header('Authorization'); // Assurez-vous que c'est le bon nom d'en-tête

            // Assurez-vous que l'en-tête est au format attendu
            if (!$apiKey || !str_starts_with($apiKey, 'Bearer ')) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

// Extraire la clé API après le préfixe Bearer
            $apiKey = substr($apiKey, 7);

            // Vérifier si l'api_key est présent
            if (!$apiKey) {
                throw new \Exception('API key manquante dans l\'en-tête de la requête.');
            }

            // Récupérer l'intention de commande
            $orderIntent = OrderIntent::findOrFail($orderIntentId);

            // Vérifier si l'intention de commande n'a pas expiré
            if ($orderIntent->expiration_date < now()) {
                throw new \Exception('L\'intention de commande a expiré.');
            }

            // Récupérer l'événement en utilisant la bonne clé primaire
            $event = Event::where('event_id', $orderIntent->order_intent_id)->firstOrFail();

            // Créer une nouvelle commande basée sur l'intention
            $order = Order::create([
                'order_number' => 'ORD-' . Str::random(10),
                'order_price' => $orderIntent->order_intent_price,
                'order_type' => $orderIntent->order_intent_type,
                'order_event_id' => $event->event_id,
                'order_payment' => 'pending',
                'order_info' => '',
                'api_key' => $apiKey, // Ajouter l'api_key ici
            ]);

            // Trouver le type de ticket approprié
            $ticketType = $this->findAppropriateTicketType($event, $order->order_price);

            // Générer un ticket pour la commande
            $ticket = Ticket::create([
                'ticket_key' => 'TIK-' . Str::random(10),
                'ticket_email' => $orderIntent->user_email,
                'ticket_phone' => $orderIntent->user_phone,
                'ticket_price' => $order->order_price,
                'ticket_event_id' => $event->event_id,
                'ticket_order_id' => $order->order_id,
                'ticket_ticket_type_id' => $ticketType->ticket_type_id,
                'ticket_status' => 'active',
            ]);

            // Générer l'URL pour télécharger les tickets
            $ticketUrl = url("api/download-tickets/{$order->order_number}");

            // Supprimer l'intention de commande
            $orderIntent->delete();

            DB::commit();

            return response()->json([
                'message' => 'Commande validée avec succès',
                'order_number' => $order->order_number,
                'ticket_download_url' => $ticketUrl,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la validation de la commande',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    private function findAppropriateTicketType(Event $event, $price)
    {
        $ticketTypes = $event->ticketTypes;

        $ticketType = $ticketTypes->first(function ($type) use ($price) {
            return $type->ticket_type_price == $price;
        });

        if (!$ticketType) {
            $ticketType = $ticketTypes->sortBy(function ($type) use ($price) {
                return abs($type->ticket_type_price - $price);
            })->first();
        }

        if (!$ticketType) {
            throw new \Exception("Aucun type de ticket disponible pour cet événement.");
        }

        return $ticketType;
    }

}
