<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Broadcast;

class BroadcastController extends Controller
{
    /**
     * Autentica conexiones a canales privados de Reverb.
     *
     * Delega a Broadcast::auth() que invoca los callbacks
     * definidos en routes/channels.php con el usuario autenticado
     * por Sanctum (auth()->user()). No toma user_id del body.
     */
    public function authenticate(Request $request): Response
    {
        return Broadcast::auth($request);
    }
}
