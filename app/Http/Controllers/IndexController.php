<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function GteCountries(Request $request)
    {
        try {
            $countries = DB::table('countries')
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                })
                ->orderBy('id', 'ASC')
                ->get();
        
            return response($countries->count() > 0 ? ['status' => 200, 'data' => $countries] : ['status' => 200, 'data' => []]);
        } catch (Exception $e) {
            return response(['status' => 500, 'error' => $e->getMessage()]);
        }
    }

    public function GetStates(Request $request)
    {
        try {
            $states = DB::table('states')
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                })
                ->where('country_id', $request->country_id)
                ->orderBy('id', 'ASC')
                ->get();
        
            return response($states->count() > 0 ? ['status' => 200, 'data' => $states] : ['status' => 200, 'data' => []]);
        } catch (Exception $e) {
            return response(['status' => 500, 'error' => $e->getMessage()]);
        }
    }

    public function GetCities(Request $request)
    {
        try {
            $cities = DB::table('cities')
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                })
                ->where('state_id', $request->state_id)
                ->orderBy('id', 'ASC')
                ->get();
        
            return response($cities->count() > 0 ? ['status' => 200, 'data' => $cities] : ['status' => 200, 'data' => []]);
        } catch (Exception $e) {
            return response(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
}
