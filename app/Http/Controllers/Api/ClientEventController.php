<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\EventInvitation;
use App\Mail\EventTicket;

class ClientEventController extends Controller
{
    /**
     * Display a listing of all events with a date no later than the current one
     */
    public function index(Request $request)
    {
        //Access the Event model, return all records sorted (ascending) by deadline-date
        $currentDate = now(); // Get the current date and time
        $events = Event::where('deadline', '>=', $currentDate)
                        ->orderBy('deadline', 'asc')
                        ->paginate(6);

        if( $request->expectsJson()) {
            return new EventCollection($events);
        }

        return response()->json($events);
    }

    /**
     * Display single event whith id.
     */
    public function show(string $id, Request $request)
    {
        //Refer to the Event model and find the 1st record in the table with the specified id 
        $event = Event::findOrFail($id);

        if( $request->expectsJson()) {
            return new EventResource($event);
        }

         return response()->json($event);
    }

    /**
     * Increment popularity of event whith id.
     */
    public function incrementPopularity($id)
    {
        $event = Event::findOrFail($id);
        $event->popularity++;
        $event->save();
        return response()->json(['success' => true]);
    }

    /**
     * Increment number participants of event whith id.
     */
    public function incrementNumber($id)
    {
        $event = Event::findOrFail($id);
        $event->number++;
        $event->save();
        return response()->json(['success' => true]);
    }

    /**
     * Show most popular events (top 3)
     */
    public function popular(Request $request)
    {
        $currentDate = now(); // Get the current date and time
        $events = Event::where('deadline', '>=', $currentDate)
                        ->orderBy('popularity', 'desc')
                        ->orderBy('deadline', 'asc')
                        ->take(3)
                        ->get();

        if( $request->expectsJson()) {
            return new EventCollection($events);
        }

        return response()->json($events);
    }

    /**
     * Find event records in the database that contain the specified $text, and display a list of all these events
     */
    public function search( Request $request,string $text)
    {
        $events = Event::where('name', 'like', "%$text%")        
            ->orWhere('deadline', 'like', "%$text%")
            ->orWhere('venue', 'like', "%$text%")
            ->orWhere('description', 'like', "%$text%")
            ->orWhereHas('category', function ($query) use ($text) {
                $query->where('name', 'like', "%$text%");
            })
            ->orWhereHas('costs', function ($query) use ($text) {
                $query->where('name', 'like', "%$text%");
            })
            ->orderBy('deadline', 'asc')
            ->paginate(3);

        return EventResource::collection($events);
    }

    public function sendInvitationMail(string $idEvent, string $idUser, string $number)
    {
        $event = Event::findOrFail($idEvent);
        $user = User::findOrFail($idUser);

        Mail::to($user->email)->send(new EventInvitation($event, $user, $number));

        return response()->json(['success' => true]);
    }

    public function sendTicketMail(string $idEvent, string $idUser, string $typeTicked, string $finalPrice, string $number)
    {
        $event = Event::findOrFail($idEvent);
        $user = User::findOrFail($idUser);

        Mail::to($user->email)->send(new EventTicket($event, $user, $typeTicked, $finalPrice, $number));

        return response()->json(['success' => true]);
    }
}