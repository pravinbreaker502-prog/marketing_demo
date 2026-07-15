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

class TrainersController extends Controller
{
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $trainers = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'Trainer')->orderBy('id', 'DESC')->get();
        return view('marketing.assign_trainers.index', compact('datas', 'customers', 'trainers'));
    }
    
    public function CreatePage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $trainers = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'Trainer')->orderBy('id', 'DESC')->get();
        return view('marketing.assign_trainers.create', compact('datas', 'customers', 'trainers'));
    }
    
    public function EditPage(Request $request)
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $trainers = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'Trainer')->orderBy('id', 'DESC')->get();
        $employees_withassigns = AssignTrainers::whereNull('deleted_at')->where('id', $request->id)->first();
        return view('marketing.assign_trainers.edit', compact('datas', 'customers', 'trainers', 'employees_withassigns'));
    }
    
    public function getAssignTrainers(Request $request)
    {
        $employees_withassigns = AssignTrainers::select('assign_trainers_toschool.*', 'employees.employee_name', 'customers.company_name')
            ->leftJoin('customers', 'customers.id', '=', 'assign_trainers_toschool.customer_id')
            ->leftJoin('employees', 'employees.id', '=', 'assign_trainers_toschool.trainer_id')
            ->whereNull('assign_trainers_toschool.deleted_at')
            ->when($request->customer, function ($query) use ($request) {
                $query->where('assign_trainers_toschool.customer_id', $request->customer);
            })
            ->when($request->filter_process_status, function ($query) use ($request) {
                $query->where('assign_trainers_toschool.process_status', 'LIKE', $request->filter_process_status);
            })
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
            ->addColumn('no_of_teacherssss', function ($employees_withassigns) {
                return '<p class="f-light">'.($employees_withassigns->no_of_teachers != NULL ? $employees_withassigns->no_of_teachers : '-').'</p>';
            })
            ->addColumn('pros_status', function ($employees_withassigns) {
                switch(strtolower($employees_withassigns->process_status)){
                    case 'pending':
                        $class = 'info';
                        break;
                    case 'on_progress':
                        $class = 'primary';
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
            ->addColumn('actions', function ($employees_withassigns) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="'.route('assigned-trainers.edit', $employees_withassigns->id).'"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-assigned_trainer" data-id="' . $employees_withassigns->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'pros_status', 'no_of_teacherssss', 'to_date', 'from_date', 'emply_name', 'cmpny_name'])
            ->make(true);
    }
    
    public function createAssignTrainers(Request $request)
    {
        try {
            $rules = [
                'customer' => 'required',
                'trainers' => 'required|array|min:1',
                'trainers.*' => 'string',
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ];
            
            $messages = [
                'customer.required' => 'The Customer is required.',
                'trainers.required' => 'The Trainers are required.',
                'from_date.required' => 'The from date is required.',
                'from_date.date' => 'The from date must be in date format.',
                'to_date.required' => 'The to date is required.',
                'to_date.date' => 'The to date must be in date format.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            
            $arr = [];
            for($i = 0; $i < count($request->trainers); $i++){
                $arr[] = [
                    'customer_id' => $request->customer,
                    'trainer_id' => $request->trainers[$i],
                    'assigned_from' => Carbon::parse($request->from_date)->format('Y-m-d H:i:s'),
                    'assigned_end' => Carbon::parse($request->to_date)->format('Y-m-d H:i:s'),
                    'process_status' => 'pending'
                ];
            }
            // dd($arr);
            $insert = AssignTrainers::insert($arr);
            if($insert){
                return response()->json(['status' => 200, 'message' => 'Trainers assigned successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to assign trainers']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateAssignTrainers(Request $request)
    {
        try {
            $rules = [
                'customer' => 'required',
                'trainers' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ];
            
            $messages = [
                'customer.required' => 'The Customer is required.',
                'trainers.required' => 'The Trainers are required.',
                'from_date.required' => 'The from date is required.',
                'from_date.date' => 'The from date must be in date format.',
                'to_date.required' => 'The to date is required.',
                'to_date.date' => 'The to date must be in date format.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            
            $arr = [
                'customer_id' => $request->customer,
                'trainer_id' => $request->trainers,
                'assigned_from' => Carbon::parse($request->from_date)->format('Y-m-d H:i:s'),
                'assigned_end' => Carbon::parse($request->to_date)->format('Y-m-d H:i:s'),
                'updated_at' => now()
            ];
            // dd($arr);
            $update = AssignTrainers::where('id', $request->id)->update($arr);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Trainers updated successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update trainers']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteAssignTrainers(Request $request){
        try {
            $rules = [
                    'id' => ['required']
                ];
            $messages = [
                    'id.required' => 'The assigned trainer id is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $delete = AssignTrainers::where('id', $request->id)->delete();
            if($delete){
                return response()->json(['status' => 200, 'message' => 'Assigned trainer has been deleted successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete trainer']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
}
