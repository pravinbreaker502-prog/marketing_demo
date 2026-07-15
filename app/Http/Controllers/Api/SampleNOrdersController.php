<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Customer,
    Employee,
    Product,
    SampleOrders,
    Orders
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SampleNOrdersController extends Controller
{
    public function ProductsList(Request $request){
        try{
            $user = Session::get('user_details');
            $std_wise = Product::where('standard', '!=', NULL)->orderBy('standard', 'DESC')->groupBy('standard')->pluck('standard');
            $arr = ['status' => 'true'];
            $arr1 = $std_wise->map(function($value) use ($request, $user) {
                $get = Product::where('standard', $value)->orderBy('id', 'DESC')->get();
               return [
                   'std' => $value,
                   'books' => $this->ListOfProducts($get, $request)
                   ];
            });
            $arr['productslist'] = $arr1;
            return response()->json($arr);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function ListOfProducts($get, $request){
        try{
            $arr = $get->map(function($value) use ($request) {
               return [
                   'std' => $value->standard,
                   'bname' => $value->product,
                   'quantity' => 0,
                //   'quantity' => $value->quantity,
                   'productid' => $value->id,
                //   'orderid' => 0,
                   ];
            });
            return $arr;
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function SaveSampleNOrders(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->type === 'sample'){
                return $this->SaveSample($request->products, $user, $request);
            }else{
                return $this->SaveOrders($request->products, $user, $request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
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
                return [
                    'employee_id' => $user->id,
                    'customer_id' => $request->clientid,
                    'product_id' => $value['productid'],
                    'product_name' => $value['bname'],
                    'quantity' => $value['quantity']
                ];
            })->toArray();
            $insert = SampleOrders::insert($arr);
            return response()->json($insert ? ['status' => 'true', 'message' => 'Saved Successfully..!'] : ['status' => 'false', 'message' => 'Failed to save sample order']);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function SaveOrders($products, $user, $request){
        try{
            // dd($request->all());
            $arr = collect($request->products)->map(function ($product, $index) use ($user, $request) {
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
        
                return [
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
                ];
            })->toArray();
            $insert = Orders::insert($arr);
            return response()->json($insert ? ['status' => 'true', 'message' => 'Saved Successfully..!'] : ['status' => 'false', 'message' => 'Failed to save order']);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function SaveSingleSampleNOrder(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->type === 'sample'){
                return $this->SaveSingleSample($user, $request);
            }else{
                return $this->SaveSingleOrder($user, $request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function SaveSingleSample($user, $request){
        try{
            $orderData = SampleOrders::where('id', $request->sourceid)->first();
            if(!$orderData){
                return response()->json(['status' => 'false', 'message' => 'Sample order not found']);
            }
            $productData = Product::find($request->productid);
            $categories = json_decode($productData->category_id, true);
            $previous_pro = Product::find($orderData->product_id);
            $previous_cat = json_decode($previous_pro->category_id, true);
            if ($categories && !in_array('1', $categories)) {
                $update1 = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$previous_cat[0].'"]\')')->increment('quantity', $orderData->quantity);
                $qty_check = $productData->quantity - (int)$request->quantity;
                
                if ((int)$qty_check < 0) {
                    throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is insufficient.');
                }
                
                if($update1){
                    $update = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$categories[0].'"]\')')->decrement('quantity', $request->quantity);
                }else{
                    return response()->json(['status' => 'false', 'message' => 'Quantity increment Failed.']);
                }
            } else {
                $update1 = Product::whereNull('deleted_at')->where('id', $orderData->product_id)->update(['quantity' => (int)$previous_pro->quantity+(int)$orderData->quantity, 'updated_at' => now()]);
                $proq_up = Product::find($request->productid);
                $qty_check = $proq_up->quantity - (int)$request->quantity;
                if((int)$qty_check < 0){
                     return response()->json(['status' => 'false', 'message' => 'Quantity of product is minimum.']);
                }
                if($update1){
                    $update = Product::where('id', $request->productid)->update(['quantity' => (int)$proq_up->quantity-(int)$request->quantity, 'updated_at' => now()]);
                }else{
                    return response()->json(['status' => 'false', 'message' => 'Quantity increment Failed.']);
                }
            }

            if (!$update) {
                throw new \Exception('Quantity reduction failed: product no. ' . ($index + 1));
            }
            $arr = [
                'employee_id' => $user->id,
                'product_id' => $productData->id,
                'product_name' => $productData->product,
                'quantity' => (int)$request->quantity
            ];
            $update = SampleOrders::where('id', $request->sourceid)->update($arr);
            return response()->json($update ? ['status' => 'true', 'message' => 'Updated Successfully..!'] : ['status' => 'false', 'message' => 'Failed to update sample order']);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function SaveSingleOrder($user, $request){
        try{
            $orderData = Orders::where('id', $request->sourceid)->first();
            if(!$orderData){
                return response()->json(['status' => 'false', 'message' => 'Order not found']);
            }
            $productData = Product::find($request->productid);
            $categories = json_decode($productData->category_id, true);
            $previous_pro = Product::find($orderData->product_id);
            $previous_cat = json_decode($previous_pro->category_id, true);
            if ($categories && !in_array('1', $categories)) {
                $update1 = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$previous_cat[0].'"]\')')->increment('quantity', $orderData->quantity);
                $qty_check = $productData->quantity - (int)$request->quantity;
                
                if ((int)$qty_check < 0) {
                    throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is insufficient.');
                }
                
                if($update1){
                    $update = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$categories[0].'"]\')')->decrement('quantity', $request->quantity);
                }else{
                    return response()->json(['status' => 'false', 'message' => 'Quantity increment Failed.']);
                }
            } else {
                $update1 = Product::whereNull('deleted_at')->where('id', $orderData->product_id)->update(['quantity' => (int)$previous_pro->quantity+(int)$orderData->quantity, 'updated_at' => now()]);
                $proq_up = Product::find($request->productid);
                $qty_check = $proq_up->quantity - (int)$request->quantity;
                if((int)$qty_check < 0){
                     return response()->json(['status' => 'false', 'message' => 'Quantity of product is minimum.']);
                }
                if($update1){
                    $update = Product::where('id', $request->productid)->update(['quantity' => (int)$proq_up->quantity-(int)$request->quantity, 'updated_at' => now()]);
                }else{
                    return response()->json(['status' => 'false', 'message' => 'Quantity increment Failed.']);
                }
            }

            if (!$update) {
                throw new \Exception('Quantity reduction failed: product no. ' . ($index + 1));
            }
            $arr = [
                'employee_id' => $user->id,
                'product_id' => $productData->id,
                'product_name' => $productData->product,
                'quantity' => (int)$request->quantity
            ];
            $update = Orders::where('id', $request->sourceid)->update($arr);
            return response()->json($update ? ['status' => 'true', 'message' => 'Order Updated Successfully..!'] : ['status' => 'false', 'message' => 'Failed to update order']);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function GetSavedSampleNOrders(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->type === 'sample'){
                $get = SampleOrders::where('customer_id', $request->clientid)->where('employee_id', $user->id)->get();
            }else{
                $get = Orders::where('customer_id', $request->clientid)->where('employee_id', $user->id)->where('status', 'pending')->get();
            }
            $arr = ['status' => 'true'];
            $arr1 = $get->map(function($value) use ($get, $request) {
                $pro = Product::where('id', $value->product_id)->first();
               return [
                   'bname' => $pro->product,
                   'quantity' => $value->quantity,
                   'productid' => $pro->id,
                   'orderid' => $value->id,
                   'std' => $pro->standard
                   ]; 
            });
            $arr['editedproducts'] = $arr1;
            return response()->json($arr);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function RemoveSavedSampleNOrders(Request $request){
        try{
            $user = Session::get('user_details');
            if($request->type === 'sample'){
                $get = SampleOrders::where('id', $request->orderid)->first();
                $delete = SampleOrders::where('id', $request->orderid)->delete();
            }else{
                $get = Orders::where('id', $request->orderid)->first();
                $delete = Orders::where('id', $request->orderid)->delete();
            }
            if(!$get){
                return response()->json(['status' => 'false', 'message' => 'Order not found']);
            }
            if($delete){
                $get_product = Product::where('id', $get->product_id)->first();
                $categories = json_decode($get_product->category_id, true);
                // dd($categories);
                if($categories && !in_array('1', $categories)){
                    $update = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$categories[0].'"]\')')->increment('quantity', $get->quantity);
                }else{
                    $update = Product::where('id', $get->product_id)->increment('quantity', $get->quantity);
                }
                if(!$update){
                    return response()->json(['status' => 'false', 'message' => 'Quantity increment Failed.']);
                }
                return response()->json(['status' => 'true', 'message' => 'Order has been deleted successfully']);
            }else{
                return response()->json(['status' => 'false', 'message' => 'Failed to delete order']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    public function GetOrdersHistory(Request $request){
        try{
            $user = Session::get('user_details');
            if(!$request->period){
                return response()->json(['status' => "false", "message" => 'Dates are required.']);
            }
            $get = Orders::when($request->period, function ($query) use ($request, $user) {
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
                    ->where('employee_id', $user->id)->pluck('customer_id');
                // dd($get->count());
            $customer = Customer::whereIn('id', $get)->get();
            $arr = ['status' => 'true'];
            $arr1 = $customer->map(function($value) use ($get, $request, $user) {
               return [
                   'schoolname' => $value->company_name,
                   'orderhistory' => $this->OrdersHistory($request, $user, $value)
                   ]; 
            });
            $arr['history'] = $arr1;
            return response()->json($arr);
        } catch (\Exception $e) {
            return response()->json(['status' => "false", "message" => $e->getMessage()]);
        }
    }
    
    function OrdersHistory($request, $user, $value){
        try {
            $user = Session::get('user_details');
            $orders = Orders::when($request->period, function ($query) use ($request, $user) {
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
                    ->where('customer_id', $value->id)->where('employee_id', $user->id)->get();
            if ($orders->isEmpty()) {
                return [];
            }
            $arr = $orders->map(function($order) use ($request, $user) {
                $product = Product::where('id', $order->product_id)->first();
                if (!$product) {
                    throw new \Exception("Product not found for product_id: {$order->product_id}");
                }
                return [
                    'orderid' => (string)$order->id,
                    'bname' => $product->product,
                    'quantity' => $order->quantity,
                    'status' => $order->status,
                    'date' => Carbon::parse($order->created_at)->format('d-m-Y'),
                ]; 
            })->toArray();
            return $arr;
        } catch (\Exception $e) {
            return response()->json(['status' => "false", 'message' => $e->getMessage()]);
        }
    }
}
