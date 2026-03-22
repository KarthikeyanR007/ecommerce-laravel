<?php

namespace App\Services;

use App\Interfaces\EventInterface;
use Illuminate\Support\Collection;

class EventService
{
    public function __construct(private EventInterface $events)
    {
    }

    public function getAllEvents()
    {
        return $this->events->getAllEvents();
    }

    public function addEvents($data)
    {
        $eventname        = $data['title'];
        $eventdiscription = $data['desc'];
        $eventlocation    = $data['location'];
        $eventimg         = $data['photo'] ?? null;
        $eventdate        = \Carbon\Carbon::parse($data['date'])->format('Y-m-d');
        $imagePath = null;

        if (isset($data['photo']) && $data['photo']) {
            $timestamp = time();
            $extension = $eventimg->getClientOriginalExtension();
            $fileName  = $timestamp . '.' . $extension;
            $eventimg->storeAs('events', $fileName, 'public');
            $imagePath = 'storage/events/' . $fileName;
        }

        return $this->events->addEvents($eventname, $eventdiscription, $eventdate, $eventlocation, $eventimg, $imagePath);
    }
    
    public function updateEvents($data,$eventId)
    {
        $eventname        = $data['title'];
        $eventdiscription = $data['desc'];
        $eventlocation    = $data['location'];
        $eventimg         = $data['photo'] ?? null;
        $eventdate        = \Carbon\Carbon::parse($data['date'])->format('Y-m-d');
        $imagePath        = null;
        $isImg            = true;

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $timestamp = time();
            $extension = $eventimg->getClientOriginalExtension();
            $fileName  = $timestamp . '.' . $extension;
            $eventimg->storeAs('events', $fileName, 'public');
            $imagePath = 'storage/events/' . $fileName;
            $isImg = false;
        }
        if($isImg){
          $imagePath = $eventimg;
        }

        return $this->events->updateEvents($eventId, $eventname, $eventdiscription, $eventdate, $eventlocation, $imagePath);
    }

    public function addformdata($data)
    {
        $resume = null;
        if (isset($data['resume']) && $data['resume'] instanceof \Illuminate\Http\UploadedFile) {
            $timestamp = time();
            $fullname  = $data['fullName'];
            $resumeFile = $data['resume'];
            $extension = $resumeFile->getClientOriginalExtension();
            $fileName = str_replace(' ', '', $fullname) . '_' . $timestamp . '.' . $extension;
            $resumeFile->storeAs('resume', $fileName, 'public');
            $resume = 'storage/resume/' . $fileName;
        }
        return $this->events->addformdata($data, $resume);
    }

    public function getallStudent()
    {
       return $this->events->getallStudent();
    }
}
