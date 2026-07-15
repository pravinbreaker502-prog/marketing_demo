<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Customer,
    FollowupHistory
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function AddClient(Request $request){
        try{
            $rules = [
                'schoolmailid' => 'unique:customers,client_email',
                'phonenumber' => 'unique:customers,client_mobile',
            ];
            
            $messages = [
                'company_name.required' => 'The company name is required.',
                'company_name.string' => 'The company name must be a string.',
                'client_name.required' => 'The client name is required.',
                'client_name.string' => 'The client name must be a string.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Phone number or email has already taken..!']);
            }
            $user = Session::get('user_details');
            $company_slug = Str::slug($request->schoolname) . '-' . Str::random(4);
            $arr = [
                'company_name' => $request->schoolname,
                'page_slug' => $company_slug,
                'client_email' => $request->schoolmailid,
                'client_mobile' => $request->phonenumber,
                'client_address' => $request->address
                ];
            $insert = Customer::create($arr);
            $data = Customer::find($insert->id);
            return response()->json($insert ? ['status' => "true", "message" => "Successfully client added..!", 'schoolid' => (string)$data->id, 'schoolname' => (string)$data->company_name, 'schoolmailid' => (string)$data->client_email, 'phonenumber' => (string)$data->client_mobile, 'address' => (string)$data->client_address] : ['status' => "false", "message" => "Failed to add client..!"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function ClientsList(Request $request){
        try{
            $user = Session::get('user_details');
            $get = Customer::orderBy('id', 'DESC')->get();
            $arr = ['status' => 'true'];
            $arr1 = $get->map(function($value) use ($request, $user) {
                $flw_status = FollowupHistory::whereNull('order_ids')->where('accept_status', '1')->whereNotNull('followup_date')->where('customer_id', $value->id)->first();
                $access = FollowupHistory::whereNull('order_ids')->where('customer_id', $value->id)->where('employee_id', $user->id)->first();
               return [
                   'schoolid' => $value->id,
                   'schoolname' => $value->company_name,
                   'mailid' => $value->client_email,
                   'phonenumber' => (string)$value->client_mobile,
                   'address' => $value->client_address,
                   'followupid' => $flw_status ? (string)$flw_status->id : '0',
                   'followupdate' => $flw_status ? Carbon::parse($flw_status->followup_date)->format('d-m-Y') : '',
                   'editaccess' => $access ? 'granted' : 'no'
                   ];
            });
            $arr['schoollist'] = $arr1;
            return response()->json($arr);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function EditClient(Request $request){
        try{
            $user = Session::get('user_details');
            $get = Customer::find($request->schoolid);
            if(!$get){
                return response()->json(['status' => "false", "message" => 'Client not found']);
            }
            $company_slug = Str::slug($request->schoolname) . '-' . Str::random(4);
            $arr = [
                'company_name' => $request->schoolname ? $request->schoolname : $get->company_name,
                'page_slug' => $request->schoolname ? $company_slug : $get->page_slug,
                'client_email' => $request->mailid ? $request->mailid : $get->client_email,
                'client_mobile' => $request->phonenumber ? $request->phonenumber : $get->client_mobile,
                'client_address' => $request->address ? $request->address : $get->client_address
                ];
            $update = Customer::where('id', $get->id)->update($arr);
            return response()->json($update ? ['status' => "true", "message" => "Successfully updated..!"] : ['status' => "false", "message" => "Failed to update client..!"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
}
