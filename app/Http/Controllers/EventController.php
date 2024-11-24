<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Models\Category; 
use App\Models\Event; 

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Access the Event model, return all records sorted (ascending) by deadline-date
        $events = Event::orderBy('deadline');
        $events = $events->paginate(3);
        return view('events.index',['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('events.create',['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        //get all data from the request (form) except the hidden field _token
        $data = $request->except('_token');
        //Reference the Event model and create a record in the corresponding table
        $event = Event::create($data);

        return redirect()->route('events.show',[$event]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         //Refer to the Event model and find the 1st record in the table with the specified id 
         $event = Event::findOrFail($id);

         return view('events.show',['event'=>$event]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
