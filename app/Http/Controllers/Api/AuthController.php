<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Employee,
    EmployeeDeviceIds
};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function Userlogin(Request $request)
    {
        try{
            if($request->logfornext == 0 && $request->logfornext != null && $request->logfornext != ""){
                $user = Employee::where('username', $request->username)->where('employee_type', 'SalesExecutive')->whereNull('deleted_at')->first();
                if (!$user) {
                    return response()->json(['status' => 'false', 'message' => 'Invalid Username']);
                }
                if ($user->CheckPassword($request->password)) {
                    $update = Employee::where('id', $user->id)->update(['fcm_token' => $request->devicetoken, 'app_token' => Str::random(50)]);
                    $data = Employee::where('id', $user->id)->whereNull('deleted_at')->first();
                    
                    if($request->type == 'savecred'){
                        $insert = EmployeeDeviceIds::insert(['employee_id' => $user->id, 'device_id' => $request->deviceid]);
                        if(!$insert){
                            return response()->json(['status' => 'false', 'message' => 'Device is not stored.']);
                        }
                    }
                    
                    return response()->json($update ? ['status' => 'true' , 'message' => 'Successfully loggedin..!', 'userid' => $data->id, 'accesstoken' => $data->app_token] : ['status' => 'false', 'message' => 'Failed to login..!']);
                } else {
                    return response()->json(['status' => 'false', 'message' => 'Invalid Password']);
                }
            }else{
                $get123 = Employee::where('username', $request->username)->where('employee_type', 'SalesExecutive')->whereNull('deleted_at')->first();
                if($get123->app_token != null){
                    return response()->json(['status' => "error", "message" => "Already logged in another device."]);
                }
                $user = Employee::where('username', $request->username)->where('employee_type', 'SalesExecutive')->whereNull('deleted_at')->first();
                if (!$user) {
                    return response()->json(['status' => 'false', 'message' => 'Invalid Username']);
                }
                if ($user->CheckPassword($request->password)) {
                    $update = Employee::where('id', $user->id)->update(['fcm_token' => $request->devicetoken, 'app_token' => Str::random(50)]);
                    $data = Employee::where('id', $user->id)->whereNull('deleted_at')->first();
                    if($request->type == 'savecred'){
                        $insert = EmployeeDeviceIds::insert(['employee_id' => $user->id, 'device_id' => $request->deviceid]);
                        if(!$insert){
                            return response()->json(['status' => 'false', 'message' => 'Device is not stored.']);
                        }
                    }
                    return response()->json($update ? ['status' => 'true' , 'message' => 'Successfully loggedin..!', 'userid' => $data->id, 'accesstoken' => $data->app_token] : ['status' => 'false', 'message' => 'Failed to login..!']);
                } else {
                    return response()->json(['status' => 'false', 'message' => 'Invalid Password']);
                }
            }
            
        } catch (\Exception $e){
            return response()->json(['status' => 'false', 'message' => $e->getMessage()]);
        }
    }
    
    public function UserProfileData(Request $request)
    {
        try{
            $user = Session::get('user_details');
            $get = Employee::where('id', $user->id)->whereNull('deleted_at')->first();
            if ($get) {
                $arr = [
                    'status' => "true",
                    'name' => $get->employee_name ? $get->employee_name : '',
                    'profileimage' => $get->employee_profile ? $get->employee_profile : '',
                    'username' => $get->username,
                    'phonenumber' => $get->employee_mobile ? (string)$get->employee_mobile : '',
                    'email' => $get->employee_email ? $get->employee_email : '',
                    'address' => $get->employee_address ? $get->employee_address : '',
                    'bloodgroup' => $get->employee_blood ? $get->employee_blood : ''
                ];
                return response()->json($arr);
            }else{
                return response()->json(['status' => "false", "message" => "User Not Found..!"]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function UpdateProfileData(Request $request)
    {
        try{
            $user = Session::get('user_details');
            if ($request->hasFile('userimage')) {
                $imageFile = $request->file('userimage');
                $imagePath = 'uploads/' . $user->employee_name .'/' . $user->employee_name . uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('uploads/' . $user->employee_name), $imagePath);
                if ($user->employee_profile && file_exists(public_path($user->employee_profile))) {
                    unlink(public_path($user->employee_profile));
                }
            } else {
                $imagePath = $user->employee_profile;
            }
            $arr = [
                'employee_profile' => $imagePath,
                'employee_mobile' => $request->input('phnumber'),
                'employee_email' => $request->input('mailid'),
                'employee_address' => $request->input('address'),
                'employee_blood' => $request->input('bloodgroup')
            ];
            $update = Employee::where('id', $user->id)->update($arr);
            return response()->json($update ? ['status' => "true", "message" => "Succesfully profile updated"] : ['status' => "false", "message" => "Failed to update user profile"]);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function UserLogout(Request $request)
    {
        try{
            $user = Session::get('user_details');
            $token_del = Employee::where('id', $user->id)->whereNull('deleted_at')->update(['app_token' => NULL, 'fcm_token' => NULL]);
            return response()->json($token_del ? ['status' => 'true', 'message' => 'Successfully logged out..!'] : ['status' => 'false', 'message' => 'Failed to logout..!']);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
}
