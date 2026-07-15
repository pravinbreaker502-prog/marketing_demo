<?php

namespace App\Http\Controllers\Trainers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer,
    Orders,
    Invoice,
    Employee,
    AssignTrainers
    };
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Services\PDFGeneratorService;
use Illuminate\Support\Facades\File;

class TrainersFollowController extends Controller
{
    public function TrainersPendingIndex()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('trainer.trainer_pending', compact('datas'));
    }
    
    public function TrainersProcessIndex()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('trainer.trainer_process', compact('datas'));
    }
    
    public function TrainersCompletedIndex()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('trainer.trainer_completed', compact('datas'));
    }
    
    public function TrainersCancelledIndex()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('trainer.trainer_cancel_works', compact('datas'));
    }
    
    public function getAssignTrainersByStatus(Request $request)
    {
        if($request->table_type == 'completed'){
            $process_status = 'completed';
        }else if($request->table_type == 'on_progress'){
            $process_status = 'on_progress';
        }else if($request->table_type == 'cancelled'){
            $process_status = 'cancelled';
        }else if($request->table_type == 'pending'){
            $process_status = 'pending';
        }else {
            $process_status = 'pending';
        }
        $employees_withassigns = AssignTrainers::select('assign_trainers_toschool.*', 'employees.employee_name', 'customers.company_name')
            ->leftJoin('customers', 'customers.id', '=', 'assign_trainers_toschool.customer_id')
            ->leftJoin('employees', 'employees.id', '=', 'assign_trainers_toschool.trainer_id')
            ->whereNull('assign_trainers_toschool.deleted_at')
            ->when($request->customer, function ($query) use ($request) {
                $query->where('assign_trainers_toschool.customer_id', $request->customer);
            })
            ->where('assign_trainers_toschool.process_status', 'LIKE', $process_status)
            ->when($request->employee_filter, function ($query) use ($request) {
                $query->where('assign_trainers_toschool.trainer_id', $request->employee_filter);
            })
            ->when($request->from_date && $request->to_date, function ($query) use ($request) {
                $from_date = Carbon::parse($request->from_date)->startOfDay()->format('Y-m-d H:i:s');
                $to_date = Carbon::parse($request->to_date)->endOfDay()->format('Y-m-d H:i:s');
                $query->whereBetween('assign_trainers_toschool.created_at', [$from_date, $to_date]);
            })
            ->orderBy('assign_trainers_toschool.id', 'DESC')
            ->get();
            // dd($employees_withassigns);
        return DataTables::of($employees_withassigns)
            ->addColumn('DT_RowIndex', function ($employees_withassigns) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('cmpny_name', function ($employees_withassigns) {
                return '<p class="f-light">'.$employees_withassigns->company_name.'</p>';
            })
            ->addColumn('emply_name', function ($employees_withassigns) {
                return '<p class="f-light">'.$employees_withassigns->employee_name.'</p>';
            })
            ->addColumn('from_date', function ($employees_withassigns) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($employees_withassigns->assigned_from)->format('d-m-Y').'</span>';
            })
            ->addColumn('to_date', function ($employees_withassigns) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($employees_withassigns->assigned_end)->format('d-m-Y').'</span>';
            })
            ->addColumn('started_date', function ($employees_withassigns) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($employees_withassigns->started_from)->format('d-m-Y').'</span>';
            })
            ->addColumn('end_date', function ($employees_withassigns) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($employees_withassigns->training_end)->format('d-m-Y').'</span>';
            })
            ->addColumn('no_of_teacherssss', function ($employees_withassigns) {
                return '<p class="f-light">'.($employees_withassigns->no_of_teachers != NULL ? $employees_withassigns->no_of_teachers : '-').'</p>';
            })
            ->addColumn('reject_reason', function ($employees_withassigns) {
                return '<textarea row="1" col="1">'.($employees_withassigns->reason != NULL ? $employees_withassigns->reason : '-').'</textarea>';
            })
            ->addColumn('pros_status', function ($employees_withassigns) {
                switch(strtolower($employees_withassigns->process_status)){
                    case 'pending':
                        $class = 'info';
                        break;
                    case 'on_progress':
                        $class = 'primary';
                        break;
                    case 'cancelled':
                        $class = 'danger';
                        break;
                    case 'completed':
                        $class = 'success';
                        break;
                    default:
                        $class = 'placeholder';
                        break;
                }
                return '<span class="badge badge-light-'.$class.'">'.$employees_withassigns->process_status.'</span>';
            })
            ->addColumn('actions', function ($employees_withassigns) use ($request, $process_status) {
                if($request->table_type == 'on_progress'){
                    $process_status1 = 'completed';
                }else if($request->table_type == 'pending'){
                    $process_status1 = 'on_progress';
                }else if($request->table_type == 'cancelled'){
                    $process_status1 = 'cancelled';
                }else {
                    $process_status1 = 'pending';
                }
                if($request->table_type == 'on_progress'){
                    $acc_btn = '<a class="btn btn-outline-primary" href="javascript:OpenNoOfTeachersModel('.$employees_withassigns->id.')"><i class="fa fa-check"></i></a>&nbsp
                              <a class="btn btn-outline-danger" href="javascript:OpenRejectReasonModal('.$employees_withassigns->id.')"><i class="fa fa-close"></i></a>';
                }else{
                    $acc_btn = '<a class="btn btn-outline-primary" href="javascript:ChangeStatusOfTraineeWorks(\''.$process_status1.'\', '.$employees_withassigns->id.')"><i class="fa fa-check"></i></a>&nbsp
                              <a class="btn btn-outline-danger" href="javascript:OpenRejectReasonModal('.$employees_withassigns->id.')"><i class="fa fa-close"></i></a>';
                }
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                              '.$acc_btn.'
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'pros_status', 'no_of_teacherssss', 'to_date', 'from_date', 'emply_name', 'cmpny_name', 'started_date', 'end_date', 'reject_reason'])
            ->make(true);
    }
    
    public function updateStatusOfTrainersWorks(Request $request){
        try {
            $rules = [
                    'id' => ['required'],
                    'status' => ['required']
                ];
            $messages = [
                    'id.required' => 'The assigned trainer id is required.',
                    'status.required' => 'The satus of trainer\'s work is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            if($request->status == 'completed'){
                $process_status = 'completed';
                $column = 'training_end';
            }else if($request->status == 'on_progress'){
                $process_status = 'on_progress';
                $column = 'started_from';
            }else if($request->status == 'canceled'){
                $process_status = 'canceled';
                $column = 'updated_at';
            }else if($request->status == 'pending'){
                $process_status = 'pending';
                $column = 'updated_at';
            }else {
                $process_status = 'pending';
                $column = 'updated_at';
            }
            // dd($process_status);
            $update = AssignTrainers::where('id', $request->id)->update(['process_status' => $process_status, $column => now()]);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Successfully Updated']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function RejectAndUpdateReason(Request $request){
        try {
            $rules = [
                    'id' => ['required'],
                    'reason' => ['required']
                ];
            $messages = [
                    'id.required' => 'The assigned trainer id is required.',
                    'reason.required' => 'The rejection reason is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd($process_status);
            $update = AssignTrainers::where('id', $request->id)->update(['process_status' => 'cancelled', 'reason' => $request->reason, 'updated_at' => now()]);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Successfully Rejected']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to reject']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function CompleteAndUpdateNoOfTeachers(Request $request){
        try {
            $rules = [
                    'id' => ['required'],
                    'no_of_teachers' => ['required']
                ];
            $messages = [
                    'id.required' => 'The assigned trainer id is required.',
                    'no_of_teachers.required' => 'The no.of teachers is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd($process_status);
            $update = AssignTrainers::where('id', $request->id)->update(['process_status' => 'completed', 'no_of_teachers' => $request->no_of_teachers, 'training_end' => now(), 'updated_at' => now()]);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Successfully completed']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to completed']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
}
