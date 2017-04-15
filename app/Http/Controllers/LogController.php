<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\LogEntry;
use App\Service;

class LogController extends Controller
{
    public function putLog(Request $request){
        #$this->middleware('auth', ['except' => 'putLog']);
        $json = $request->data;

        if (!self::is_JSON($json)) {
            # Not valid JSON string"
            $error = json_last_error_msg();
            return response('Not valid JSON string ('.$error.')', 400);
        } else {
            # "Valid JSON string";

            try {
                $results1 = \DB::connection("mysql")->select(\DB::raw("SELECT * FROM log_entries"));
                $results2 = \DB::connection("mysql")->select(\DB::raw("SELECT * FROM log_entry_service"));
                $results3 = \DB::connection("mysql")->select(\DB::raw("SELECT * FROM services"));
            } catch(\Illuminate\Database\QueryException $ex){
                return response('DB error', 500);
            }

            $incomingData = json_decode($json);
            $serviceNames = $incomingData->services;

            $logEntry = new LogEntry();
            $logEntry->user_id = $incomingData->user_id;
            $logEntry->user_name = $incomingData->user_name;
            $logEntry->app_version = $incomingData->app_version;
            $logEntry->internal_ip = $incomingData->internal_ip;

            $logEntry->save();

            if (is_array($serviceNames) && count($serviceNames) > 0){
                foreach($serviceNames as $serviceName){
                    $service = Service::firstOrNew(
                        array('name' => $serviceName));
                    $service->save();
                    $services[$service->id] = $serviceName;
                }
                $logEntry->services()->sync(array_keys($services));
            }
            return response('Log entry registered with id #'.$logEntry->id, 200);
        }
    }
    private function getAllServiceNames($serviceString){
        $services = DB::table('services')->select('name')->get();

        foreach ($services as $service){
            $serviceNames[] = $service->name;
        }
        return $serviceNames;
    }

    private function is_JSON($string) {
        json_decode($string);
        return (json_last_error()===JSON_ERROR_NONE);
    }


    public function getall(){
        $query = LogEntry::select()->orderBy('id', 'desc');
        $logEntries = $query->get();
        $data = [
            'logEntries' => $logEntries,
        ];
        return view('index', $data);
    }

    public function vortex(Request $request){

    }
}
