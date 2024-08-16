<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Exclure certaines routes du middleware
        $excludedRoutes = ['/download-tickets', "/api/documentation"];

        if (in_array($request->path(), $excludedRoutes)) {
            return $next($request);
        }

        // Extraire la clé API de l'en-tête Authorization
        $apiKey = $request->header('Authorization');

        // Assurez-vous que l'en-tête est au format attendu
        if (!$apiKey || !str_starts_with($apiKey, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Extraire la clé API après le préfixe Bearer
        $apiKey = substr($apiKey, 7);

        // Vérifier si la clé API existe dans la base de données
        $exists = DB::table('api_requests')->where('api_key', $apiKey)->exists();

        if (!$exists) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
/*

public function handle(Request $request, Closure $next)
{
// Extraire la clé API de l'en-tête Authorization
$apiKey = $request->header('Authorization');

// Assurez-vous que l'en-tête est au format attendu
if (!$apiKey || !str_starts_with($apiKey, 'Bearer ')) {
return response()->json(['message' => 'Unauthorized'], 401);
}

// Extraire la clé API après le préfixe Bearer
$apiKey = substr($apiKey, 7);

// Vérifier si la clé API existe dans la base de données
$exists = DB::table('api_requests')->where('api_key', $apiKey)->exists();

if (!$exists) {
return response()->json(['message' => 'Unauthorized'], 401);
}

return $next($request);
}
 */
}
