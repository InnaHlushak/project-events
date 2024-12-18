<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EventRequest;
use App\Models\Category; 
use App\Models\Event; 
use App\Models\Cost; 

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin'])->except(['index', 'show']);
    }
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
        $costs = Cost::all();

        return view('events.create',['categories'=>$categories, 'costs' => $costs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
       //get all data from the request (form) except the next
        $data = $request->except('_token', 'costs', 'image');

        //save the path to the image file in the database
        if ($request->hasFile('image')) {
        $data['image']  = $request->file('image')->store('posters');
        }

        //Reference the Event model and create a record in the corresponding table
        $event = Event::create($data);

        //Associate ticket types (by costs) to events
        //$event->costs()->attach($request->costs);
        //Parse JSON strings and get an array with only ID
        $costIds = array_map(function ($cost) {
            $parsedCost = json_decode($cost, true);
            return $parsedCost['id'];
        }, $request->costs);
        //to attach  cost to event by inserting a record in the relationship's intermediate table
        $event->costs()->attach($costIds);

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
    public function edit(Event $event)
    {
        $categories = Category::all();
        $costs = Cost::all();

        return view('events.edit',['event' => $event, 'categories' => $categories, 'costs' => $costs]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->except('_token'));
        $event->costs()->sync($request->costs);

        if ($request->hasFile('image')) {
            if (Storage::exists($event->image)) {
                Storage::delete($event->image);
            } 
            
            $newImagePath = $request->file('image')->store('posters');
            $event->image = $newImagePath;
            $event->update(['image' => $newImagePath]);
        } else {
            $previousImagePath = $request->input('previous_image');
            $event->image = $previousImagePath;
            $event->update(['image' => $previousImagePath]);
        }

        return redirect()->route('events.show',[$event]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (Storage::exists($event->image)) {
            Storage::delete($event->image);
        }
        
        $event->delete();

        return redirect()->route('events.index');
    }

    /**
     * method for confirming delete
     */
    public function confirmDelete($id)
    {
        $event = Event::findOrFail($id);
        return view('events.confirm-delete', compact('event'));
    }
}
