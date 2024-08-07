<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;

class AttendeeController extends Controller
{

    use CanLoadRelationships;

    private array $relations = [
        'user',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $this->loadRelationships(
            $event-> attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
            'user_id' => $request->user()->id,
            ])
        );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $attendee->delete();

        return response(status: 204);
    }
}
