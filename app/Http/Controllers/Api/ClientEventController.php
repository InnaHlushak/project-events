<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;


class ClientEventController extends Controller
{
    public function example() {
        $message ='Hello from Laravel';
        return response()->json(['message'=>$message]);
    }

    /**
     * Display a listing of all events
     */
    public function index(Request $request)
    {
        //Access the Event model, return all records sorted (ascending) by deadline-date
        $events = Event::orderBy('deadline','asc');
        $events = $events->paginate(3);


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
}