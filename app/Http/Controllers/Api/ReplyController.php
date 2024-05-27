<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\Ticket;

class ReplyController extends Controller
{
    /**
     * Store a newly created reply in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ticketId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $ticketId)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        $reply = new Reply();
        $reply->ticket_id = $ticket->id;
        $reply->user_id = $validatedData['user_id']; // Using user_id from the request
        $reply->message = $validatedData['message'];
        $reply->save();

        return response()->json($reply, 201);
    }
}
