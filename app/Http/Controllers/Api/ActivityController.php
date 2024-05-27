<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Ticket;

class ActivityController extends Controller
{
    public function index(Ticket $ticket)
    {
        $activities = Activity::where('subject_id', $ticket->id)
                              ->where('subject_type', Ticket::class)
                              ->with('user')
                              ->latest()
                              ->get();

        return response()->json($activities);
    }
}
