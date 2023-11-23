<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        $this->middleware('throttle:api')
            ->only(['store', 'destory']);
        $this->authorizeResource(Attendee::class, 'event');
    }


    public function index(Event $event)
    {
        $attendee = $event->attendees()->latest();
        return AttendeeResource::collection(
            $attendee->paginate()
        );
    }



    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => $request->user()->id
        ]);
        return new AttendeeResource($attendee);
    }



    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }



    public function destroy(Event $event, Attendee $attendee)
    {
        // $this->authorize('delete-attendee', '[$event,$attendee]');
        $attendee->delete();
        // return response()->json([
        //     'message' => 'Attendee deleted successfully'
        // ])
        return response(status: 204);
    }
}
