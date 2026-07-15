<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Employee;
use Illuminate\Support\Facades\Session;

class ValidateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->token || $request->token == null){
            return response()->json(["status" => "error", "message" => "Unauthorized Account"]);
        }
        $user = Employee::where('app_token', $request->token)->whereNull('deleted_at')->first();
        if($user){
            Session::put('user_details', $user);
            return $next($request);
        }else{
            return response()->json(["status" => "error", "message" => "Unauthorized Account"]);
        }
    }
}
