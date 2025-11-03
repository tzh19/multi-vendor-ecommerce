<?php

if (! function_exists('requireRole')) {
    function requireRole($role)
    {
        if (! auth()->check() || auth()->user()->role !== $role) {
            abort(403);
        }
    }
}
