<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusyResource;
use App\Models\Busy;
use App\Models\Employee;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class FreeController extends Controller
{
    public function index()
    {

        //hard coded vars
        $employees = request('employees');
        $meeting_length = request('length');
        $start_date_time= request('start_date_time');
        $end_date_time= request('end_date_time');


        $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $start_date_time);
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date_time);


        //same for intervals vars
        $timeStep = 30;

        //declare intervals from time start to end date for 30mins
        $intervals = CarbonInterval::minutes($timeStep)->toPeriod($start_date_time, $end_date);

        //to hold slots
        $possibleSlots = [];
        $busySlots = [];
        $notEnoughTimeSlots = [];

        //loop through carbon object to remove times outside of working hours
        foreach ($intervals as $slot) {

            if(
                ($slot->format('H:i:s') >= $start_date->format('H:i:s')) && ($slot->format('H:i:s') < $end_date->format('H:i:s'))
            ) {
                //dump($slot);
                $possibleSlots[] = $slot;
	       }
        }

        $found_employees= Employee::whereIn('company_id', $employees)->pluck('id');

        $books = Busy::with('employee')
        ->whereIn('employee_id', $found_employees)
        ->whereBetween('date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d') ])
        ->orderBy('employee_id', 'asc')
        ->get();

        //get slots that are not an option as there are bookings
        foreach ($possibleSlots as $slot){

            foreach ($books as $book){

                $start_book = Carbon::createFromFormat('Y-m-d H:i:s', $book->date . " " . $book->start_time);
                $end_book = Carbon::createFromFormat('Y-m-d H:i:s', $book->date . " " . $book->end_time)->subMinutes(1);

            if($slot->between($start_book, $end_book)){
                $busySlots[] = $slot;
            }

            }
        }

        //get those slots that will not have enough time
        foreach($possibleSlots as $slot){

            foreach($books as $book){
                $with_meeting_length = $slot->copy()->addMinutes($meeting_length - 1);
                $start_book = Carbon::createFromFormat('Y-m-d H:i:s', $book->date . " " . $book->start_time);
                $end_book = Carbon::createFromFormat('Y-m-d H:i:s', $book->date . " " . $book->end_time)->subMinutes(1);

                if(
                    $with_meeting_length->between($start_book, $end_book) ||
                    $with_meeting_length->format('H:i:s') > $end_date->format('H:i:s')){
                    $notEnoughTimeSlots[] = $slot;
                }
            }

        }

        //get the differences to confirm available slots
        $availableSlots = array_diff($possibleSlots, $busySlots, $notEnoughTimeSlots);


      return $availableSlots;

    }



}
