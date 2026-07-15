<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer,
    Orders,
    Invoice,
    Employee,
    OrderReturnProducts
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

class OrderReturnProductController extends Controller
{
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $employees = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'SalesExecutive')->orderBy('id', 'DESC')->get();
        $products = Product::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        return view('marketing.returnproduct.return-product-list', compact('datas', 'customers', 'employees', 'products'));
    }
    
    public function CreatePage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $employees = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'SalesExecutive')->orderBy('id', 'DESC')->get();
        return view('marketing.returnproduct.add-return-product', compact('datas', 'customers', 'employees'));
    }
    
    public function EditPage(Request $request)
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $return_products = OrderReturnProducts::whereNull('deleted_at')->where('id', $request->id)->first();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $employees = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'SalesExecutive')->orderBy('id', 'DESC')->get();
        $orders = Orders::select('orders.*', 'customers.company_name', 'products.product', 'employees.employee_name')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->leftJoin('employees', 'employees.id', '=', 'orders.employee_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.customer_id', $return_products->customer_id)
            ->where('orders.employee_id', $return_products->employee_id)
            ->where('status', 'LIKE', 'delivered')
            ->orderBy('orders.id', 'DESC')
            ->get();
        $products = Product::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        return view('marketing.returnproduct.return-product-list-edit', compact('datas', 'customers', 'orders', 'employees', 'return_products', 'products'));
    }
    
    public function GetOrdersForReturnProduct(Request $request){
        $orders = Orders::select('orders.*', 'customers.company_name', 'products.product', 'employees.employee_name')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->leftJoin('employees', 'employees.id', '=', 'orders.employee_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.customer_id', $request->customer_id)
            ->where('orders.employee_id', $request->employee_id)
            ->where('status', 'LIKE', 'delivered')
            ->orderBy('orders.id', 'DESC')
            ->get();
        return response()->json($orders);
    }
    
    public function getOrdersForReturnProductList(Request $request)
    {
        $order_returns = OrderReturnProducts::select('order_return_products.*', 'customers.company_name', 'products.product', 'employees.employee_name')
            ->leftJoin('customers', 'customers.id', '=', 'order_return_products.customer_id')
            ->leftJoin('products', 'products.id', '=', 'order_return_products.product_id')
            ->leftJoin('employees', 'employees.id', '=', 'order_return_products.employee_id')
            ->leftJoin('invoice_history', 'invoice_history.id', '=', 'order_return_products.invoice_id')
            ->whereNull('order_return_products.deleted_at')
            ->when($request->customer_id, function($query) use ($request){
                $query->where('order_return_products.customer_id', $request->customer_id);
            })
            ->when($request->employee_filter, function($query) use ($request){
                $query->where('order_return_products.employee_id', $request->employee_filter);
            })
            ->when($request->product_filter, function($query) use ($request){
                $query->where('order_return_products.product_id', $request->product_filter);
            })
            ->when($request->from_date && $request->to_date, function($query) use ($request){
                $from_date = Carbon::parse($request->from_date)->startOfDay()->format('Y-m-d H:i:s');
                $to_date = Carbon::parse($request->to_date)->endOfDay()->format('Y-m-d H:i:s');
                $query->whereBetween('order_return_products.created_at', [$from_date, $to_date]);
            })
            ->orderBy('order_return_products.id', 'DESC')
            ->get();

        return DataTables::of($order_returns)
            ->addColumn('DT_RowIndex', function ($order_returns) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('cmpny_name', function ($order_returns) {
                return '<p class="f-light">'.$order_returns->company_name.'</p>';
            })
            ->addColumn('product', function ($order_returns) {
                return '<p class="f-light">'.$order_returns->product.'</p>';
            })
            ->addColumn('emply_name', function ($order_returns) {
                return '<p class="f-light">'.$order_returns->employee_name.'</p>';
            })
            ->addColumn('qty', function ($order_returns) {
                return '<span class="badge badge-light-primary">'.$order_returns->quantity.'</span>';
            })
            ->addColumn('ttl_amt', function ($order_returns) {
                return '<span class="badge badge-light-success">'.$order_returns->total_amount.'</span>';
            })
            ->addColumn('compensated_amt', function ($order_returns) {
                return '<span class="badge badge-light-success">'.($order_returns->compensated_amount != NULL ? $order_returns->compensated_amount : '-').'</span>';
            })
            ->addColumn('returned_amt', function ($order_returns) {
                return '<span class="badge badge-light-success">'.($order_returns->returned_amount != NULL ? $order_returns->returned_amount : '-').'</span>';
            })
            ->addColumn('actions', function ($order_returns) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="'.route('order-return-products.edit', $order_returns->id).'"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-order_return_products" data-id="' . $order_returns->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'cmpny_name', 'product', 'qty', 'emply_name', 'ttl_amt', 'compensated_amt', 'returned_amt'])
            ->make(true);
    }
    
    public function createOrdersForReturnProduct(Request $request){
        try {
            $rules = [
                'customer' => ['required'],
                'employee' => ['required'],
                'order_products' => ['required'],
                'quantity' => ['required', 'integer']
            ];
            
            $messages = [
                'customer.required' => 'The company is required.',
                'employee.required' => 'The employee is required.',
                'order_products.required' => 'The product is required.',
                'quantity.required' => 'The quantity is required.',
                'quantity.integer' => 'The quantitt must be integers.'
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $order = Orders::whereNull('deleted_at')->where('id', $request->order_products)->first();
            if($request->quantity > $order->quantity){
                return response()->json(['status' => 400, 'message' => 'Quatity is greater than the ordered product quantity.']);
            }
            
            $product = Product::whereNull('deleted_at')->where('id', $order->product_id)->first();
            if ($order->discount == '0' || $order->discount == null) {
                $discount_per = $product->discount;
                $ttl_sellpri = $product->actual_price*$request->quantity;
                $dis_amount = ($product->discount / 100) * $ttl_sellpri;
            } else {
                $discount_per = $order->discount;
                $ttl_sellpri = $product->actual_price*$request->quantity;
                $dis_amount = ($order->discount / 100) * $ttl_sellpri;
            }
            $acc_amount = $ttl_sellpri-$dis_amount;
            $gst_amount = ($product->gst / 100) * $acc_amount;
            $ttl_amount = $acc_amount+$gst_amount;
                
            $arr = [
                'customer_id' => $request->customer,
                'employee_id' => $request->employee,
                'product_id' => $order->product_id,
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'quantity' => $request->quantity,
                'discount' => $order->discount,
                'discount_amount' => round((int)$dis_amount, 2),
                'gst_per' => $product->gst,
                'gst_amount' => round((int)$gst_amount, 2),
                'actual_amount' => round((int)$acc_amount, 2),
                'total_amount' => round((int)$ttl_amount, 2),
                ];
            // dd($arr);
            $insert = OrderReturnProducts::create($arr);
            if($insert){
                $arr1 = [
                    'quantity' => $product->quantity + $request->quantity,
                    'updated_at' => now()
                    ];
                $qty_update = Product::whereNull('deleted_at')->where('id', $order->product_id)->update($arr1);
                if(!$qty_update){
                    return response()->json(['status' => 400, 'message' => 'Quantity update failed. Kindly contact the admin.']);
                }
                return response()->json(['status' => 200, 'message' => 'Created successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to create']);
            }

        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateOrdersForReturnProduct(Request $request){
        try {
            $rules = [
                'customer_edit' => ['required'],
                'employee_edit' => ['required'],
                'order_products_edit' => ['required'],
                'quantity_edit' => ['required', 'integer']
            ];
            
            $messages = [
                'customer_edit.required' => 'The company is required.',
                'employee_edit.required' => 'The employee is required.',
                'order_products_edit.required' => 'The product is required.',
                'quantity_edit.required' => 'The quantity is required.',
                'quantity_edit.integer' => 'The quantitt must be integers.'
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $order_return = OrderReturnProducts::where('id', $request->id)->first();
            $order = Orders::whereNull('deleted_at')->where('id', $request->order_products_edit)->first();
            if($request->quantity_edit > $order->quantity){
                return response()->json(['status' => 400, 'message' => 'Quatity is greater than the ordered product quantity.']);
            }
            
            $add = $request->compensated_amount+$request->returned_amount;
            if($add > $order_return->total_amount){
                return response()->json(['status' => 400, 'message' => 'The total amount of the given compensation & returned amount is greater than the credit amount. Kindly the check amount correctly']);
            }
            
            $product = Product::whereNull('deleted_at')->where('id', $order->product_id)->first();
            if ($order->discount == '0' || $order->discount == null) {
                $discount_per = $product->discount;
                $ttl_sellpri = $product->actual_price*$request->quantity_edit;
                $dis_amount = ($product->discount / 100) * $ttl_sellpri;
            } else {
                $discount_per = $order->discount;
                $ttl_sellpri = $product->actual_price*$request->quantity_edit;
                $dis_amount = ($order->discount / 100) * $ttl_sellpri;
            }
            $acc_amount = $ttl_sellpri-$dis_amount;
            $gst_amount = ($product->gst / 100) * $acc_amount;
            $ttl_amount = $acc_amount+$gst_amount;
                
            $arr = [
                'customer_id' => $request->customer_edit,
                'employee_id' => $request->employee_edit,
                'product_id' => $order->product_id,
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'quantity' => $request->quantity_edit,
                'discount' => $order->discount,
                'discount_amount' => round((int)$dis_amount, 2),
                'gst_per' => $product->gst,
                'gst_amount' => round((int)$gst_amount, 2),
                'actual_amount' => round((int)$acc_amount, 2),
                'total_amount' => round((int)$ttl_amount, 2),
                'compensated_amount' => round((int)$request->compensated_amount, 2),
                'returned_amount' => round((int)$request->returned_amount, 2),
                ];
            // dd($arr);
            $update = OrderReturnProducts::where('id', $request->id)->update($arr);
            if($update){
                $arr1 = [
                    'quantity' => $product->quantity + $request->quantity_edit,
                    'updated_at' => now()
                    ];
                $qty_update = Product::whereNull('deleted_at')->where('id', $order->product_id)->update($arr1);
                if(!$qty_update){
                    return response()->json(['status' => 400, 'message' => 'Quantity update failed. Kindly contact the admin.']);
                }
                return response()->json(['status' => 200, 'message' => 'Updated successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update']);
            }

        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteOrdersForReturnProduct(Request $request){
        try {
            $rules = [
                    'id' => ['required']
                ];
            $messages = [
                    'id.required' => 'The order id is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $delete = OrderReturnProducts::where('id', $request->id)->delete();
            if($delete){
                return response()->json(['status' => 200, 'message' => 'successfully deleted']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
}
