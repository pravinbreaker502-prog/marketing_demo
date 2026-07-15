<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Employee,
    LeaveRequests,
    PunchingHistory
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class LeaveRequestController extends Controller
{
    public function LeaveRequestsEntry(Request $request){
        if($request->type == 'cancelleave'){
            return $this->CancelLeaveRequest($request);
        }else{
            return $this->AddLeaveRequests($request);
        }
    }
    
    function AddLeaveRequests($request){
        try{
            $user = Session::get('user_details');
            // dd($user);
            if($request->leavetype == 'holdinginterval'){
                $start_time = Carbon::parse($request->startdate);
                $end_time = Carbon::parse($request->enddate);
                $differenceInMinutes_check = (int)$start_time->diffInMinutes($end_time);
                $differenceInMinutes = (string)(int)$start_time->diffInMinutes($end_time);
                if($differenceInMinutes_check > 4 && $differenceInMinutes_check < 11){
                    $title = 'Alert';
                    $message = 'you are spending around more than '.$differenceInMinutes.' minutes at same location';
                    $type = 'admin';
                    $sendIds = [
                        $user->fcm_token
                        ];
                    PushNotification($title, $message, $type, $sendIds);
                }
            }
            // dd('sent');
            $arr = [
                'employee_id' => $user->id,
                'leave_type' => ($request->leavetype == 'fullday' ? '0' : ($request->leavetype == 'halfday' ? '1' : ($request->leavetype == 'permission' ? '2' : ($request->leavetype == 'holdinginterval' ? '3' : '')))),
                'from_date' => Carbon::parse($request->startdate)->format('Y-m-d H:i:s'),
                'to_date' => Carbon::parse($request->enddate)->format('Y-m-d H:i:s'),
                'leave_reason' => $request->reason,
                'status' => '0'
                ];
            $insert = LeaveRequests::create($arr);
            return response()->json($insert ? ['status' => "true", "message" => "Request sent..!", 'leaveid' => (string)$insert->id] : ['status' => "false", "message" => "Failed to sent request..!"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function CancelLeaveRequest($request){
        try{
            $user = Session::get('user_details');
            if(!$request->leaveid){
                return response()->json(['status' => "false", "message" => 'Leave Id is required']);
            }
            $arr = [
                'reject_reason' => $request->cancelreason,
                'status' => '3'
                ];
            $update = LeaveRequests::where('id', $request->leaveid)->update($arr);
            return response()->json($update ? ['status' => "true", "message" => "Successfully leave has been cancelled..!"] : ['status' => "false", "message" => "Failed to cancel leave..!"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function LeaveHistory(Request $request){
        try{
            $user = Session::get('user_details');
            $arr = [
                'status' => 'true',
                'fullday' => LeaveRequests::where('leave_type', '0')->where('employee_id', $user->id)->count(),
                'permission' => LeaveRequests::where('leave_type', '1')->where('employee_id', $user->id)->count(),
                'halfday' => LeaveRequests::where('leave_type', '2')->where('employee_id', $user->id)->count(),
                'approved' => LeaveRequests::where('status', '1')->where('employee_id', $user->id)->count(),
                'pending' => LeaveRequests::where('status', '0')->where('leave_type', '!=', '3')->where('employee_id', $user->id)->count(),
                'rejected' => LeaveRequests::where('status', '2')->where('employee_id', $user->id)->count(),
                'cancelled' => LeaveRequests::where('status', '3')->where('employee_id', $user->id)->count(),
                'latepunches' => PunchingHistory::where('punch_type', '0')->where('employee_id', $user->id)->whereTime('created_at', '>', '09:00:00')->whereTime('created_at', '<', '12:00:00')->count()
                ];
            $leave_requests = LeaveRequests::where('leave_type', '!=', '3')->when($request->period, function ($query) use ($request, $user) {
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
                                ->where('employee_id', $user->id)->get();
            $arr1 = $leave_requests->map(function($value) use ($request, $user) {
               return [
                   'leaveid' => (string)$value->id,
                   'type' => ($value->leave_type == '0' ? 'fullday' : ($value->leave_type == '1' ? 'halfday' : ($value->leave_type == '2' ? 'permission' : ''))),
                   'status' => ($value->status == '1' ? 'accepted' : ($value->status == '2' ? 'rejected' : ($value->status == '3' ? 'cancelled' : 'pending'))),
                   'fromdate' => Carbon::parse($value->from_date)->format('d-m-Y'),
                   'enddate' => Carbon::parse($value->to_date)->format('d-m-Y'),
                   'totaldays' => (string)Carbon::parse($value->from_date)->diffInDays(Carbon::parse($value->to_date)),
                   'reason' => $value->leave_reason ? $value->leave_reason : '',
                   ]; 
            })->toArray();
            $arr['leavehistory'] = $arr1;
            $late_punches = PunchingHistory::when($request->period, function ($query) use ($request, $user) {
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
                            })->where('punch_type', '0')->where('employee_id', $user->id)->whereTime('created_at', '>', '09:00:00')->whereTime('created_at', '<', '12:00:00')->get();
            $arr2 = $late_punches->map(function($value) use ($request, $user) {
               return [
                   'date' => Carbon::parse($value->created_at)->format('d-m-Y'),
                   'time' => Carbon::parse($value->created_at)->format('H:i A')
                   ]; 
            })->toArray();
            $arr['latepunchlist'] = $arr2;
            return response()->json($arr);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
}
