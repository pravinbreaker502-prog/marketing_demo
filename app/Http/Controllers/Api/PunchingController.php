<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Employee,
    PunchingHistory
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PunchingController extends Controller
{
    public function PunchingStatusEntry(Request $request){
        if($request->type == 'facedetection'){
            return $this->FaceDetectionEntry($request);
        }else{
            return $this->OdoMeterDetectionEntry($request);
        }
    }
    
    function FaceDetectionEntry($request){
        try{
            $user = Session::get('user_details');
            if($request->punchtype != '1'){
                if($request->punchid){
                    $delete = PunchingHistory::where('id', $request->punchid)->delete();
                    if(!$delete){
                        return response()->json(['status' => "false", "message" => 'Failed to delete old data']);
                    }
                }
            }
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imagePath = 'uploads/' . $user->employee_name . uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('uploads/' . $user->employee_name), $imagePath);
            } else {
                $imagePath = NULL;
            }
            $arr = [
                'in_id' => $request->punchtype == '1' ? $request->punchid : NULL,
                'employee_id' => $user->id,
                'punch_type' => $request->punchtype ? $request->punchtype : '0',
                'faceimage_path' => $imagePath
                ];
            $insert = PunchingHistory::create($arr);
            return response()->json($insert ? ['status' => "true", "message" => "Successfully Face Detected..!", 'punchid' => (string)$insert->id] : ['status' => "false", "message" => "Failed to detect face..!"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function OdoMeterDetectionEntry($request){
        try{
            $user = Session::get('user_details');
            if(!$request->punchid){
                return response()->json(['status' => "false", "message" => 'Punchid is required']);
            }
            $get = PunchingHistory::where('id', $request->punchid)->first();
            if(!$get){
                return response()->json(['status' => "false", "message" => 'Punch history is not found']);
            }
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imagePath = 'uploads/' . $user->employee_name . uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('uploads/' . $user->employee_name), $imagePath);
                if ($get->odoimage_path && file_exists(public_path($get->odoimage_path))) {
                    unlink(public_path($get->odoimage_path));
                }
            } else {
                $imagePath = $user->odoimage_path;
            }
            $arr = [
                'odoimage_path' => $imagePath,
                'odometer_km' => $request->kmdriven
                ];
            $update = PunchingHistory::where('id', $request->punchid)->update($arr);
            return response()->json($update ? ['status' => "true", "message" => "Successfully Odometer Detected..!"] : ['status' => "false", "message" => "Failed to detect odometer..!"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    // public function OdometerHistory(Request $request){
    //     try{
    //         $user = Session::get('user_details');
    //         if($request->startdate != '' && $request->enddate != ''){
    //             $arr = ['status' => 'true'];
    //             $in = PunchingHistory::when($request->has(['startdate', 'enddate']), function($query) use ($request) {
    //                 $from_date = Carbon::parse($request->startdate)->startOfDay();  // Start of the day for from_date
    //                 $to_date = Carbon::parse($request->enddate)->endOfDay();        // End of the day for to_date
    //                 $query->whereBetween('updated_at', [$from_date, $to_date]);
    //             })
    //             ->where('employee_id', $user->id)
    //             ->where('punch_type', '0')
    //             ->get();
    //             // dd($in);
    //             $arr1 = $in->map(function($value) use ($request, $user) {
    //                 $out = PunchingHistory::where('employee_id', $user->id)->where('in_id', $value->id)->where('punch_type', '1')->first();
    //                 // dd($value);
    //               return [
    //                   'startkm' => (string)$value->odometer_km,
    //                   'endkm' =>(string) $out->odometer_km,
    //                   'totalkm' => (string)((int)$out->odometer_km - (int)$value->odometer_km),
    //                   ];
    //             });
    //             $arr['kmlist'] = $arr1;
    //             return response()->json($arr);
    //         }else{
    //             return response()->json(['status' => "false", "message" => 'Dates are required']);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => "false", "message" => $e->getMessage()]);
    //     }
    // }
    
    public function OdometerHistory(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->startdate != '' && $request->enddate != ''){
                $arr = ['status' => 'true'];
                $out = PunchingHistory::when($request->has(['startdate', 'enddate']), function($query) use ($request) {
                    $from_date = Carbon::parse($request->startdate)->startOfDay();  // Start of the day for from_date
                    $to_date = Carbon::parse($request->enddate)->endOfDay();        // End of the day for to_date
                    $query->whereBetween('updated_at', [$from_date, $to_date]);
                })
                ->where('employee_id', $user->id)
                ->where('punch_type', '1')
                ->get();
                // dd($in);
                $arr1 = $out->map(function($value) use ($request, $user) {
                    $in = PunchingHistory::where('employee_id', $user->id)->where('id', $value->in_id)->where('punch_type', '0')->first();
                    // dd($value);
                  return [
                      'startkm' => (string)$in->odometer_km,
                      'endkm' =>(string) $value->odometer_km,
                      'totalkm' => (string)((int)$value->odometer_km - (int)$in->odometer_km),
                      ];
                });
                $arr['kmlist'] = $arr1;
                return response()->json($arr);
            }else{
                return response()->json(['status' => "false", "message" => 'Dates are required']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
}
