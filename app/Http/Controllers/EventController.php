<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Swagger\schemas;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Events",
 *     description="API Endpoints pour la gestion des événements"
 * )
 */

class EventController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/events",
     * security={{"apiAuth": {}}},
     * summary="Liste paginée des événements en cours",
     * tags={"Events"},
     * @OA\Response(
     * response=200,
     * description="Liste des événements en cours récupérée avec succès",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="current_page", type="integer"),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Event")),
     * @OA\Property(property="first_page_url", type="string"),
     * @OA\Property(property="from", type="integer"),
     * @OA\Property(property="last_page", type="integer"),
     * @OA\Property(property="last_page_url", type="string"),
     * @OA\Property(property="next_page_url", type="string"),
     * @OA\Property(property="path", type="string"),
     * @OA\Property(property="per_page", type="integer"),
     * @OA\Property(property="prev_page_url", type="string"),
     * @OA\Property(property="to", type="integer"),
     * @OA\Property(property="total", type="integer")
     * )
     * )
     * )
     */
    public function index()
    {
        $currentDate = now();
        $events = Event::where('event_date', '>', $currentDate)
            ->orderBy('event_date', 'asc')
            ->paginate(10);
        return response()->json($events);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     security={{"apiAuth": {}}},
     *     summary="Afficher un événement spécifique",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Événement récupéré avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Événement non trouvé",
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
            $event = Event::where('event_id', $id)->firstOrFail();
            return response()->json($event);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Événement non trouvé.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

/**
 * @OA\Post(
 *     path="/api/events",
 *     security={{"apiAuth": {}}},
 *     summary="Créer un nouvel événement",
 *     tags={"Events"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"event_title", "event_description", "event_date", "event_image", "event_category", "event_city", "event_address", "event_status"},
 *                 @OA\Property(property="event_title", type="string", maxLength=30),
 *                 @OA\Property(property="event_description", type="string", maxLength=16777215),
 *                 @OA\Property(property="event_date", type="string", format="date"),
 *                 @OA\Property(property="event_image", type="string", maxLength=200),
 *                 @OA\Property(property="event_category", type="string", enum={"Autre", "Concert-Spectacle", "Diner Gala", "Festival", "Formation"}),
 *                 @OA\Property(property="event_city", type="string", maxLength=100),
 *                 @OA\Property(property="event_address", type="string", maxLength=200),
 *                 @OA\Property(property="event_status", type="string", enum={"upcoming", "completed", "cancelled"})
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Événement créé avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/Event")
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
                'event_title' => 'required|string|max:30',
                'event_description' => 'required|string|max:16777215', // mediumText max length
                'event_date' => 'required|date',
                'event_image' => 'required|string|max:200',
                'event_category' => 'required|string|in:Autre,Concert-Spectacle,Diner Gala,Festival,Formation',
                'event_city' => 'required|string|max:100',
                'event_address' => 'required|string|max:200',
                'event_status' => 'required|string|in:upcoming,completed,cancelled',
            ]);

            $event = Event::create($validatedData);

            return response()->json($event, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'événement.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validatedData = $request->validate([
            'event_title' => 'sometimes|required|string|max:255',
            'event_description' => 'nullable|string',
            'event_date' => 'sometimes|required|date',
            'event_category' => 'sometimes|required|string',
            'event_image' => 'sometimes|nullable|string',
            'event_city' => 'sometimes|nullable|string',
            'event_address' => 'sometimes|nullable|string',
            'event_status' => 'sometimes|required|string',
        ]);
        $event->update($validatedData);
        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
