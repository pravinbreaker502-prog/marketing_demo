<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Customer,
    Orders,
    Invoice,
    PaymentHistory
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

class InvoiceController extends Controller
{
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $customers = Customer::whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        return view('marketing.invoices.invoice', compact('datas', 'customers'));
    }
    
    public function getInvoices(Request $request)
    {
        // Retrieve the invoices with the specified conditions
        $invoices = Invoice::select('invoice_history.*', 'customers.company_name')
            ->leftJoin('customers', 'customers.id', '=', 'invoice_history.customer_id')
            ->whereNull('invoice_history.deleted_at')
            ->when($request->customer_id, function($query) use ($request){
                $query->where('invoice_history.customer_id', $request->customer_id);
            })
            ->when($request->from_date && $request->to_date, function($query) use ($request){
                $from_date = Carbon::parse($request->from_date)->startOfDay()->format('Y-m-d H:i:s');
                $to_date = Carbon::parse($request->to_date)->endOfDay()->format('Y-m-d H:i:s');
                $query->whereBetween('invoice_history.created_at', [$from_date, $to_date]);
            })
            ->orderBy('invoice_history.id', 'DESC')
            ->get();

        return DataTables::of($invoices)
            ->addColumn('DT_RowIndex', function ($invoices) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('cmpny_name', function ($invoices) {
                return '<p class="f-light">'.$invoices->company_name.'</p>';
            })
            ->addColumn('invoice_number', function ($invoices) {
                return '<p class="f-light">'.$invoices->invoice_no.'</p>';
            })
            ->addColumn('invoice_date', function ($invoices) {
                return '<span class="badge badge-light-primary">'.Carbon::parse($invoices->created_at)->format('d-m-Y h:i A').'</span>';
            })
            ->addColumn('invoice_pre', function ($invoices) {
                return '<a target="_blank" href="'.asset($invoices->invoice).'"><i class="fa fa-file-pdf-o"></i></a>';
            })
            ->addColumn('dwnld_invoice', function ($invoices) {
                return '<a href="'.asset($invoices->invoice).'" download><i class="fa fa-download"></i></a>';
            })
            ->addColumn('pay_btn', function ($invoices) {
                return '<a href="javascript:PaymentForInvoice('.$invoices->id.')" download><i class="fa fa-credit-card" aria-hidden="true"></i></a>';
            })
            ->addColumn('payment_status_show', function ($invoices) {
                return '<p class="f-light" '.($invoices->payment_status == 'pending' ? 'style="color: #eb0000;"' : ($invoices->payment_status == 'paid' ? 'style="color: #06a506;"' : ($invoices->payment_status == 'partialed' ? 'style="color: #d6d606;"' : ''))).'>'.($invoices->payment_status == 'pending' ? 'UnPaid' : ($invoices->payment_status == 'paid' ? 'Paid' : ($invoices->payment_status == 'partialed' ? 'Partialy Paid' : ''))).'</p>';
            })
            ->addColumn('actions', function ($invoices) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="delete list-group-item"> <a href="#" class="delete-invoice" data-id="' . $invoices->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'cmpny_name', 'invoice_number', 'invoice_date', 'invoice_pre', 'dwnld_invoice', 'pay_btn', 'payment_status_show'])
            ->make(true);
    }
    
    public function PayFullInvoiceAmount(Request $request){
        // dd($request->all());
        try{
            
            if($request->history == 'get_invoice_data'){
                $invoice123 = Invoice::where('id', $request->invoice_id)->first();
                return response()->json($invoice123 ? ['status' => 200, 'data' => $invoice123] : ['status' => 400, 'message' => 'Invoice not found..!']);
            }
            $invoice = Invoice::where('id', $request->invoice_id)->first();
            if((int)$request->invoice_amount > (int)$invoice->pending_amount){
                return response()->json(['status' => 402, 'message' => 'The given amount is greater than the pending amount. So please use split pay to split the given amount to the pending invoices.']);
            }
            // if((int)$invoice->pending_amount > (int)$request->invoice_amount){
            //     return response()->json(['status' => 402, 'message' => 'Please pay full amount in the direct pay or use split pay to process the payment.']);
            // }
            $pending_amount = ((int)$invoice->total_amount - (int)$invoice->paid_amount) - (int)$request->invoice_amount;
            $arr = [
                'paid_amount' => (int)$invoice->paid_amount+(int)$request->invoice_amount,
                'pending_amount' => $pending_amount <= 0 ? NULL : $pending_amount,
                'payment_status' => $pending_amount <= 0 ? 'paid' : 'partialed',
                'updated_at' => now()
                ];
            // dd($arr);
            $update = Invoice::where('id', $request->invoice_id)->update($arr);
            if($update){
                $arr1 = [
                    'customer_id' => $invoice->customer_id,
                    'invoice_id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'amount' => (int)$request->invoice_amount > (int)$invoice->pending_amount ? $invoice->pending_amount : $request->invoice_amount,
                    'payment_status' => 'success'
                    ];
                $payment_update = PaymentHistory::create($arr1);
                if(!$payment_update){
                    return response()->json(['status' => 400, 'message' => 'Failed to update payment history..!']);
                }
            }
            return response()->json($update ? ['status' => 200, 'message' => 'Payment Successfull..!'] : ['status' => 400, 'message' => 'Failed to pay..!']);
            
        }catch(\Exception $e){
            return response()->json(['status' => 500, 'error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    public function PaySplitInvoiceAmount(Request $request){
        // dd($request->all());
        try{
            
            if($request->history == 'get_invoice_data'){
                $invoice123 = Invoice::where('id', $request->invoice_id)->first();
                return response()->json($invoice123 ? ['status' => 200, 'data' => $invoice123] : ['status' => 400, 'message' => 'Invoice not found..!']);
            }
            $invoice = Invoice::where('id', $request->invoice_id)->first();
            $sumof_pending = Invoice::where('customer_id', $invoice->customer_id)->where('payment_status', 'NOT LIKE', 'paid')->sum('pending_amount');
            // dd($sumof_pending);
            if((int)$request->invoice_amount > (int)$sumof_pending){
                return response()->json(['status' => 402, 'message' => 'The given amount is greater than the sum of all pending invoices amount. So kindly check the amount before you pay.']);
            }
            if((int)$request->invoice_amount <= (int)$invoice->pending_amount){
                return response()->json(['status' => 402, 'message' => 'Kindly use direct pay to proceed the payment.']);
            }
            // dd($request->all());
            $pending_amount = ((int)$invoice->total_amount - (int)$invoice->paid_amount) - (int)$invoice->pending_amount;
            $total_pending = (int)$request->invoice_amount - (int)$invoice->pending_amount;
            $_COOKIE['total_pending'] = $total_pending;
            // setcookie('total_pending', $total_pending, time() + 3600, "/");
            // dd($_COOKIE['total_pending']);
            $arr = [
                'paid_amount' => (int)$invoice->paid_amount+(int)$invoice->pending_amount,
                'pending_amount' => $pending_amount <= 0 ? NULL : $pending_amount,
                'payment_status' => $pending_amount <= 0 ? 'paid' : 'partialed',
                'updated_at' => now()
                ];
            // dd($arr);
            $update = Invoice::where('id', $request->invoice_id)->update($arr);
            if($update){
                $arr1 = [
                    'customer_id' => $invoice->customer_id,
                    'invoice_id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'amount' => (int)$request->invoice_amount > (int)$invoice->pending_amount ? $invoice->pending_amount : $request->invoice_amount,
                    'payment_status' => 'success'
                    ];
                $payment_update = PaymentHistory::create($arr1);
                if(!$payment_update){
                    return response()->json(['status' => 400, 'message' => 'Failed to update payment history..!']);
                }
                $pending_invoices = Invoice::where('customer_id', $invoice->customer_id)->where('payment_status', 'NOT LIKE', 'paid')->orderBy('id', 'ASC')->get();
                for($i = 0; $i < $pending_invoices->count(); $i++){
                    $pen_invoice = Invoice::where('id', $pending_invoices[$i]->id)->where('payment_status', 'NOT LIKE', 'paid')->first();
                    // dd($pen_invoice);
                    $total_pending = $_COOKIE['total_pending'];
                    $new_total_pending1 = $total_pending - $pen_invoice->pending_amount;
                    $new_total_pending2 = $pen_invoice->pending_amount - $total_pending;
                    // dd(abs($total_pending));
                    $new_total_pending3 = $total_pending > $pen_invoice->pending_amount ? $new_total_pending1 : $new_total_pending2;
                    $new_total_pending = abs($new_total_pending3) <= 0 ? 0 : abs($new_total_pending3);
                    $new_total_pending = abs($new_total_pending);
                    $new_total_pending_check = $new_total_pending;
                    $_COOKIE['total_pending'] = $new_total_pending;
                    // if($i == 1){
                    // dd($total_pending);
                    // }
                    $reduce_amount = (int)$pen_invoice->pending_amount < $total_pending ? $pen_invoice->pending_amount : $total_pending;
                    $pending_amount123 = ((int)$pen_invoice->total_amount - (int)$pen_invoice->paid_amount) - (int)$reduce_amount;
                    $arr2 = [
                        'paid_amount' => (int)$pen_invoice->paid_amount+(int)$reduce_amount,
                        'pending_amount' => $pending_amount123 <= 0 ? NULL : $pending_amount123,
                        'payment_status' => $pending_amount123 <= 0 ? 'paid' : 'partialed',
                        'updated_at' => now()
                        ];
                    $finalupdate = Invoice::where('id', $pen_invoice->id)->update($arr2);
                    $arr3 = [
                        'customer_id' => $pen_invoice->customer_id,
                        'invoice_id' => $pen_invoice->id,
                        'invoice_no' => $pen_invoice->invoice_no,
                        'amount' => $reduce_amount,
                        'payment_status' => 'success'
                        ];
                    $payment_update = PaymentHistory::create($arr3);
                    if ($new_total_pending_check <= 0) {
                        break;
                    }
                }
            }
            return response()->json($update ? ['status' => 200, 'message' => 'Payment Successfull..!'] : ['status' => 400, 'message' => 'Failed to pay..!']);
            
        }catch(\Exception $e){
            return response()->json(['status' => 500, 'error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    public function deleteInvoices(Request $request){
        try {
            $rules = [
                    'id' => ['required']
                ];
            $messages = [
                    'id.required' => 'The invoice id is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $delete = Invoice::where('id', $request->id)->update(['deleted_at' => now()]);
            if($delete){
                $payment_update = PaymentHistory::where('invoice_id', $request->id)->update(['deleted_at' => now()]);
                return response()->json(['status' => 200, 'message' => 'Invoice deleted successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete invoice']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
}
