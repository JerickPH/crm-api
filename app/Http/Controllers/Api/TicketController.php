<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TicketCreated;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request data: ', $request->all());

        $validatedData = $request->validate([
            'ticket_subject' => 'required|string|max:255',
            'assign_staff' => 'nullable|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
            'priority' => 'nullable|in:High,Medium,Low',
            'cc' => 'nullable',
            'description' => 'required|string',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'to_email' => 'required|email',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $this->generateUniqueFilename($request->file('file'));
            $request->file('file')->storeAs('tickets', $filePath, 'public');
        }

        $messageId = Str::random(32) . '@example.com';

        try {
            $ticket = Ticket::create([
                'subject' => $validatedData['ticket_subject'],
                'assign_staff' => $validatedData['assign_staff'] ?? null,
                'to_email' => $validatedData['to_email'],
                'company_id' => $validatedData['company_id'] ?? null,
                'priority' => $validatedData['priority'] ?? null,
                'cc' => $validatedData['cc'] ?? null,
                'description' => $validatedData['description'],
                'file' => $filePath,
                'user_id' => $validatedData['user_id'] ?? (auth()->check() ? auth()->id() : null),
                'message_id' => $messageId,
                'status' => 'New'
            ]);

            Log::info('Ticket created: ', $ticket->toArray());

            $ccEmails = [];
            if (!empty($validatedData['cc'])) {
                $ccEmails = array_map('trim', explode(',', $validatedData['cc']));
            }

            Mail::to($validatedData['to_email'])
                ->cc($ccEmails)
                ->send(new TicketCreated($ticket));

            return response()->json(['message' => 'Ticket is successfully created!', 'ticket' => $ticket], 201);
        } catch (\Exception $e) {
            Log::error("Error creating ticket: " . $e->getMessage());
            return response()->json(['message' => 'Error processing ticket creation'], 500);
        }
    }

    public function show(Ticket $ticket)
    {
        return response()->json($ticket->load('user', 'userProfile', 'replies'));
    }


    public function update(Request $request, Ticket $ticket)
    {
        Log::info('Update method called');
        Log::info('Request data: ', $request->all());

        $validatedData = $request->validate([
            'subject' => 'sometimes|required|string|max:255',
            'assign_staff' => 'sometimes|required|integer|exists:users,id',
            'company_id' => 'sometimes|required|integer|exists:companies,id',
            'user_id' => 'sometimes|nullable|exists:users,id',
            'priority' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'to_email' => 'sometimes|required|email',
            'cc' => 'sometimes|nullable|string',
        ]);

        Log::info('Validated data: ', $validatedData);

        Log::info('Current ticket state: ', $ticket->toArray());

        $updated = false;
        foreach ($validatedData as $key => $value) {
            if ($ticket->$key !== $value) {
                $ticket->$key = $value;
                $updated = true;
            }
        }

        if ($updated) {
            $ticket->save();
            Log::info('Ticket updated: ', $ticket->toArray());
        } else {
            Log::info('No changes detected.');
        }

        return response()->json($ticket);
    }


    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(null, 204);
    }

    private function generateUniqueFilename($file)
    {
        $originalName = $file->getClientOriginalName();
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $slug = Str::slug($name);
        $count = 1;

        while (Storage::disk('public')->exists("tickets/{$slug}.{$extension}")) {
            $slug = Str::slug($name) . "-{$count}";
            $count++;
        }

        return "{$slug}.{$extension}";
    }
}