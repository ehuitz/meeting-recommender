<?php

namespace App\Http\Controllers\Api;

use App\Models\Sync;
use App\Models\Employee;
use App\Models\Busy;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSyncRequest;
use App\Http\Resources\SyncResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use Carbon\Carbon;

class SyncController extends Controller
{


    //This controller retrieves file from url, parse, and store to db.

    // Add Sync Log

    public function upload(Request $request){


        Log::info('starting manual sync upload');
        $request->validate([
           'status' => 'required',
            'origin' => 'required',
            'textFile' => 'required'
         ]);

         $sync = new Sync;
         $mytime = Carbon::now();


         if($request->file()) {

              // Store File
            $file_name = time().'_'. $request->textFile->getClientOriginalName();
            $file_path = $request->file('textFile')->storeAs($mytime->format('d-m-Y'), $file_name, 'public');

            $sync->status = $request->status;
            $sync->origin = $request->origin;

            $sync->name = $file_name;
            $sync->path = 'storage/'. $file_path;
            $sync->save();

            Log::info('ending manual sync upload');

             //Parse File
             $input = fopen($request->file('textFile'), "r");


                //  Split by line
                 foreach (explode("\r\n", file_get_contents($request->file('textFile'))) as $key=>$line){
                //Split by ; in each line
                    $array[$key] = explode(';', $line);
                 }


            $this->parse($array);
         }

    }

    public function parse($lines)
    {
        Log::info('starting text file parse');
        //remove blank rows
        $lines = array_map('array_filter', $lines);
        $lines = array_filter($lines);
       // dd($lines);

        $errors = [];

        Busy::truncate();

        foreach ($lines as $line) {

            // if array has two items
            if(count($line)==2){

                //it is an company id and employee name
                //update or create based on company id
                Employee::updateOrCreate([
                    "company_id" => $line[0]
                ],
                    [
                        "name" => $line[1],
                    ]);
            }
            // if array has more than 2 items
            elseif(count($line)>2){

                    //check employee exists
                    $employee = Employee::where('company_id', $line[0])->first();

                    //if null create it
                    if(!$employee){
                      $new_employee =  Employee::create([
                            'company_id' => $line[0]
                        ]);

                        $employee = Employee::where('company_id', $line[0])->first();

                    }
                    //use employee id to create busy record
                    //stored the date in a different format
                    //add as resource

                    Busy::create([
                        "employee_id" => $employee->id,
                        "date" => date('Y-m-d',strtotime($line[1])),
                        "start_time" => date('H:i:s',strtotime($line[1])),
                        "end_time" => date('H:i:s',strtotime($line[2])),
                        ]);

            }

            else
            {
                //dont worry about anything else, for now
                continue;
            }



        }

    //     try {


    //     }
    //     catch (QueryException $e) {
    //        $errors = $errors + [$e->getMessage()];
    //    }
    Log::info('finishing text file parse');
    }
}
