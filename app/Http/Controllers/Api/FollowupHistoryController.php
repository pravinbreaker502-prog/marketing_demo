<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Customer,
    Employee,
    FollowupHistory,
    Product,
    SampleOrders,
    Orders
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class FollowupHistoryController extends Controller
{
    public function SaveFollowupHistory(Request $request){
        try{
            $user = Session::get('user_details');
            $order_ids = $this->SaveSampleNOrders($request, $request->type);
    
            $arr = [
                'employee_id' => $user->id,
                'customer_id' => $request->clientid,
                'sample_ids' => (!empty($order_ids->sampleids) ? json_encode($order_ids->sampleids) : NULL),
                'order_ids' => (!empty($order_ids->orderids) && $request->interestlay == 'interested' ? json_encode($order_ids->orderids) : NULL),
                'in_time' => $request->intime,
                'out_time' => $request->outtime,
                'accept_status' => ($request->interestlay == 'interested' ? '1' : ($request->interestlay == 'notinterested' ? '2' : '0')),
                'followup_date' => (!empty($request->followupdate) && $request->interestlay == 'interested' ? Carbon::parse($request->followupdate)->format('Y-m-d') : NULL),
                'training' => ($request->training == 'yes' ? '1' : ($request->training == 'no' ? '2' : '0')),
            ];
    // dd($arr);
            $insert = FollowupHistory::create($arr);
            
            if ($insert) {
                if ($request->type === 'sample' || $request->type === 'both') {
                    $updateSample = SampleOrders::whereIn('id', $order_ids->sampleids)->update(['followup_id' => $insert->id]);
                    if (!$updateSample) {
                        return response()->json(['status' => "false", "message" => "Sample follow-up ID update failed"]);
                    }
                }
    
                if ($request->type === 'order' || $request->type === 'both') {
                    $updateOrder = Orders::whereIn('id', $order_ids->orderids)->update(['followup_id' => $insert->id]);
                    if (!$updateOrder) {
                        return response()->json(['status' => "false", "message" => "Order follow-up ID update failed"]);
                    }
                }
    
                return response()->json(['status' => "true", "message" => "Saved successfully"]);
            }else{
                return response()->json(['status' => "false", "message" => "Failed to add followup..!"]);
            }
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function SaveSampleNOrders($request, $type){
        try{
            $user = Session::get('user_details');
            $sample_ids = null;
            $order_ids = null;
    
            if($type === 'sample'){
                $sample_ids = $this->SaveSample($request->sampleids, $user, $request);
            } elseif($type === 'order'){
                $order_ids = $this->SaveOrders($request->orderids, $user, $request);
            } else {
                $sample_ids = $this->SaveSample($request->sampleids, $user, $request);
                $order_ids = $this->SaveOrders($request->orderids, $user, $request);
            }
    
            return (object)[
                'sampleids' => $sample_ids,
                'orderids' => $order_ids
            ];
        } catch (\Exception $e) {
            throw $e; // Let the calling function handle the exception
        }
    }
    
    function SaveSample($products, $user, $request){
        try{
            $arr = collect($products)->map(function($value) use ($user, $request) {
                $productData = Product::find($value['productid']);
                $categories = json_decode($productData->category_id, true);
                if ($categories && !in_array('1', $categories)) {
                    $qty_check = $productData->quantity - (int)$value['quantity'];
                    
                    if ((int)$qty_check < 0) {
                        throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is insufficient.');
                    }
                    
                    $update = Product::whereNull('deleted_at')
                        ->whereRaw('JSON_CONTAINS(category_id, \'["' . $categories[0] . '"]\')')
                        ->decrement('quantity', $value['quantity']);
                } else {
                    $qty_check = $productData->quantity - (int)$value['quantity'];
                    
                    if ((int)$qty_check < 0) {
                        throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is insufficient.');
                    }
                    
                    $update = Product::where('id', $value['productid'])
                        ->update(['quantity' => $qty_check]);
                }
    
                if (!$update) {
                    throw new \Exception('Quantity reduction failed: product no. ' . ($index + 1));
                }
                return SampleOrders::create([
                    'employee_id' => $user->id,
                    'customer_id' => $request->clientid,
                    'product_id' => $value['productid'],
                    'product_name' => $value['bname'],
                    'quantity' => $value['quantity']
                ]);
            })->map(function($order) {
                return (string)$order->id;
            })->toArray();
            return $arr;
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function SaveOrders($products, $user, $request){
        try{
            // dd($request->all());
            $arr = collect($products)->map(function ($product, $index) use ($user, $request) {
                $productData = Product::find($product['productid']);
                
                if (!$productData) {
                    throw new \Exception('Product not found: product no. ' . ($index + 1));
                }
                
                $categories = json_decode($productData->category_id, true);
                if ($categories && !in_array('1', $categories)) {
                    $qty_check = $productData->quantity - (int)$product['quantity'];
                    
                    if ((int)$qty_check < 0) {
                        throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is insufficient.');
                    }
                    
                    $update = Product::whereNull('deleted_at')
                        ->whereRaw('JSON_CONTAINS(category_id, \'["' . $categories[0] . '"]\')')
                        ->decrement('quantity', $product['quantity']);
                } else {
                    $qty_check = $productData->quantity - (int)$product['quantity'];
                    
                    if ((int)$qty_check < 0) {
                        throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is insufficient.');
                    }
                    
                    $update = Product::where('id', $product['productid'])
                        ->update(['quantity' => $qty_check]);
                }
    
                if (!$update) {
                    throw new \Exception('Quantity reduction failed: product no. ' . ($index + 1));
                }
                
                // Calculate pricing, discounts, and tax
                $product_price = $productData->actual_price;
                $product_sellprice = $productData->sell_price;
                $product_gst = $productData->gst;
                $product_dis = $productData->discount;
                
                $ttl_sellpri = $product_sellprice * (int)$product['quantity'];
                $dis_amount = ($product_dis / 100) * $ttl_sellpri;
                $acc_amount = $ttl_sellpri - $dis_amount;
                $gst_amount = ($product_gst / 100) * $acc_amount;
                $ttl_amount = $acc_amount + $gst_amount;
        
                return Orders::create([
                    'customer_id' => $request->clientid,
                    'product_id' => $product['productid'],
                    'employee_id' => $user->id,
                    'product_name' => $productData->product,
                    'quantity' => (int)$product['quantity'],
                    'discount' => $product_dis,
                    'discount_amt' => $dis_amount,
                    'gst_per' => $product_gst,
                    'gst_amt' => $gst_amount,
                    'actual_amt' => $acc_amount,
                    'total_amt' => $ttl_amount,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })->map(function($order) {
                return (string)$order->id;
            })
            ->toArray();
            return $arr;
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function GetFollowupHistory(Request $request){
        try{
            $user = Session::get('user_details');
            $get = FollowupHistory::when($request->period, function ($query) use ($request) {
                try {
                    $period = explode('-', $request->period);
                    if (count($period) === 2) {
                        $month = $period[0];
                        $year = $period[1];
                        $from = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
                        $to = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
                        $query->whereBetween('followup_date', [$from, $to]);
                    } else {
                        return response()->json(['status' => 'false', 'message' => "Invalid period format"]);
                    }
                } catch (\Exception $e) {
                    return response()->json(['status' => 'false', 'message' => "Invalid period format: " . $e->getMessage()]);
                }
            })
            ->where('employee_id', $user->id)
            ->where('accept_status', '1')
            ->where('followup_date', '!=', NULL)
            ->where('order_ids', '=', NULL)
            ->orderBy('id', 'DESC')->get();
            $arr = ['status' => 'true'];
            $arr1 = $get->map(function($value) use ($request, $user) {
                $cus = Customer::where('id', $value->customer_id)->first();
               return [
                   'schoolid' => $cus ? (string)$cus->id : '',
                   'schoolname' => $cus ? $cus->company_name : '',
                   'date' => $value->followup_date != null ? Carbon::parse($value->followup_date)->format('d-m-Y') : "",
                   'followupid' => (string)$value->id
                   ];
            });
            $arr['followuplist'] = $arr1;
            return response()->json($arr);
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function EditnUpdateFollowupHistory(Request $request) {
        try {
            $user = Session::get('user_details');
            $get = FollowupHistory::where('id', $request->followupid)->first();
            
            if ($get) {
                if($request->interestlay == 'interested'){
                    $order_ids = $this->SaveOrders($request->orderids, $user, $request);
                }else{
                    $order_ids = [];
                }
                $arr = [
                    'order_ids' => (!empty($order_ids) && $request->interestlay == 'interested' ? json_encode($order_ids) : NULL),
                    'accept_status' => ($request->interestlay == 'interested' ? '1' : ($request->interestlay == 'notinterested' ? '2' : '0')),
                    'followup_date' => (!empty($request->followupdate) && $request->interestlay == 'interested' ? Carbon::parse($request->followupdate)->format('Y-m-d') : NULL),
                ];
    
                $update = FollowupHistory::where('id', $request->followupid)->update($arr);
    
                if ($update) {
                    if($request->interestlay == 'interested'){
                        if(!empty($order_ids)){
                            $updateOrder = Orders::whereIn('id', $order_ids)->update(['followup_id' => $request->followupid]);
                            if (!$updateOrder) {
                                return response()->json(['status' => "false", "message" => "Order follow-up ID update failed"]);
                            }
                        }
                    }
                    return response()->json(['status' => "true", "message" => "Updated successfully"]);
                } else {
                    return response()->json(['status' => "false", "message" => "Failed to update follow-up history"]);
                }
            } else {
                return response()->json(['status' => "false", "message" => "Follow-up history not found"]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function GetSchoolVisits(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->type == 'followup'){
                $get = FollowupHistory::when($request->period, function ($query) use ($request) {
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
                ->where('employee_id', $user->id)
                ->where('accept_status', '1')
                ->where('followup_date', '!=', NULL)
                ->orderBy('id', 'DESC')->get();
            }else{
                $get = FollowupHistory::when($request->period, function ($query) use ($request) {
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
                ->where('employee_id', $user->id)
                ->where('accept_status', '2')
                ->where('followup_date', '=', NULL)
                ->orderBy('id', 'DESC')->get();
            }
            $arr = ['status' => 'true'];
            $arr1 = $get->map(function($value) use ($request, $user) {
                $cus = Customer::where('id', $value->customer_id)->first();
               return [
                   'schoolname' => $cus ? $cus->company_name : '',
                   'schoolmailid' => $cus ? $cus->client_email : '',
                   'phonenumber' => $cus ? (string)$cus->client_mobile : '',
                   'address' => $cus ? $cus->client_address : '',
                   'date' => $value->followup_date != null ? Carbon::parse($value->followup_date)->format('d-m-Y') : Carbon::parse($value->updated_at)->format('d-m-Y'),
                   ];
            });
            $arr['schoollist'] = $arr1;
            return response()->json($arr);
        } catch(\Exception $e){
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
}
