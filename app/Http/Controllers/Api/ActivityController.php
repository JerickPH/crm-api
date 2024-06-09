<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Ticket;
use App\Models\Company;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function getTicketActivities($ticketId)
    {
        $activities = Activity::where('subject_id', $ticketId)
                              ->where('subject_type', Ticket::class)
                              ->with('user')
                              ->latest()
                              ->get();

        return response()->json($activities);
    }

    public function getCompanyActivities($companyId)
    {
        $activities = Activity::where('subject_id', $companyId)
                              ->where('subject_type', Company::class)
                              ->with('user')
                              ->latest()
                              ->get();

        return response()->json($activities);
    }
}
