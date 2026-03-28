<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface EventInterface
{
    public function getAllEvents();
    public function addEvents($eventname, $eventdiscription, $eventdate, $eventlocation, $eventimg, $imagePath);
    public function updateEvents($eventId, $eventname, $eventdiscription, $eventdate, $eventlocation, $imagePath);
    public function addformdata($data, $resume);
    public function getallStudent();
    public function deleteEvent($eventId);
}