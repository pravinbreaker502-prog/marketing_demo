<?php

namespace App\Http\Controllers\Packing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer,
    Orders,
    Invoice
    };
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PackingOrdersController extends Controller
{
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('packing.pending-orders.pack-pending-orders', compact('datas'));
    }
    
    public function packPackedPage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        return view('packing.packed-orders.pack-packed-orders', compact('datas'));
    }
    
    public function getPackPendingOrders(Request $request)
    {
        $orders = Orders::select('orders.*', 'customers.company_name', 'products.product')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', 'LIKE', 'pending')
            ->orderBy('orders.id', 'DESC')
            ->get();

        return DataTables::of($orders)
            ->addColumn('order_checkbox', function ($orders) {
                return '<input type="checkbox" class="f-light" value="'.$orders->id.'" name="order_ids_for_generate_invoice[]" id="order_ids_for_generate_invoice'.$orders->id.'">';
            })
            ->addColumn('DT_RowIndex', function ($orders) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('cmpny_name', function ($orders) {
                return '<p class="f-light">'.$orders->company_name.'</p>';
            })
            ->addColumn('product', function ($orders) {
                return '<p class="f-light">'.$orders->product.'</p>';
            })
            ->addColumn('qty', function ($orders) {
                return '<span class="badge badge-light-primary">'.$orders->quantity.'</span>';
            })
            ->addColumn('del_date', function ($orders) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($orders->delivery_date)->format('d-m-Y').'</span>';
            })
            ->addColumn('order_status', function ($orders) {
                return '<span class="badge badge-light-info">'.$orders->status.'</span>';
            })
            ->addColumn('actions', function ($orders) {
                return '<p class="f-light">
                            <ul id="t-1" class="action simple-list flex-row list-group">
                              <li class="edit list-group-item"> <a href="javascript:;"><i class="fa fa-check" onclick="ChangeTheOrderStatus('.$orders->id.')"></i></a></li>
                            </ul>
                        </p>';
            })
            // <li class="delete list-group-item"> <a href="javascript:;"><i class="fa fa-close" onclick="ChangeTheOrderStatus()"></i></a></li>

            ->rawColumns(['order_checkbox', 'actions', 'cmpny_name', 'product', 'qty', 'del_date', 'disc_per', 'order_status'])
            ->make(true);
    }
    
    public function updatePackPendingOrders(Request $request){
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
            $update = Orders::where('id', $request->id)->update(['status' => 'Packed', 'updated_at' => now()]);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Order status has been updated successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update order status']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function getPackPackedOrders(Request $request)
    {
        $orders = Orders::select('orders.*', 'customers.company_name', 'products.product')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', 'LIKE', 'packed')
            ->orderBy('orders.id', 'DESC')
            ->get();

        return DataTables::of($orders)
            ->addColumn('order_checkbox', function ($orders) {
                return '<input type="checkbox" class="f-light" value="'.$orders->id.'" name="order_ids_for_generate_invoice[]" id="order_ids_for_generate_invoice'.$orders->id.'">';
            })
            ->addColumn('DT_RowIndex', function ($orders) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('cmpny_name', function ($orders) {
                return '<p class="f-light">'.$orders->company_name.'</p>';
            })
            ->addColumn('product', function ($orders) {
                return '<p class="f-light">'.$orders->product.'</p>';
            })
            ->addColumn('qty', function ($orders) {
                return '<span class="badge badge-light-primary">'.$orders->quantity.'</span>';
            })
            ->addColumn('del_date', function ($orders) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($orders->delivery_date)->format('d-m-Y').'</span>';
            })
            ->addColumn('order_status', function ($orders) {
                return '<span class="badge badge-light-secondary">'.$orders->status.'</span>';
            })
            // <li class="delete list-group-item"> <a href="javascript:;"><i class="fa fa-close" onclick="ChangeTheOrderStatus()"></i></a></li>

            ->rawColumns(['order_checkbox', 'cmpny_name', 'product', 'qty', 'del_date', 'disc_per', 'order_status'])
            ->make(true);
    }
    
    
}
