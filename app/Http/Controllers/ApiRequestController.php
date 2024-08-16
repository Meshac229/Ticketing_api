<?php

namespace App\Http\Controllers;

use App\Models\ApiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApiRequestController extends Controller
{
    public function showForm()
    {
        return view('api_request_form');
    }

    public function handleRequest(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|unique:api_requests,email',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Générer une clé API unique
        $apiKey = Str::random(40);

        // Enregistrer la demande dans la base de données

        $apiRequest = ApiRequest::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'company' => $validatedData['company'],
            'email' => $validatedData['email'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'api_key' => $apiKey,
        ]);

        Mail::send('emails.api_key', ['apiKey' => $apiKey], function ($message) use ($validatedData) {
            $message->to($validatedData['email']);
            $message->subject('Votre clé API');
        });
        return redirect()->route('emails.success')->with('message', 'Demande envoyée avec succès. Vérifiez votre email pour la clé API.');

        // return response()->json(['message' => 'Demande envoyée avec succès. Vérifiez votre email pour la clé API.']);
    }
}
