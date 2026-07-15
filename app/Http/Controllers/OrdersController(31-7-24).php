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
// use Illuminate\Support\Str;

class OrdersController extends Controller
{
    protected $pdfService;
    
    public function __construct(PDFGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $employees = Employee::whereNull('deleted_at')->where('employee_type', 'LIKE', 'SalesExecutive')->orderBy('id', 'DESC')->get();
        return view('marketing.Orders.orders', compact('datas', 'customers', 'employees'));
    }
    
    public function CreatePage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $pro_cats = DB::table('products_categories')->whereNull('deleted_at')->get();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $employees = Employee::whereNull('deleted_at')->whereIn('employee_type', ['SalesExecutive', 'Dealer'])->orderBy('id', 'DESC')->get();
        return view('marketing.Orders.assign_orders', compact('datas', 'customers', 'employees', 'pro_cats'));
    }
    
    public function EditPage(Request $request)
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $products = Product::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        $order = Orders::whereNull('deleted_at')->where('id', $request->id)->first();
        $employees = Employee::whereNull('deleted_at')->whereIn('employee_type', ['SalesExecutive', 'Dealer'])->orderBy('id', 'DESC')->get();
        return view('marketing.Orders.edit_orders', compact('datas', 'customers', 'products', 'order', 'employees'));
    }
    
    public function getOrders(Request $request)
    {
        $orders = Orders::select('orders.*', 'customers.company_name', 'products.product')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->leftJoin('employees', 'employees.id', '=', 'orders.employee_id')
            ->whereNull('orders.deleted_at')
            ->when($request->customer_id, function($query) use ($request){
                $query->where('orders.customer_id', $request->customer_id);
            })
            ->when($request->filter_order_status, function($query) use ($request){
                $query->where('orders.status', 'LIKE', $request->filter_order_status);
            })
            ->when($request->product_name_filter, function($query) use ($request){
                $query->where('products.product', 'LIKE', '%'.$request->product_name_filter.'%');
            })
            ->when($request->employee_filter, function($query) use ($request){
                $query->where('orders.employee_id', $request->employee_filter);
            })
            ->when($request->employee_zone_country_filter, function($query) use ($request){
                $query->where('employees.employee_zone_country', $request->employee_zone_country_filter);
            })
            ->when($request->employee_zone_state_filter, function($query) use ($request){
                $query->where('employees.employee_zone_state', $request->employee_zone_state_filter);
            })
            ->when($request->employee_zone_city_filter, function($query) use ($request){
                $query->where('employees.employee_zone_city', $request->employee_zone_city_filter);
            })
            ->when($request->from_date && $request->to_date, function($query) use ($request){
                $from_date = Carbon::parse($request->from_date)->startOfDay()->format('Y-m-d H:i:s');
                $to_date = Carbon::parse($request->to_date)->endOfDay()->format('Y-m-d H:i:s');
                $query->whereBetween('orders.created_at', [$from_date, $to_date]);
            })
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
            ->addColumn('disc_per', function ($orders) {
                return '<span class="badge badge-light-success">'.$orders->discount.' %</span>';
            })
            ->addColumn('disc_amt', function ($orders) {
                return '<span class="badge badge-light-success">'.$orders->discount_amt.'</span>';
            })
            ->addColumn('gst_percent', function ($orders) {
                return '<span class="badge badge-light-success">'.$orders->gst_per.' %</span>';
            })
            ->addColumn('gst_amount', function ($orders) {
                return '<span class="badge badge-light-success">'.$orders->gst_amt.'</span>';
            })
            ->addColumn('ttl_amt', function ($orders) {
                return '<span class="badge badge-light-success">'.$orders->total_amt.'</span>';
            })
            ->addColumn('acc_amount', function ($orders) {
                return '<span class="badge badge-light-success">'.$orders->discount_amt + $orders->actual_amt.'</span>';
            })
            ->addColumn('order_status', function ($orders) {
                switch(strtolower($orders->status)){
                    case 'pending':
                        $class = 'pending';
                        break;
                    case 'packed':
                        $class = 'packed';
                        break;
                    case 'verified':
                        $class = 'verified';
                        break;
                    case 'dispatched':
                        $class = 'dispatched';
                        break;
                    case 'delivered':
                        $class = 'delivered';
                        break;
                    case 'invoiced':
                        $class = 'invoiced';
                        break;
                    case 'cancelled':
                        $class = 'cancelled';
                        break;
                    default:
                        $class = 'placeholder';
                        break;
                }
                return '<select id="order_status_drop_0' . $orders->id . '" onchange="ChangeOrderStatus(this.value, ' . $orders->id . ')" class="order-status '.$class.'" style="border-radius: 20px;">
                            <option value="" style="text-align: center;">Select Status</option>
                            <option '.(strtolower($orders->status) == 'pending' ? 'selected' : '').' value="pending" style="text-align: center;">Pending</option>
                            <option '.(strtolower($orders->status) == 'packed' ? 'selected' : '').' value="packed" style="text-align: center;">Packed</option>
                            <option '.(strtolower($orders->status) == 'verified' ? 'selected' : '').' value="verified" style="text-align: center;">Verified</option>
                            <option '.(strtolower($orders->status) == 'dispatched' ? 'selected' : '').' value="dispatched" style="text-align: center;">Dispatched</option>
                            <option '.(strtolower($orders->status) == 'delivered' ? 'selected' : '').' value="delivered" style="text-align: center;">Delivered</option>
                            <option '.(strtolower($orders->status) == 'invoiced' ? 'selected' : '').' value="invoiced" style="text-align: center;">Invoiced</option>
                            <option '.(strtolower($orders->status) == 'cancelled' ? 'selected' : '').' value="cancelled" style="text-align: center;">Cancelled</option>
                        </select>';
            })
            ->addColumn('actions', function ($orders) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="'.route('order.edit', $orders->id).'"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-order" data-id="' . $orders->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['order_checkbox', 'actions', 'cmpny_name', 'product', 'qty', 'del_date', 'disc_per', 'order_status', 'disc_amt', 'gst_percent', 'gst_amount', 'ttl_amt', 'acc_amount'])
            ->make(true);
    }
    
    public function GetProductsForInput(Request $request)
    {
        $products = Product::whereNull('deleted_at')
            ->when($request->search, function($query) use ($request) {
                $query->where('product', 'LIKE', '%'.$request->search.'%');
            })
            ->orderBy('sort_order', 'DESC')
            ->get();
    
        return response()->json($products);
    }
    
    public function createOrders(Request $request){
        try {
            $rules = [
                'customer' => ['required'],
                'employee' => ['required'],
                'product.*' => ['required'],
                'quantity.*' => ['required', 'integer'],
                'delivery_date.*' => ['required', 'date'],
                'product_discount.*' => ['required', 'numeric']
            ];
            
            $messages = [
                'customer.required' => 'The company is required.',
                'employee.required' => 'The employee is required.',
                'product.*.required' => 'The products are required.',
                'quantity.*.required' => 'The quantities are required.',
                'quantity.*.integer' => 'The quantities must be integers.',
                'delivery_date.*.required' => 'The delivery dates are required.',
                'delivery_date.*.date' => 'The delivery dates must be valid dates.',
                'product_discount.*.required' => 'The discounts are required.',
                'product_discount.*.numeric' => 'The discounts must be numbers.'
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $data = $request->all();
            // dd($data);
            // Extract the arrays from the request
            $products = collect($data['product']);
            $quantities = collect($data['quantity']);
            $delivery_dates = collect($data['delivery_date']);
            $product_discounts = collect($data['product_discount']);
            
            $arr = $products->map(function ($product, $index) use ($data, $quantities, $delivery_dates, $product_discounts) {
                $productData = Product::find($product);
                $categories = json_decode($productData->category_id, true);
                if($categories && !in_array('1', $categories)){
                    $qty_check = $productData->quantity - $quantities[$index];
                    if((int)$qty_check < 0){
                        throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is minimum.');
                    }
                    // dd($qty_check);
                    // dd($categories[0]);
                    // $update = Product::whereNull('deleted_at')->where('category_id', $categories[0])->decrement('quantity', $quantities[$index]);
                    $update = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$categories[0].'"]\')')->decrement('quantity', $quantities[$index]);
                }else{
                    $qty_check = $productData->quantity - $quantities[$index];
                    if((int)$qty_check < 0){
                        throw new \Exception('Quantity of product no. ' . ($index + 1) . ' is minimum.');
                    }
                    $arr1 = [
                        'quantity' => $productData->quantity - $quantities[$index]
                    ];
                    // dd($arr1);
                    $update = Product::where('id', $product)->update($arr1);
                }
                if(!$update){
                    // return response()->json(['status' => 400, 'message' => 'Quantity Reduction Failed.']);
                    throw new \Exception('Quantity Reduction Failed.');
                }
                
                $product_price = $productData->actual_price;
                $product_sellprice = $productData->sell_price;
                $product_gst = $productData->gst;
                $product_dis = $productData->discount;
                
                if ($product_discounts[$index] == '0' || $product_discounts[$index] == null) {
                    $discount_per = $product_dis;
                    $ttl_sellpri = $product_price*$quantities[$index];
                    $dis_amount = ($product_dis / 100) * $ttl_sellpri;
                } else {
                    $discount_per = $product_discounts[$index];
                    $ttl_sellpri = $product_price*$quantities[$index];
                    $dis_amount = ($product_discounts[$index] / 100) * $ttl_sellpri;
                }
                
                $acc_amount = $ttl_sellpri-$dis_amount;
                $gst_amount = ($product_gst / 100) * $acc_amount;
                $ttl_amount = $acc_amount+$gst_amount;
        
                return [
                    'customer_id' => $data['customer'],
                    'product_id' => $product,
                    'employee_id' => $data['employee'],
                    'product_name' => $productData->product,
                    'quantity' => $quantities[$index],
                    'delivery_date' => $delivery_dates[$index],
                    'discount' => $discount_per,
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
            // dd($arr);
            $insert = Orders::insert($arr);
            if($insert){
                return response()->json(['status' => 200, 'message' => 'Order created successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to create order']);
            }

        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateOrders(Request $request)
    {
        try {
            $rules = [
                'customer' => ['required'],
                'employee' => ['required'],
                'product' => ['required'],
                'quantity' => ['required', 'integer'],
                'delivery_date' => ['required', 'date'],
                'product_discount' => ['required', 'numeric']
            ];
            
            $messages = [
                'customer.required' => 'The company is required.',
                'employee.required' => 'The employee are required.',
                'product.required' => 'The products are required.',
                'quantity.required' => 'The quantities are required.',
                'quantity.integer' => 'The quantities must be integers.',
                'delivery_date.required' => 'The delivery dates are required.',
                'delivery_date.date' => 'The delivery dates must be valid dates.',
                'product_discount.required' => 'The discounts are required.',
                'product_discount.numeric' => 'The discounts must be numbers.'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $productData = Product::find($request->product);
            $orderData = Orders::where('id', $request->id)->first();
            $categories = json_decode($productData->category_id, true);
            $previous_pro = Product::find($orderData->product_id);
            $previous_cat = json_decode($previous_pro->category_id, true);
            // dd($categories);
            if($categories && !in_array('1', $categories)){
                // dd('hgaf');
                $update1 = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$previous_cat[0].'"]\')')->increment('quantity', $orderData->quantity);
                if($update1){
                    $update = Product::whereNull('deleted_at')->whereRaw('JSON_CONTAINS(category_id, \'["'.$categories[0].'"]\')')->decrement('quantity', $request->quantity);
                }else{
                    return response()->json(['status' => 400, 'message' => 'Quantity increment Failed.']);
                }
            }else{
                $update1 = Product::whereNull('deleted_at')->where('id', $orderData->product_id)->update(['quantity' => (int)$previous_pro->quantity+(int)$orderData->quantity, 'updated_at' => now()]);
                $qty_check = $productData->quantity - $request->quantity;
                if((int)$qty_check < 0){
                     return response()->json(['status' => 400, 'message' => 'Quantity of product is minimum.']);
                }
                if($update1){
                    // dd(['quantity' => (int)$productData->quantity-(int)$request->quantity, 'updated_at' => now()]);
                    $update = Product::where('id', $request->product)->update(['quantity' => (int)$productData->quantity-(int)$request->quantity, 'updated_at' => now()]);
                }else{
                    return response()->json(['status' => 400, 'message' => 'Quantity increment Failed.']);
                }
                
            }
            if(!$update){
                return response()->json(['status' => 400, 'message' => 'Quantity Reduction Failed.']);
            }
                
            $product_price = $productData->actual_price;
            $product_sellprice = $productData->sell_price;
            $product_gst = $productData->gst;
            $product_dis = $productData->discount;
            
            if ($request->product_discount == '0' || $request->product_discount == null) {
                $discount_per = $product_dis;
                $ttl_sellpri = $product_price*$request->quantity;
                $dis_amount = ($product_dis / 100) * $ttl_sellpri;
            } else {
                $discount_per = $request->product_discount;
                $ttl_sellpri = $product_price*$request->quantity;
                $dis_amount = ($request->product_discount / 100) * $ttl_sellpri;
            }
            $acc_amount = $ttl_sellpri-$dis_amount;
            $gst_amount = ($product_gst / 100) * $acc_amount;
            $ttl_amount = $acc_amount+$gst_amount;
            
            // dd($ttl_amount);
            $arr = [
                    // 'customer_id' => $request->customer,
                    'product_id' => $productData->id,
                    'product_name' => $productData->product,
                    'employee_id' => $request->employee,
                    'quantity' => $request->quantity,
                    'delivery_date' => Carbon::parse($request->delivery_date)->format('y-m-d'),
                    'discount' => $discount_per,
                    'discount_amt' => $dis_amount,
                    'gst_per' => $product_gst,
                    'gst_amt' => $gst_amount,
                    'actual_amt' => $acc_amount,
                    'total_amt' => $ttl_amount,
                    // 'created_at' => now(),
                    'updated_at' => now(),
                ];
            // dd($arr);
            $update = Orders::where('id', $request->id)->update($arr);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Order update successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update order']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function changeOrderStatus(Request $request){
        try {
            $rules = [
                    'id' => ['required'],
                    'status' => ['required'],
                ];
            $messages = [
                    'id.required' => 'The product id is required.',
                    'status.required' => 'The order status is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $update = Orders::where('id', $request->id)->update(['status' => $request->status]);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Order status has been updated successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update order status']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteOrders(Request $request){
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
            $delete = Orders::where('id', $request->id)->update(['deleted_at' => now()]);
            if($delete){
                return response()->json(['status' => 200, 'message' => 'Order has been deleted successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete order']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function previewInvoice(Request $request)
    {
        try {
            $orders = Orders::select('orders.*', 'customers.company_name', 'products.product', 'products.actual_price as product_price')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->whereNull('orders.deleted_at')
            ->whereIn('orders.id', $request->order_ids)
            ->where('orders.status', '!=', 'invoiced')
            ->get()->toArray();
            // $orders = Orders::whereNull('deleted_at')->whereIn('id', $request->order_ids)->where('status', '!=', 'invoiced')->get()->toArray();
            // dd($orders);
            if (empty($orders)) {
                return response()->json(['status' => 400, 'message' => 'No valid orders found.']);
            }
            $html = $this->pdfService->getHTMLContent($orders);
            return response()->json(['preview_html' => $html]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function generateInvoice(Request $request)
    {
        try {
            $customer = Customer::whereNull('deleted_at')->where('id', $request->customer_id)->first();
            // dd($customer);
            if (!$customer) {
                return response()->json(['status' => 400, 'message' => 'Please select a particular customer to proceed invoice.']);
            }
            $rand = $this->generateRandomIntegerString(5);
            $invoice_no = 'F'.$rand;
            $invoice = Invoice::whereNull('deleted_at')
                            ->where('invoice_no', '=', $invoice_no)
                            ->first();
            if($invoice){
                return response()->json(['status' => 400, 'message' => 'Issue in generate invoice. Please contact admin.']);
            }
            
            $orders = Orders::select('orders.*', 'customers.company_name', 'products.product', 'products.actual_price as product_price')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('products', 'products.id', '=', 'orders.product_id')
            ->whereNull('orders.deleted_at')
            ->whereIn('orders.id', $request->order_ids)
            ->where('orders.status', '!=', 'invoiced')
            ->get()->toArray();
            $ttl_amt_orders = Orders::whereNull('orders.deleted_at')
            ->whereIn('orders.id', $request->order_ids)
            ->where('orders.status', '!=', 'invoiced')
            ->sum('orders.total_amt');
            // dd($ttl_amt_orders);
            // $orders = Orders::whereNull('deleted_at')
            //                 ->whereIn('id', $request->order_ids)
            //                 ->where('status', '!=', 'invoiced')
            //                 ->get()
            //                 ->toArray();

            if (empty($orders)) {
                return response()->json(['status' => 400, 'message' => 'No valid orders found.']);
            }
            $html = $this->pdfService->getHTMLContent($orders);
            
            $pdfContent = $this->pdfService->generatePDF($html);

            $directory = 'pdfs/customer_'.$request->customer_id;

            // Ensure the directory exists; create if it doesn't
            if (!File::isDirectory(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true);
            }

            // File path where the PDF will be stored
            $filepath_name = $directory.'/' . $customer->company_name.'_'.$customer->id.'_Invoice_'.$invoice_no.'.pdf';
            $filePath = public_path($filepath_name);
            // dd($filepath_name);
            // Save the PDF to the specified file path
            file_put_contents($filePath, $pdfContent);

            // Return the public URL/path to access the PDF
            $publicUrl = asset($filepath_name);
            
            $invoice_insert = Invoice::create([
                'customer_id' => $customer->id,
                'order_id' => json_encode($request->order_ids),
                'invoice_no' => $invoice_no,
                'invoice' => $filepath_name,
                'total_amount' => round((int)$ttl_amt_orders, 2),
                'pending_amount' => round((int)$ttl_amt_orders, 2),
                'payment_status' => 'pending'
                ]);
            if($invoice_insert == true){
                $order_update = Orders::whereIn('id', $request->order_ids)->update(['invoice_id' => $invoice_insert->id, 'invoice_no' => $invoice_no]);
                return response()->json(['status' => 200, 'url' => $publicUrl]);
            }else{
                return response()->json(['status' => 400, 'message' => 'Invoice entry failed.']);
            }
            
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    function generateRandomIntegerString(int $length): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
    
        // Generate an array of random integers of length $length
        $randomIntegers = array_map(function() use ($characters, $charactersLength) {
            return $characters[random_int(0, $charactersLength - 1)];
        }, range(1, $length));
    
        // Concatenate array elements into a string
        return implode('', $randomIntegers);
    }
    
    public function getCreditOrDebitBalance(Request $request){
        // dd($request->all());
        $get_ttlreturn_amt = DB::table('order_return_products')->whereNull('deleted_at')->where('customer_id', $request->customer_id)->sum('total_amount');
        $get_compenreturn_amt = DB::table('order_return_products')->whereNull('deleted_at')->where('customer_id', $request->customer_id)->sum('compensated_amount');
        $get_return_return_amt = DB::table('order_return_products')->whereNull('deleted_at')->where('customer_id', $request->customer_id)->sum('returned_amount');
        
        $invoice_pending = Invoice::whereNull('deleted_at')->where('customer_id', $request->customer_id)->where('payment_status', 'NOT LIKE', 'paid')->sum('pending_amount');
        
        $cm_ret_total = (int)$get_compenreturn_amt + (int)$get_return_return_amt;
        if((int)$get_ttlreturn_amt > $cm_ret_total){
            $final_ret_bal = (int)$get_ttlreturn_amt - $cm_ret_total;
        }else{
            $final_ret_bal = $cm_ret_total - (int)$get_ttlreturn_amt;
        }
        if((int)$invoice_pending > $final_ret_bal){
            $amount = (int)$invoice_pending - $final_ret_bal;
            $message = 'Pending amount from the customer side.';
        }else{
            $amount = $final_ret_bal - (int)$invoice_pending;
            $message = 'Pending amount of need to settle to customer.';
        }
        return response()->json(['amount' => $amount, 'message' => $message]);
    }
    
    public function getProductsListForOrder(Request $request){
        // dd($request->all());
        $products = Product::whereNull('deleted_at')->where('category_id', $request->id)->get();
        
        $html = '';
        for ($i = 0; $i < $products->count(); $i++){
            $html .= '<div class="employee-row d-flex my-2" data-x-group="">
                        <div class="col-md-3 position-relative ">
                        <label class="form-label show-alert show-alert__success" for="Products">Products</label>
                            <select class="form-control" name="product[]" id="product_'.($i+1).'">
                                <option selected value="'.$products[$i]->id.'">'.$products[$i]->product.'</option>
                            </select>
                        </div>
                        
            
                        <div class="col-md-3 position-relative">
                        <label class="form-label" for="Quantity">Quantity</label>
                            <input type="number" name="quantity[]" id="quantity_'.($i+1).'" class="form-control" placeholder="Quantity">
                        </div>
            
                        <div class="col-xxl-2 col-sm-6">
                            <label class="form-label" for="delivary_date">Delivery Date</label>
                            <input id="delivery_date_'.($i+1).'" name="delivery_date[]" class="delivery-date form-control" type="date">
                        </div>
            
                        <div class="col-xxl-2 col-sm-6" style="margin-left: 1%;">
                          <label class="form-label" for="product_discount">Discount %</label>
                          <input type="number" class="form-control" name="product_discount[]" id="product_discount_'.($i+1).'" autocomplete="off" placeholder="Discount %">
                        </div>
            
                        <div class="col-md-2 position-relative one">
                            <button type="button" class="btn btn-danger" data-remove-btn="">-</button>
                            <button type="button" class="btn btn-success" data-add-btn="">+</button>
                        </div>
                    </div>';
        }
        
        return response()->json($html);
    }
}
