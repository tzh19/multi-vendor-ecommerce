<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->hasRole('customer')) {
            return redirect()->route('products.index');
        }

        if ($user->hasRole('vendor')) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('dashboard');
    }
}
