<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Customer,
    Employee,
    FollowupHistory
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class FollowupHistoryController extends Controller
{
    public function SaveFollowupHistory(Request $request){
        try{
            $user = Session::get('user_details');
            $arr = [
                'employee_id' => $user->id,
                'customer_id' => $request->clientid,
                'sample_ids' => ($request->sampleids != '' || $request->sampleids != null ? json_encode($request->sampleids) : NULL),
                'order_ids' => (($request->orderids != '' || $request->orderids != null) && $request->interestlay == 'interested' ? json_encode($request->orderids) : NULL),
                'in_time' => $request->intime,
                'out_time' => $request->outtime,
                'accept_status' => ($request->interestlay == 'interested' ? '1' : ($request->interestlay == 'notinterested' ? '2' : '0')),
                'followup_date' => (($request->followupdate != '' || $request->followupdate != null) && $request->interestlay == 'interested' ? Carbon::parse($request->followupdate)->format('Y-m-d') : NULL),
                'training' => ($request->training == 'yes' ? '1' : ($request->training == 'no' ? '2' : '0')),
                ];
            // dd($arr);
            // return response()->json($arr);
            $insert = FollowupHistory::create($arr);
            return response()->json($insert ? ['status' => "true", "message" => "Saved successfully"] : ['status' => "false", "message" => "Failed to save..!"]);
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function GetFollowupHistory(Request $request){
        try{
            $user = Session::get('user_details');
            $get = FollowupHistory::when($request->period, function ($query) use ($request) {
                try {
                    $period = explode('-', $request->period);
                    if (count($period) === 2) {
                        $month = $period[0];
                        $year = $period[1];
                        $from = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
                        $to = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
                        $query->whereBetween('followup_date', [$from, $to]);
                    } else {
                        return response()->json(['status' => 'false', 'message' => "Invalid period format"]);
                    }
                } catch (\Exception $e) {
                    return response()->json(['status' => 'false', 'message' => "Invalid period format: " . $e->getMessage()]);
                }
            })
            ->where('accept_status', '1')
            ->where('followup_date', '!=', NULL)
            ->where('order_ids', '=', NULL)
            ->orderBy('id', 'DESC')->get();
            $arr = ['status' => 'true'];
            $arr1 = $get->map(function($value) use ($request, $user) {
                $cus = Customer::where('id', $value->customer_id)->first();
               return [
                   'schoolid' => $cus ? (string)$cus->id : '',
                   'schoolname' => $cus ? $cus->company_name : '',
                   'date' => $value->followup_date != null ? Carbon::parse($value->followup_date)->format('d-m-Y') : "",
                   'followupid' => (string)$value->id
                   ];
            });
            $arr['followuplist'] = $arr1;
            return response()->json($arr);
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function EditnUpdateFollowupHistory(Request $request){
        try{
            $user = Session::get('user_details');
            $get = FollowupHistory::where('id', $request->followupid)->first();
            if($get){
                $arr = [
                    'order_ids' => (($request->orderids != '' || $request->orderids != null) && $request->interestlay == 'interested' ? json_encode($request->orderids) : NULL),
                    'accept_status' => ($request->interestlay == 'interested' ? '1' : ($request->interestlay == 'notinterested' ? '2' : '0')),
                    'followup_date' => (($request->followupdate != '' || $request->followupdate != null) && $request->interestlay == 'interested' ? Carbon::parse($request->followupdate)->format('Y-m-d') : NULL)
                    ];
                $update = FollowupHistory::where('id', $request->followupid)->update($arr);
                return response()->json($update ? ['status' => "true", "message" => "Saved successfully"] : ['status' => "false", "message" => "Failed to save..!"]);
            }else{
                return response()->json(['status' => "false", "message" => "Data not found"]);
            }
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function GetSchoolVisits(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->type == 'followup'){
                $get = FollowupHistory::when($request->period, function ($query) use ($request) {
                    try {
                        $period = explode('-', $request->period);
                        if (count($period) === 2) {
                            $month = $period[0];
                            $year = $period[1];
                            $from = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
                            $to = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
                            $query->whereBetween('created_at', [$from, $to]);
                        } else {
                            return response()->json(['status' => 'false', 'message' => "Invalid period format"]);
                        }
                    } catch (\Exception $e) {
                        return response()->json(['status' => 'false', 'message' => "Invalid period format: " . $e->getMessage()]);
                    }
                })
                ->where('accept_status', '1')
                ->where('followup_date', '!=', NULL)
                ->orderBy('id', 'DESC')->get();
            }else{
                $get = FollowupHistory::when($request->period, function ($query) use ($request) {
                    try {
                        $period = explode('-', $request->period);
                        if (count($period) === 2) {
                            $month = $period[0];
                            $year = $period[1];
                            $from = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
                            $to = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
                            $query->whereBetween('created_at', [$from, $to]);
                        } else {
                            return response()->json(['status' => 'false', 'message' => "Invalid period format"]);
                        }
                    } catch (\Exception $e) {
                        return response()->json(['status' => 'false', 'message' => "Invalid period format: " . $e->getMessage()]);
                    }
                })
                ->where('accept_status', '2')
                ->where('followup_date', '=', NULL)
                ->orderBy('id', 'DESC')->get();
            }
            $arr = ['status' => 'true'];
            $arr1 = $get->map(function($value) use ($request, $user) {
                $cus = Customer::where('id', $value->customer_id)->first();
               return [
                   'schoolname' => $cus ? $cus->company_name : '',
                   'schoolmailid' => $cus ? $cus->client_email : '',
                   'phonenumber' => $cus ? (string)$cus->client_mobile : '',
                   'address' => $cus ? $cus->client_address : '',
                   'date' => $value->followup_date != null ? Carbon::parse($value->followup_date)->format('d-m-Y') : Carbon::parse($value->updated_at)->format('d-m-Y'),
                   ];
            });
            $arr['schoollist'] = $arr1;
            return response()->json($arr);
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
}
