<?php

namespace App\Repositories;

use App\Models\Event;
use App\Models\studentData;
use App\Interfaces\EventInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventInterface
{
    public function getAllEvents()
    {
        return Event::latest()->get();
    }

    public function addEvents($eventname, $eventdiscription, $eventdate, $eventlocation, $eventimg, $eventimage)
    {
        $events = Event::create([
                    'eventname'        => $eventname,
                    'eventdiscription' => $eventdiscription,
                    'eventdate'        => $eventdate,
                    'eventlocation'    => $eventlocation,
                    'eventimage'       => $eventimage
                ]);

        return $events;
    }

    public function updateEvents($eventId, $eventname, $eventdiscription, $eventdate, $eventlocation,$imagePath)
    {
        $eventId = $eventId;

        $eventRecord = Event::where('id',$eventId)->update([
                        'eventname'        => $eventname,
                        'eventdiscription' => $eventdiscription,
                        'eventdate'        => $eventdate,
                        'eventlocation'    => $eventlocation,
                        'eventimage'       => $imagePath
                    ]);

        return $eventRecord;
    }

    public function addformdata($data, $resume)
    {
        $fullname = $data['fullName'];
        $phone    = $data['phone'];
        $email    = $data['email'];
        $age    = $data['age'];
        $service = $data['service'];
        $resume  = $resume;
        $studentData = studentData::create([
                                'fullName' => $fullname,
                                'phone'    => $phone,
                                'email'    => $email,
                                'age'      => $age,
                                'service'  => $service,
                                'resume'   => $resume
                            ]);

        return $studentData;
    }

    public function getallStudent($perPage = 10)
    {
        return studentData::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function deleteEvent($eventId)
    {
        return Event::where('id',$eventId)->update(['event_status' => 0]);
    }
}