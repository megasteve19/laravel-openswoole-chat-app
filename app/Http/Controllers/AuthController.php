<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Create');
    }

    public function store(StoreRequest $storeRequest): RedirectResponse
    {
        $user = User::where('username', $storeRequest->input('username'))
            ->first();

        Auth::loginUsingId($user->id);

        return redirect()->route('index');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('auth.create');
    }
}
