<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TicketTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ticket-types",
     *     security={{"apiAuth": {}}},
     *     summary="Obtenir tous les types de tickets",
     *     tags={"TicketTypes"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des types de tickets",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TicketType"))
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
    public function index()
    {
        $ticketTypes = TicketType::all();
        return response()->json($ticketTypes);
    }

    /**
     * @OA\Post(
     *     path="/api/ticket-types",
     *     security={{"apiAuth": {}}},
     *     summary="Créer un nouveau type de ticket",
     *     tags={"TicketTypes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"ticket_type_event_id", "ticket_type_name", "ticket_type_price", "ticket_type_quantity", "ticket_type_real_quantity", "ticket_type_total_quantity", "ticket_type_description"},
     *                 @OA\Property(property="ticket_type_event_id", type="integer"),
     *                 @OA\Property(property="ticket_type_name", type="string", maxLength=50),
     *                 @OA\Property(property="ticket_type_price", type="integer"),
     *                 @OA\Property(property="ticket_type_quantity", type="integer"),
     *                 @OA\Property(property="ticket_type_real_quantity", type="integer"),
     *                 @OA\Property(property="ticket_type_total_quantity", type="integer"),
     *                 @OA\Property(property="ticket_type_description", type="string", maxLength=16777215)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Type de ticket créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/TicketType")
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
                'ticket_type_event_id' => 'required|exists:events,event_id',
                'ticket_type_name' => 'required|string|max:50',
                'ticket_type_price' => 'required|integer|min:0|max:16777215',
                'ticket_type_quantity' => 'required|integer|min:0',
                'ticket_type_real_quantity' => 'required|integer|min:0',
                'ticket_type_total_quantity' => 'required|integer|min:0',
                'ticket_type_description' => 'required|string|max:16777215',
            ]);

            $ticketType = TicketType::create($validatedData);
            return response()->json($ticketType, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du type de ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $ticketType = TicketType::findOrFail($id);
        return response()->json($ticketType);
    }

    public function update(Request $request, $id)
    {
        try {
            $ticketType = TicketType::findOrFail($id);

            $validatedData = $request->validate([
                'ticket_type_event_id' => 'sometimes|required|exists:events,event_id',
                'ticket_type_name' => 'sometimes|required|string|max:50',
                'ticket_type_price' => 'sometimes|required|integer|min:0|max:16777215',
                'ticket_type_quantity' => 'sometimes|required|integer|min:0',
                'ticket_type_real_quantity' => 'sometimes|required|integer|min:0',
                'ticket_type_total_quantity' => 'sometimes|required|integer|min:0',
                'ticket_type_description' => 'sometimes|required|string|max:16777215',
            ]);

            $ticketType->update($validatedData);
            return response()->json($ticketType);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du type de ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ticketType = TicketType::findOrFail($id);
            $ticketType->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression du type de ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/events/{eventId}/ticket-types",
     *     security={{"apiAuth": {}}},
     *     summary="Obtenir tous les types de tickets pour un événement spécifique",
     *     tags={"TicketTypes"},
     *     @OA\Parameter(
     *         name="eventId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des types de tickets pour l'événement",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TicketType"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucun type de ticket trouvé pour cet événement",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="event_id", type="integer")
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
    public function getTicketTypesByEvent($eventId)
    {
        try {
            $event = Event::where('event_id', $eventId)->firstOrFail();

            // Ajoute ceci pour vérifier si l'événement est bien récupéré
            if (!$event) {
                return response()->json([
                    'message' => 'Événement non trouvé.',
                ], 404);
            }

            $ticketTypes = $event->ticketTypes;

            // Ajoute ceci pour vérifier le contenu de $ticketTypes
            if ($ticketTypes->isEmpty()) {
                return response()->json([
                    'message' => 'Aucun type de ticket trouvé pour cet événement.',
                    'event_id' => $eventId, // Ajoute l'ID de l'événement pour le débogage
                ], 404);
            }

            return response()->json($ticketTypes);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des types de tickets.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
