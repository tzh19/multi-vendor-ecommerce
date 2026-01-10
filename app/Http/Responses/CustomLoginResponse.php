<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('dashboard'));
        }

        if ($user->hasRole('vendor')) {
            return redirect()->intended(route('vendor.dashboard'));
        }

        if ($user->hasRole('customer')) {
            return redirect()->route('products.index');
        }

        return redirect()->route('dashboard');
    }
}
