<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer
    };
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('marketing.client.client', compact('datas'));
    }
    
    public function CreatePage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('marketing.client.add_client', compact('datas'));
    }
    
    public function EditPage(Request $request)
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customer = Customer::whereNull('deleted_at')->where('id', $request->id)->first();
        return view('marketing.client.edit_client', compact('datas', 'customer'));
    }
    
    public function getCustomers(Request $request)
    {
        $customers = Customer::whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        return DataTables::of($customers)
            ->addColumn('DT_RowIndex', function ($customers) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('cmpny_name', function ($customers) {
                return '<p class="f-light">'.$customers->company_name.'</p>';
            })
            ->addColumn('clnt_name', function ($customers) {
                return '<p class="f-light">'.$customers->client_name.'</p>';
            })
            ->addColumn('clnt_email', function ($customers) {
                return '<p class="f-light">'.$customers->client_email.'</p>';
            })
            ->addColumn('clnt_mobile', function ($customers) {
                return '<p class="f-light">'.$customers->client_mobile.'</p>';
            })
            ->addColumn('clnt_address', function ($customers) {
                return '<p class="f-light">'.$customers->client_address.'</p>';
            })
            ->addColumn('gst_no', function ($customers) {
                return '<p class="f-light">'.$customers->gst_no.'</p>';
            })
            ->addColumn('actions', function ($customers) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="'.route('customers.edit', $customers->id).'"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-customer" data-id="' . $customers->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'cmpny_name', 'clnt_name', 'clnt_email', 'clnt_mobile', 'clnt_address', 'gst_no'])
            ->make(true);
    }
    
    public function createCustomers(Request $request)
    {
        try {
            $rules = [
                'company_name' => ['required', 'string'],
                'client_name' => ['required', 'string'],
                // 'client_email' => ['email'],
                // 'client_email' => ['required', 'email'],
                // 'client_mobile' => ['numeric'],
                // 'client_mobile' => ['required', 'numeric'],
                'client_address' => ['required', 'string'],
                'gst_no' => ['required', 'string']
            ];
            
            $messages = [
                'company_name.required' => 'The company name is required.',
                'company_name.string' => 'The company name must be a string.',
                'client_name.required' => 'The client name is required.',
                'client_name.string' => 'The client name must be a string.',
                // 'client_email.required' => 'The client email is required.',
                // 'client_email.email' => 'The client email must be a valid email address.',
                // 'client_mobile.required' => 'The client mobile number is required.',
                // 'client_mobile.numeric' => 'The client mobile number must be a valid 10-digit number.',
                'client_address.required' => 'The client address is required.',
                'client_address.string' => 'The client address must be a string.',
                'gst_no.required' => 'The GST number is required.',
                'gst_no.string' => 'The GST number must be a string.'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $company_slug = Str::slug($request->company_name) . '-' . Str::random(4);
            $arr = [
                'company_name' => $request->company_name,
                'page_slug' => $company_slug,
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_mobile' => $request->client_mobile,
                'client_address' => $request->client_address,
                'gst_no' => $request->gst_no
                ];
            // dd($arr);
            $insert = Customer::create($arr);
            if($insert){
                return response()->json(['status' => 200, 'message' => 'Client created successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to create client']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateCustomers(Request $request)
    {
        try {
            $rules = [
                'company_name' => ['required', 'string'],
                'client_name' => ['required', 'string'],
                // 'client_email' => ['email'],
                // 'client_email' => ['required', 'email'],
                // 'client_mobile' => ['numeric'],
                // 'client_mobile' => ['required', 'numeric'],
                'client_address' => ['required', 'string'],
                'gst_no' => ['required', 'string']
            ];
            
            $messages = [
                'company_name.required' => 'The company name is required.',
                'company_name.string' => 'The company name must be a string.',
                'client_name.required' => 'The client name is required.',
                'client_name.string' => 'The client name must be a string.',
                // 'client_email.required' => 'The client email is required.',
                // 'client_email.email' => 'The client email must be a valid email address.',
                // 'client_mobile.required' => 'The client mobile number is required.',
                // 'client_mobile.numeric' => 'The client mobile number must be a valid 10-digit number.',
                'client_address.required' => 'The client address is required.',
                'client_address.string' => 'The client address must be a string.',
                'gst_no.required' => 'The GST number is required.',
                'gst_no.string' => 'The GST number must be a string.'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $company_slug = Str::slug($request->company_name) . '-' . Str::random(4);
            $arr = [
                'company_name' => $request->company_name,
                'page_slug' => $company_slug,
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_mobile' => $request->client_mobile,
                'client_address' => $request->client_address,
                'gst_no' => $request->gst_no
                ];
            // dd($arr);
            $update = Customer::where('id', $request->id)->update($arr);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Client update successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update client']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteCustomers(Request $request){
        try {
            $rules = [
                    'id' => ['required']
                ];
            $messages = [
                    'id.required' => 'The product id is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $delete = Customer::where('id', $request->id)->update(['deleted_at' => now()]);
            if($delete){
                return response()->json(['status' => 200, 'message' => 'Client has been deleted successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete client']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
}
