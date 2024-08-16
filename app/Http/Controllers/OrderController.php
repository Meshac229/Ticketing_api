<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{

/**
 * @OA\Get(
 *     path="/api/orders",
 *     security={{"apiAuth": {}}},
 *     summary="Récupérer toutes les commandes",
 *     tags={"Orders"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des commandes",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Order")
 *         )
 *     )
 * )
 */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

/**
 * @OA\Post(
 *     path="/api/orders",
 *     security={{"apiAuth": {}}},
 *     summary="Créer une nouvelle commande",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"order_number", "order_event_id", "order_price", "order_type", "order_payment"},
 *             @OA\Property(property="order_number", type="string", maxLength=50),
 *             @OA\Property(property="order_event_id", type="integer"),
 *             @OA\Property(property="order_price", type="integer"),
 *             @OA\Property(property="order_type", type="string", maxLength=50),
 *             @OA\Property(property="order_payment", type="string", maxLength=100),
 *             @OA\Property(property="order_info", type="string", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Commande créée avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
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
                'order_number' => 'required|string|max:50|unique:orders',
                'order_event_id' => 'required|exists:events,event_id',
                'order_price' => 'required|integer|min:0|max:16777215',
                'order_type' => 'required|string|max:50',
                'order_payment' => 'required|string|max:100',
                'order_info' => 'nullable|string',
            ]);

            $order = Order::create($validatedData);
            return response()->json($order, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

/**
 * @OA\Get(
 *     path="/api/orders/{id}",
 *     security={{"apiAuth": {}}},
 *     summary="Récupérer une commande par ID",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la commande",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Commande trouvée",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Commande non trouvée",
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
            $order = Order::findOrFail($id);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Commande non trouvée.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $validatedData = $request->validate([
                'order_number' => 'sometimes|required|string|max:50|unique:orders,order_number,' . $id . ',order_id',
                'order_event_id' => 'sometimes|required|exists:events,event_id',
                'order_price' => 'sometimes|required|integer|min:0|max:16777215',
                'order_type' => 'sometimes|required|string|max:50',
                'order_payment' => 'sometimes|required|string|max:100',
                'order_info' => 'nullable|string',
            ]);

            $order->update($validatedData);
            return response()->json($order);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour de la commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression de la commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

/**
 * @OA\Get(
 *     path="/api/orders/number/{orderNumber}",
 *     security={{"apiAuth": {}}},
 *     summary="Récupérer une commande par numéro de commande",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="orderNumber",
 *         in="path",
 *         required=true,
 *         description="Numéro de commande",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Commande trouvée",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Commande non trouvée",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    public function getByOrderNumber($orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Commande non trouvée.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user/orders",
     *     security={{"apiAuth": {}}},
     *     summary="Récupérer les commandes de l'utilisateur",
     *     description="Récupère une liste paginée des commandes de l'utilisateur authentifié",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de la page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des commandes de l'utilisateur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Order")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     */

    public function getUserOrders(Request $request)
    {
        // Extraire la clé API de l'en-tête Authorization
        $authHeader = $request->header('Authorization');

        // Assurez-vous que l'en-tête est au format attendu
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Extraire la clé API après le préfixe Bearer
        $apiKey = substr($authHeader, 7);

        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        $orders = Order::where('api_key', $apiKey)
            ->orderBy('order_created_on', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
        ]);
    }
}
