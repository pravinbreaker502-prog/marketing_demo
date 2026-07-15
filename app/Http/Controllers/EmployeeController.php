<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer,
    Orders,
    Invoice,
    Employee
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

class EmployeeController extends Controller
{
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('marketing.employees.employee_list', compact('datas'));
    }
    
    public function CreatePage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('marketing.employees.add_employee', compact('datas'));
    }
    
    public function EditPage(Request $request)
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $employee = Employee::whereNull('deleted_at')->where('id', $request->id)->first();
        $countries = DB::table('countries')
                ->orderBy('id', 'ASC')
                ->get();
        $states = DB::table('states')
                ->where('country_id', $employee->employee_zone_country)
                ->orderBy('id', 'ASC')
                ->get();
         $cities = DB::table('cities')
                ->where('state_id', $employee->employee_zone_state)
                ->orderBy('id', 'ASC')
                ->get();
        return view('marketing.employees.edit_employee', compact('datas', 'employee', 'countries', 'states', 'cities'));
    }
    
    public function getEmployees(Request $request)
    {
        $employees = Employee::whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        return DataTables::of($employees)
            ->addColumn('DT_RowIndex', function ($employees) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('emply_name', function ($employees) {
                return '<p class="f-light">'.$employees->employee_name.'</p>';
            })
            ->addColumn('emply_id', function ($employees) {
                return '<p class="f-light">'.$employees->employee_id.'</p>';
            })
            ->addColumn('user_name', function ($employees) {
                return '<p class="f-light">'.$employees->username.'</p>';
            })
            ->addColumn('mobile', function ($employees) {
                return '<p class="f-light">'.$employees->employee_mobile.'</p>';
            })
            ->addColumn('email', function ($employees) {
                return '<p class="f-light">'.$employees->employee_email.'</p>';
            })
            ->addColumn('dateofbirth', function ($employees) {
                return '<p class="f-light">'.carbon::parse($employees->employee_dob)->format('d-m-Y').'</p>';
            })
            ->addColumn('typeof_emply', function ($employees) {
                return '<p class="f-light">'.$employees->employee_type.'</p>';
            })
            ->addColumn('actions', function ($employees) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="'.route('employees.edit', $employees->id).'"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-employee" data-id="' . $employees->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'emply_name', 'emply_id', 'user_name', 'mobile', 'email', 'dateofbirth', 'typeof_emply'])
            ->make(true);
    }
    
    public function createEmployees(Request $request)
    {
        try {
            $generalRules = [
                'employee_profile' => 'nullable|file|mimes:png,jpg,jpeg',
                'employee_name' => 'required|string|max:255',
                // 'employee_id' => 'required|string|max:255|unique:employees',
                'username' => 'required|string|max:255|unique:employees',
                'password' => 'required|string|min:8',
                'employee_email' => 'required|email|max:255|unique:employees',
                'employee_mobile' => ['required', 'regex:/^[6-9]\d{9}$/', 'unique:employees'],
                // 'employee_adhaar_doc' => 'required|file|mimes:jpg,png,jpeg,pdf',
                'employee_dob' => 'required|date',
                'employee_address' => 'required|string|max:500',
                // 'employee_qualification_doc' => 'required|file|mimes:pdf,jpg,png,jpeg',
                // 'employee_experience' => 'required',
                // 'employee_resume' => 'required|file|mimes:pdf,doc,docx',
                // 'employee_passbook_doc' => 'required|file|mimes:jpg,png,jpeg,pdf',
                // 'employee_pan_doc' => 'required|file|mimes:jpg,png,jpeg,pdf',
                'employee_type' => 'required|string',
            ];
            
            $salesExecutiveRules = [
                // 'vehichle_type' => 'required_if:employee_type,SalesExecutive|string',
                // 'vehichle_name' => 'required_if:employee_type,SalesExecutive|string|max:255',
                // 'vehichle_regno' => 'required_if:employee_type,SalesExecutive|string|max:255',
                // 'employee_zone_country' => 'required_if:employee_type,SalesExecutive|string',
                // 'employee_zone_state' => 'required_if:employee_type,SalesExecutive|string',
                // 'employee_zone_city' => 'required_if:employee_type,SalesExecutive|string',
                // 'employee_zone_pincode' => 'required_if:employee_type,SalesExecutive|string|max:10',
                // 'vehichle_license' => 'required_if:employee_type,SalesExecutive|file|mimes:jpg,png,jpeg,pdf',
                // 'vehichle_insurance' => 'required_if:employee_type,SalesExecutive|file|mimes:jpg,png,jpeg,pdf',
            ];
            
            $messages = [
                'employee_profile.file' => 'Profile image must be a file.',
                'employee_profile.mimes' => 'Profile image must be of type: png, jpg, jpeg.',
                'employee_name.required' => 'Employee Name is required.',
                // 'employee_id.required' => 'Employee ID is required.',
                'employee_id.unique' => 'Employee ID must be unique.',
                'username.required' => 'Username is required.',
                'username.unique' => 'Username must be unique.',
                'password.required' => 'Password is required and must be at least 8 characters.',
                'employee_email.required' => 'Email Address is required.',
                'employee_email.email' => 'Email Address must be a valid email format.',
                'employee_email.unique' => 'Email Address must be unique.',
                'employee_mobile.required' => 'The mobile number is required.',
                'employee_mobile.regex' => 'Mobile Number must be a valid 10-digit number starting with 6-9.',
                'employee_mobile.unique' => 'Mobile Number must be unique.',
                // 'employee_adhaar_doc.required' => 'Aadhar card is required.',
                'employee_adhaar_doc.file' => 'Aadhar card must be a file.',
                'employee_adhaar_doc.mimes' => 'Aadhar card must be of type: jpg, png, jpeg, pdf.',
                'employee_dob.required' => 'Date of Birth is required.',
                'employee_dob.date' => 'Date of Birth must be a valid date format.',
                'employee_address.required' => 'Address is required.',
                'employee_address.max' => 'Address may not be greater than 500 characters.',
                // 'employee_qualification_doc.required' => 'Qualification Documents are required.',
                // 'employee_qualification_doc.file' => 'Qualification Documents must be a file.',
                'employee_qualification_doc.mimes' => 'Qualification Documents must be of type: pdf, jpg, png, jpeg.',
                // 'employee_experience.required' => 'Work Experience is required.',
                // 'employee_resume.required' => 'Employee Resume is required.',
                'employee_resume.file' => 'Employee Resume must be a file.',
                'employee_resume.mimes' => 'Employee Resume must be of type: pdf, doc, docx.',
                // 'employee_passbook_doc.required' => 'Bank Passbook is required.',
                'employee_passbook_doc.file' => 'Bank Passbook must be a file.',
                'employee_passbook_doc.mimes' => 'Bank Passbook must be of type: jpg, png, jpeg, pdf.',
                // 'employee_pan_doc.required' => 'PAN Card is required.',
                'employee_pan_doc.file' => 'PAN Card must be a file.',
                'employee_pan_doc.mimes' => 'PAN Card must be of type: jpg, png, jpeg.',
                'employee_type.required' => 'Type of Employee is required.',
                'vehichle_type.required_if' => 'Vehicle Type is required for Sales Executive.',
                'vehichle_name.required_if' => 'Vehicle Name is required for Sales Executive.',
                'vehichle_name.max' => 'Vehicle Name may not be greater than 255 characters.',
                'vehichle_regno.required_if' => 'Vehicle Registration Number is required for Sales Executive.',
                'vehichle_regno.max' => 'Vehicle Registration Number may not be greater than 255 characters.',
                'employee_zone_country.required_if' => 'Working Zone Country is required for Sales Executive.',
                'employee_zone_state.required_if' => 'Working Zone State is required for Sales Executive.',
                'employee_zone_city.required_if' => 'Working Zone City is required for Sales Executive.',
                'employee_zone_pincode.required_if' => 'Working Zone Pincode is required for Sales Executive.',
                'employee_zone_pincode.max' => 'Working Zone Pincode may not be greater than 10 characters.',
                // 'vehichle_license.required_if' => 'License is required for Sales Executive.',
                'vehichle_license.file' => 'License must be a file.',
                'vehichle_license.mimes' => 'License must be of type: jpg, png, jpeg, pdf.',
                // 'vehichle_insurance.required_if' => 'Insurance is required for Sales Executive.',
                'vehichle_insurance.file' => 'Insurance must be a file.',
                'vehichle_insurance.mimes' => 'Insurance must be of type: jpg, png, jpeg, pdf.',
            ];

            
            if ($request->input('employee_type') == 'SalesExecutive') {
                $rules = array_merge($generalRules, $salesExecutiveRules);
            } else {
                $rules = $generalRules;
            }

            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd($request->all());
            $employeeName = $request->input('employee_name');
            $employeeBasePath = 'uploads/' . $employeeName;
            
            // Ensure the directory exists
            if (!file_exists(public_path($employeeBasePath))) {
                mkdir(public_path($employeeBasePath), 0755, true);
            }
            
            // Function to handle file storage
            function storeFile($file, $basePath) {
                if ($file) {
                    $filename = $file->getClientOriginalName();
                    $file->move(public_path($basePath), $filename);
                    return $basePath . '/' . $filename;
                }
                return null;
            }
            
            $employee_profile = storeFile($request->file('employee_profile'), $employeeBasePath);
            $employee_adhaar_doc = storeFile($request->file('employee_adhaar_doc'), $employeeBasePath);
            $employee_qualification_doc = storeFile($request->file('employee_qualification_doc'), $employeeBasePath);
            $employee_resume = storeFile($request->file('employee_resume'), $employeeBasePath);
            $employee_passbook_doc = storeFile($request->file('employee_passbook_doc'), $employeeBasePath);
            $employee_pan_doc = storeFile($request->file('employee_pan_doc'), $employeeBasePath);
            
            // Conditionally handle files for SalesExecutive type
            if ($request->input('employee_type') == 'SalesExecutive') {
                $vehichle_license = storeFile($request->file('vehichle_license'), $employeeBasePath);
                $vehichle_insurance = storeFile($request->file('vehichle_insurance'), $employeeBasePath);
            } else {
                $vehichle_license = null;
                $vehichle_insurance = null;
            }
                        
            
            $employee_slug = Str::slug($request->employee_name) . '-' . Str::random(4);
            $arr = [
                'employee_name' => $request->input('employee_name'),
                'page_slug' => $employee_slug,
                'employee_id' => $request->input('employee_id'),
                'username' => $request->input('username'),
                'password' => md5($request->input('password')),
                'employee_email' => $request->input('employee_email'),
                'employee_mobile' => $request->input('employee_mobile'),
                'employee_dob' => $request->input('employee_dob'),
                'employee_address' => $request->input('employee_address'),
                'employee_experience' => $request->input('employee_experience'),
                'employee_type' => $request->input('employee_type'),
                'vehichle_type' => $request->input('vehichle_type'),
                'vehichle_name' => $request->input('vehichle_name'),
                'vehichle_regno' => $request->input('vehichle_regno'),
                'employee_zone_country' => $request->input('employee_zone_country'),
                'employee_zone_state' => $request->input('employee_zone_state'),
                'employee_zone_city' => $request->input('employee_zone_city'),
                'employee_zone_pincode' => $request->input('employee_zone_pincode'),
                'employee_adhaar_doc' => $employee_adhaar_doc,
                'employee_qualification_doc' => $employee_qualification_doc,
                'employee_resume' => $employee_resume,
                'employee_passbook_doc' => $employee_passbook_doc,
                'employee_pan_doc' => $employee_pan_doc,
                'employee_profile' => $employee_profile
            ];
            if (isset($vehichle_license)) {
                $arr['vehichle_license'] = $vehichle_license;
            }
            
            if (isset($vehichle_insurance)) {
                $arr['vehichle_insurance'] = $vehichle_insurance;
            }
            // dd($arr);
            $insert = Employee::create($arr);
            $arr1 = [
                    'user_id' => $insert->id,
                    'username' => $request->input('username'),
                    'password' => md5($request->input('password')),
                    'user_type' => $request->input('employee_type')
                    ];
            $insert1 = User::create($arr1);
            if($insert && $insert1){
                return response()->json(['status' => 200, 'message' => 'Employee created successfully.']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to create employee.']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateEmployees(Request $request)
    {
        try {
            $generalRules = [
                'employee_profile' => 'nullable|file|mimes:png,jpg,jpeg',
                'employee_name' => 'required|string|max:255',
                'employee_id' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                // 'password' => 'required|string|min:8',
                'employee_email' => 'required|email|max:255',
                'employee_mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
                // 'employee_adhaar_doc' => 'required|file|mimes:jpg,png,jpeg,pdf',
                'employee_dob' => 'required|date',
                'employee_address' => 'required|string|max:500',
                // 'employee_qualification_doc' => 'required|file|mimes:pdf,jpg,png,jpeg,pdf',
                // 'employee_experience' => 'required',
                // 'employee_resume' => 'required|file|mimes:pdf,doc,docx',
                // 'employee_passbook_doc' => 'required|file|mimes:jpg,png,jpeg,pdf',
                // 'employee_pan_doc' => 'required|file|mimes:jpg,png,jpeg,pdf',
                'employee_type' => 'required|string',
            ];
            
            $salesExecutiveRules = [
                // 'vehichle_type' => 'required_if:employee_type,SalesExecutive|string',
                // 'vehichle_name' => 'required_if:employee_type,SalesExecutive|string|max:255',
                // 'vehichle_regno' => 'required_if:employee_type,SalesExecutive|string|max:255',
                // 'employee_zone_country' => 'required_if:employee_type,SalesExecutive|string',
                // 'employee_zone_state' => 'required_if:employee_type,SalesExecutive|string',
                // 'employee_zone_city' => 'required_if:employee_type,SalesExecutive|string',
                // 'employee_zone_pincode' => 'required_if:employee_type,SalesExecutive|string|max:10',
                // 'vehichle_license' => 'required_if:employee_type,SalesExecutive|file|mimes:jpg,png,jpeg,pdf',
                // 'vehichle_insurance' => 'required_if:employee_type,SalesExecutive|file|mimes:jpg,png,jpeg,pdf',
            ];
            
            $messages = [
                'employee_profile.file' => 'Profile image must be a file.',
                'employee_profile.mimes' => 'Profile image must be of type: png, jpg, jpeg.',
                'employee_name.required' => 'Employee Name is required.',
                'employee_id.required' => 'Employee ID is required.',
                'username.required' => 'User Name is required.',
                // 'password.required' => 'Password is required and must be at least 8 characters.',
                'employee_email.required' => 'Email Address is required.',
                'employee_email.email' => 'Email Address must be a valid email format.',
                'employee_mobile.required' => 'The client mobile number must be a valid 10-digit number.',
                'employee_mobile.digits' => 'Mobile Number must be 10 digits.',
                // 'employee_adhaar_doc.required' => 'Aadhar card is required and must be a valid image file.',
                // 'employee_adhaar_doc.file' => 'Aadhar card must be a file.',
                // 'employee_adhaar_doc.mimes' => 'Aadhar card must be of type: jpg, png, jpeg ,pdf.',
                'employee_dob.required' => 'Date of Birth is required.',
                'employee_dob.date' => 'Date of Birth must be a valid date format.',
                'employee_address.required' => 'Address is required.',
                // 'employee_qualification_doc.required' => 'Qualification Documents are required and must be a valid file.',
                // 'employee_qualification_doc.file' => 'Qualification Documents must be a file.',
                // 'employee_qualification_doc.mimes' => 'Qualification Documents must be of type: pdf, jpg, png, jpeg, pdf.',
                // 'employee_experience.required' => 'Work Experience is required.',
                // 'employee_experience.numeric' => 'Work Experience must be a number.',
                // 'employee_resume.required' => 'Employee Resume is required and must be a valid file.',
                // 'employee_resume.file' => 'Employee Resume must be a file.',
                // 'employee_resume.mimes' => 'Employee Resume must be of type: pdf, doc, docx.',
                // 'employee_passbook_doc.required' => 'Bank Passbook is required and must be a valid file.',
                // 'employee_passbook_doc.file' => 'Bank Passbook must be a file.',
                // 'employee_passbook_doc.mimes' => 'Bank Passbook must be of type: jpg, png, jpeg, pdf.',
                // 'employee_pan_doc.required' => 'PAN Card is required and must be a valid file.',
                // 'employee_pan_doc.file' => 'PAN Card must be a file.',
                // 'employee_pan_doc.mimes' => 'PAN Card must be of type: jpg, png, jpeg.',
                'employee_type.required' => 'Type of Employee is required.',
                'vehichle_type.required_if' => 'Vehicle Type is required for Sales Executive.',
                // 'vehichle_license.required_if' => 'License is required for Sales Executive and must be a valid image file.',
                // 'vehichle_license.file' => 'License must be a file.',
                // 'vehichle_license.mimes' => 'License must be of type: jpg, png, jpeg.',
                // 'vehichle_insurance.required_if' => 'Insurance is required for Sales Executive and must be a valid image file.',
                // 'vehichle_insurance.file' => 'Insurance must be a file.',
                // 'vehichle_insurance.mimes' => 'Insurance must be of type: jpg, png, jpeg.',
                'vehichle_name.required_if' => 'Vehicle Name is required for Sales Executive.',
                'vehichle_name.max' => 'Vehicle Name may not be greater than :max characters.',
                'vehichle_regno.required_if' => 'Vehicle Registration Number is required for Sales Executive.',
                'vehichle_regno.max' => 'Vehicle Registration Number may not be greater than :max characters.',
                'employee_zone_country.required_if' => 'Working Zone Country is required for Sales Executive.',
                'employee_zone_state.required_if' => 'Working Zone State is required for Sales Executive.',
                'employee_zone_city.required_if' => 'Working Zone City is required for Sales Executive.',
                'employee_zone_pincode.required_if' => 'Working Zone Pincode is required for Sales Executive and must be a valid pincode.',
                'employee_zone_pincode.max' => 'Working Zone Pincode may not be greater than :max characters.',
            ];
            
            if ($request->input('employee_type') == 'SalesExecutive') {
                $rules = array_merge($generalRules, $salesExecutiveRules);
            } else {
                $rules = $generalRules;
            }

            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $employee = Employee::where('id', $request->id)->first();
            // dd($request->all());
            $employeeName = $request->input('employee_name');
            $employeeBasePath = 'uploads/' . $employeeName;
            
            // Ensure the directory exists
            if (!file_exists(public_path($employeeBasePath))) {
                mkdir(public_path($employeeBasePath), 0755, true);
            }
            
            // Function to handle file storage
            function storeFile($file, $basePath, $old_path) {
                if ($file) {
                    $filename = $file->getClientOriginalName();
                    $file->move(public_path($basePath), $filename);
                    return $basePath . '/' . $filename;
                }
                return $old_path;
            }
            
            $employee_profile = storeFile($request->file('employee_profile'), $employeeBasePath, $employee->employee_profile);
            $employee_adhaar_doc = storeFile($request->file('employee_adhaar_doc'), $employeeBasePath, $employee->employee_adhaar_doc);
            $employee_qualification_doc = storeFile($request->file('employee_qualification_doc'), $employeeBasePath, $employee->employee_qualification_doc);
            $employee_resume = storeFile($request->file('employee_resume'), $employeeBasePath, $employee->employee_resume);
            $employee_passbook_doc = storeFile($request->file('employee_passbook_doc'), $employeeBasePath, $employee->employee_passbook_doc);
            $employee_pan_doc = storeFile($request->file('employee_pan_doc'), $employeeBasePath, $employee->employee_pan_doc);
            
            // Conditionally handle files for SalesExecutive type
            if ($request->input('employee_type') == 'SalesExecutive') {
                $vehichle_license = storeFile($request->file('vehichle_license'), $employeeBasePath, $employee->vehichle_license);
                $vehichle_insurance = storeFile($request->file('vehichle_insurance'), $employeeBasePath, $employee->vehichle_insurance);
            } else {
                $vehichle_license = null;
                $vehichle_insurance = null;
            }
                        
            
            $employee_slug = Str::slug($request->employee_name) . '-' . Str::random(4);
            $arr = [
                'employee_name' => $request->input('employee_name'),
                'page_slug' => $employee_slug,
                'employee_id' => $request->input('employee_id'),
                'username' => $request->input('username'),
                'employee_email' => $request->input('employee_email'),
                'employee_mobile' => $request->input('employee_mobile'),
                'employee_dob' => $request->input('employee_dob'),
                'employee_address' => $request->input('employee_address'),
                'employee_experience' => $request->input('employee_experience'),
                'employee_type' => $request->input('employee_type'),
                'employee_adhaar_doc' => $employee_adhaar_doc,
                'employee_qualification_doc' => $employee_qualification_doc,
                'employee_resume' => $employee_resume,
                'employee_passbook_doc' => $employee_passbook_doc,
                'employee_pan_doc' => $employee_pan_doc,
                'employee_profile' => $employee_profile
            ];
            $arr1 = [
                'username' => $request->input('username'),
                'user_type' => $request->input('employee_type'),
                ];
            if ($request->input('password') != null) {
                $arr['password'] = md5($request->input('password'));
                $arr1['password'] = md5($request->input('password'));
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['vehichle_type'] = $request->input('vehichle_type');
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['vehichle_name'] = $request->input('vehichle_name');
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['vehichle_regno'] = $request->input('vehichle_regno');
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['employee_zone_country'] = $request->input('employee_zone_country');
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['employee_zone_state'] = $request->input('employee_zone_state');
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['employee_zone_city'] = $request->input('employee_zone_city');
            }
            if ($request->input('employee_type') == 'SalesExecutive') {
                $arr['employee_zone_pincode'] = $request->input('employee_zone_pincode');
            }
            if (isset($vehichle_license)) {
                $arr['vehichle_license'] = $vehichle_license;
            }
            
            if (isset($vehichle_insurance)) {
                $arr['vehichle_insurance'] = $vehichle_insurance;
            }
            // dd($arr);
            $update = Employee::where('id', $request->id)->update($arr);
            $update1 = User::where('user_id', $request->id)->update($arr1);
            if($update && $update1){
                return response()->json(['status' => 200, 'message' => 'Employee updated successfully.']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update employee.']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteEmployees(Request $request){
        try {
            $rules = [
                    'id' => ['required']
                ];
            $messages = [
                    'id.required' => 'The employee id is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $delete = Employee::where('id', $request->id)->update(['deleted_at' => now()]);
            if($delete){
                return response()->json(['status' => 200, 'message' => 'Employee has been deleted successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete employee']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
}
