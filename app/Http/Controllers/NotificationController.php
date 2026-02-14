<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {

        $notifications = $request->user()->notifications()->paginate(10);

        $notifications->getCollection()->transform(function ($n) {
            return [
                'id' => $n->id,
                'data' => $n->data,
                'read_at' => $n->read_at,
                'created_at' => $n->created_at->diffForHumans(),
            ];
        });

        return inertia('Notification/Index', [
            'notifications' => $notifications
        ]);

    }
}
