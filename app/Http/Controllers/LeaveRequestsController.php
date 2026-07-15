<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Customer,
    Employee,
    LeaveRequests
    };
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Mpdf\Mpdf;
use App\Services\PDFGeneratorService;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Str;

class LeaveRequestsController extends Controller
{
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('marketing.leaverequests.index', compact('datas'));
    }
    
    public function getLeaveRequests(Request $request)
    {
        $leaverequest = LeaveRequests::whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        return DataTables::of($leaverequest)
            ->addColumn('DT_RowIndex', function ($leaverequest) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('emply_name', function ($leaverequest) {
                $employee = Employee::whereNull('deleted_at')->where('id', $leaverequest->employee_id)->first();
                $emp_name = $employee ? $employee->employee_name : 'N/A'; // or handle the null case as needed
                return '<p class="f-light">'.$emp_name.'</p>';
            })
            ->addColumn('emply_type', function ($leaverequest) {
                $employee = Employee::whereNull('deleted_at')->where('id', $leaverequest->employee_id)->first();
                $emp_type = $employee ? $employee->employee_type : 'N/A'; // or handle the null case as needed
                return '<p class="f-light">'.$emp_type.'</p>';
            })
            ->addColumn('leavetype', function ($leaverequest) {
                return '<p class="f-light">'.($leaverequest->leave_type == '0' ? 'Fullday' : ($leaverequest->leave_type == '1' ? 'Halfday' : ($leaverequest->leave_type == '2' ? 'Permission' : ''))).'</p>';
            })
            ->addColumn('leavereason', function ($leaverequest) {
                return '<textarea cols="15" rows="2" readonly>'.($leaverequest->leave_reason ? $leaverequest->leave_reason : '-').'</textarea>';
            })
            ->addColumn('fromdate', function ($leaverequest) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($leaverequest->from_date)->format('d-m-Y').'</span>';
            })
            ->addColumn('todate', function ($leaverequest) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($leaverequest->to_date)->format('d-m-Y').'</span>';
            })
            ->addColumn('rejectreason', function ($leaverequest) {
                return '<textarea cols="15" rows="2" readonly>'.($leaverequest->status == '2' && $leaverequest->reject_reason != null ? $leaverequest->reject_reason : '-').'</textarea>';
            })
            ->addColumn('actions', function ($leaverequest) {
                switch(strtolower($leaverequest->status)){
                    case '0':
                        $html = '<p class="f-light">
                                  <ul id="t-1" class="action simple-list flex-row list-group">
                                    <li class="edit list-group-item"> <a href="Javascript:AcceptLeaveRequest('.$leaverequest->id.');"><i class="fa fa-check"></i></a></li>
                                    <li class="delete list-group-item"> <a href="Javascript:OpenReasonModal('.$leaverequest->id.');"><i class="fa fa-close"></i></a></li>
                                  </ul>
                                </p>';
                        break;
                    case '1':
                        $html = '<button type="button" class="btn btn-success">Accepted</button>';
                        break;
                    case '2':
                        $html = '<button type="button" class="btn btn-danger">Rejected</button>';
                        break;
                    case '3':
                        $html = '<button type="button" class="btn btn-danger">Cancelled</button>';
                        break;
                    default:
                        $html = '<p class="f-light">
                                  <ul id="t-1" class="action simple-list flex-row list-group">
                                    <li class="edit list-group-item"> <a href="Javascript:void(0);"><i class="fa fa-check"></i></a></li>
                                    <li class="delete list-group-item"> <a href="Javascript:void(0);"><i class="fa fa-close"></i></a></li>
                                  </ul>
                                </p>';
                        break;
                }
                return $html;
            })

            ->rawColumns(['emply_name', 'emply_type', 'leavetype', 'leavereason', 'fromdate', 'todate', 'rejectreason', 'actions'])
            ->make(true);
    }
    
    public function acceptLeaveRequest(Request $request){
        try{
            $update = LeaveRequests::where('id', $request->id)->update(['status' => '1', 'updated_at' => now()]);
            return response()->json($update ? ['status' => 200, 'message' => 'Request has bee accepted successfully..!'] : ['status' => 400, 'message' => 'Failed to accept request..!']);
        } catch(\Exception $e){
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }
    
    public function rejectLeaveRequest(Request $request){
        try{
            $update = LeaveRequests::where('id', $request->id)->update(['status' => '2', 'reject_reason' => $request->reason, 'updated_at' => now()]);
            return response()->json($update ? ['status' => 200, 'message' => 'Request has bee rejected successfully..!'] : ['status' => 400, 'message' => 'Failed to reject request..!']);
        } catch(\Exception $e){
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }
}