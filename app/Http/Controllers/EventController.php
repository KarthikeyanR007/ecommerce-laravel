<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventService;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function __construct(private EventService $eventService)
    {
    }

    public function getAllEvent(){
       $events = $this->eventService->getAllEvents();
        return response()->json([
            'data'    => $events,
            'message' => 'All events get successfully',
        ]);
    }

    public function addEvent(Request $req)
    {
        $data = $req->all();
        return $this->eventService->addEvents($data);
    }

    public function updateEvent(Request $req, $eventId)
    {
        $data    = $req->all();
        $eventId = $eventId;
        $events   = $this->eventService->updateEvents($data,$eventId);
        return response()->json([
            'data'    => $events,
            'message' => 'All events Updated successfully',
        ]);
    }

    public function addFormData(Request $req)
    {
        $data    = $req->all();
        $studentData = $this->eventService->addformdata($data);
        return response()->json([
            'data'    => $studentData,
            'message' => 'All events Updated successfully',
        ]);
    }

    public function getallStudent()
    {
        $student = $this->eventService->getallStudent();
        return response()->json([
            'data' => [
                'data'         => $student->items(),
                'total'        => $student->total(),
                'per_page'     => $student->perPage(),
                'current_page' => $student->currentPage(),
                'last_page'    => $student->lastPage(),
            ],
            'message' => 'Get All student successfully',
        ]);
    }
}