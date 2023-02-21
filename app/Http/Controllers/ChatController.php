<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Index', [
            'messages' => Message::with('user')
                ->orderBy('created_at')
                ->get(),
        ]);
    }
}
