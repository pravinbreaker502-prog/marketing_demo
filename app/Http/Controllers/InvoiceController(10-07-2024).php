<?php

namespace App\Http\Controllers;

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

            ->rawColumns(['cmpny_name', 'invoice_number', 'invoice_date', 'invoice_pre', 'dwnld_invoice', 'pay_btn'])
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
            $pending_amout = ((int)$invoice->total_amount - (int)$invoice->paid_amount) - (int)$request->invoice_amount;
            $arr = [
                'paid_amount' => (int)$invoice->paid_amount+(int)$request->invoice_amount,
                'pending_amount' => $pending_amout,
                'payment_status' => $pending_amout == 0 ? 'paid' : 'partialed',
                'updated_at' => now()
                ];
            // dd($arr);
            $update = Invoice::where('id', $request->invoice_id)->update($arr);
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
            // dd($request->all());
            $pending_amout = ((int)$invoice->total_amount - (int)$invoice->paid_amount) - (int)$invoice->pending_amount;
            $total_pending = (int)$request->invoice_amount - (int)$invoice->pending_amount;
            // dd($total_pending);
            $arr = [
                'paid_amount' => (int)$invoice->paid_amount+(int)$invoice->pending_amount,
                'pending_amount' => $pending_amout,
                'payment_status' => $pending_amout == 0 ? 'paid' : 'partialed',
                'updated_at' => now()
                ];
            // dd($arr);
            $update = Invoice::where('id', $request->invoice_id)->update($arr);
            if($update){
                $pending_invoices = Invoice::where('customer_id', $invoice->customer_id)->where('payment_status', 'NOT LIKE', 'paid')->get();
            }
            return response()->json($update ? ['status' => 200, 'message' => 'Payment Successfull..!'] : ['status' => 400, 'message' => 'Failed to pay..!']);
            
        }catch(\Exception $e){
            return response()->json(['status' => 500, 'error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
}
