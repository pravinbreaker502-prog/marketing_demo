<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Product
    };
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $pro_cats = DB::table('products_categories')->whereNull('deleted_at')->get();
        return view('marketing.products.products', compact('datas', 'pro_cats'));
    }
    
    public function CreatePage()
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $pro_cats = DB::table('products_categories')->whereNull('deleted_at')->get();
        return view('marketing.products.add_products', compact('datas', 'pro_cats'));
    }
    
    public function EditPage(Request $request)
    {
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        $datas['headermenu'] = HeaderMenu();
        $datas['sidemenubar'] = SideMenuBars();
        $pro_cats = DB::table('products_categories')->whereNull('deleted_at')->get();
        $product = Product::whereNull('deleted_at')->where('id', $request->id)->first();
        return view('marketing.products.edit_product', compact('datas', 'product', 'pro_cats'));
    }
    
    public function getProducts(Request $request)
    {
        $products = Product::select('products.*', 'products_categories.category_name')
            ->whereNull('products.deleted_at')
            ->leftjoin('products_categories', 'products_categories.id', '=', 'products.category_id')
            ->orderBy('id', 'DESC')
            ->get();

        return DataTables::of($products)
            ->addColumn('DT_RowIndex', function ($products) {
                static $i = 1;
                return $i++;
            })
            ->addColumn('product_caregory', function ($products) {
                return '<p class="f-light">'.($products->category_id != NULL || $products->category_id != '' ? $products->category_name : '-').'</p>';
            })
            ->addColumn('product_name', function ($products) {
                return '<p class="f-light">'.$products->product.'</p>';
            })
            ->addColumn('qty', function ($products) {
                return '<p class="f-light">'.$products->quantity.'</p>';
            })
            ->addColumn('acc_price', function ($products) {
                return '<p class="f-light">'.$products->actual_price.'</p>';
            })
            ->addColumn('dis_per', function ($products) {
                return '<p class="f-light">'.$products->discount.'%</p>';
            })
            ->addColumn('sell', function ($products) {
                return '<p class="f-light">'.$products->sell_price.'</p>';
            })
            ->addColumn('gst_per', function ($products) {
                return '<p class="f-light">'.$products->gst.'%</p>';
            })
            ->addColumn('actions', function ($products) {
                return '<p class="f-light">
                          <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="'.route('products.edit', $products->id).'"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-product" data-id="' . $products->id . '"><i class="fa fa-trash"></i></a></li>
                          </ul>
                        </p>';
            })

            ->rawColumns(['actions', 'product_caregory', 'product_name', 'qty', 'acc_price', 'dis_per', 'sell', 'gst_per'])
            ->make(true);
    }
    
    public function createProducts(Request $request)
    {
        try {
            $rules = [
                'product_category' => ['required'],
                'product_name' => ['required', 'string'],
                'product_qty' => ['required', 'integer'],
                'product_purprice' => ['required', 'numeric'],
                'product_accprice' => ['required', 'numeric'],
                'product_discount' => ['required', 'integer'],
                'product_sellprice' => ['required', 'numeric'],
                'product_gst' => ['required', 'integer']
            ];
            $messages = [
                'product_category.required' => 'The product category is required.',
                'product_name.required' => 'The product name is required.',
                'product_qty.required' => 'The quantity is required.',
                'product_qty.integer' => 'The quantity must be an integer.',
                'product_purprice.required' => 'The purchase price is required.',
                'product_purprice.numeric' => 'The purchase price must be a number.',
                'product_accprice.required' => 'The actual price is required.',
                'product_accprice.numeric' => 'The actual price must be a number.',
                'product_discount.required' => 'The discount is required.',
                'product_discount.integer' => 'The discount must be an integer.',
                'product_sellprice.required' => 'The sell price is required.',
                'product_sellprice.numeric' => 'The sell price must be a number.',
                'product_gst.required' => 'The GST is required.',
                'product_gst.integer' => 'The GST must be an integer.'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $gst_amount = ((int)$request->product_gst/100)*(int)$request->product_sellprice;
            $dis_amount = ((int)$request->product_discount/100)*(int)$request->product_accprice;
            $product_slug = Str::slug($request->product_name) . '-' . Str::random(4);
            $arr = [
                'category_id' => $request->product_category,
                'product' => $request->product_name,
                'page_slug' => $product_slug,
                'quantity' => $request->product_qty,
                'actual_price' => $request->product_accprice,
                'discount' => $request->product_discount,
                'discount_amt' => $dis_amount,
                'sell_price' => $request->product_sellprice,
                'gst' => $request->product_gst,
                'gst_amt' => $gst_amount,
                'product_purprice' => $request->product_purprice,
                'sort_order' => $request->sort_order
                ];
            // dd($arr);
            $insert = Product::create($arr);
            if($insert){
                return response()->json(['status' => 200, 'message' => 'Product created successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to create product']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateProducts(Request $request)
    {
        try {
            $rules = [
                'id' => ['required'],
                'product_category' => ['required'],
                'product_name' => ['required', 'string'],
                'product_qty' => ['required', 'integer'],
                'product_purprice' => ['required', 'numeric'],
                'product_accprice' => ['required', 'numeric'],
                'product_discount' => ['required', 'integer'],
                'product_sellprice' => ['required', 'numeric'],
                'product_gst' => ['required', 'integer']
            ];
            $messages = [
                'product_category.required' => 'The product category is required.',
                'id.required' => 'The product id is required.',
                'product_name.required' => 'The product name is required.',
                'product_qty.required' => 'The quantity is required.',
                'product_qty.integer' => 'The quantity must be an integer.',
                'product_purprice.required' => 'The purchase price is required.',
                'product_purprice.numeric' => 'The purchase price must be a number.',
                'product_accprice.required' => 'The actual price is required.',
                'product_accprice.numeric' => 'The actual price must be a number.',
                'product_discount.required' => 'The discount is required.',
                'product_discount.integer' => 'The discount must be an integer.',
                'product_sellprice.required' => 'The sell price is required.',
                'product_sellprice.numeric' => 'The sell price must be a number.',
                'product_gst.required' => 'The GST is required.',
                'product_gst.integer' => 'The GST must be an integer.'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            $gst_amount = ((int)$request->product_gst/100)*(int)$request->product_sellprice;
            $dis_amount = ((int)$request->product_discount/100)*(int)$request->product_accprice;
            $product_slug = Str::slug($request->product_name) . '-' . Str::random(4);
            $arr = [
                'category_id' => $request->product_category,
                'product' => $request->product_name,
                'page_slug' => $product_slug,
                'quantity' => $request->product_qty,
                'actual_price' => $request->product_accprice,
                'discount' => $request->product_discount,
                'discount_amt' => $dis_amount,
                'sell_price' => $request->product_sellprice,
                'gst' => $request->product_gst,
                'gst_amt' => $gst_amount,
                'product_purprice' => $request->product_purprice,
                'sort_order' => $request->sort_order
                ];
            // dd($arr);
            $update = Product::where('id', $request->id)->update($arr);
            if($update){
                return response()->json(['status' => 200, 'message' => 'Product updated successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to update product']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteProducts(Request $request){
        try {
            $rules = [
                    'id' => ['required']
                ];
            $messages = [
                    'id.required' => 'The product id is required.'
                ];
            $validator = Validator::make($request->all(), $rules, $messages);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 401,
                    'errors' => $validator->errors()->messages()
                ]);
            }
            // dd(['deleted_at' => now()]);
            $delete = Product::where('id', $request->id)->update(['deleted_at' => now()]);
            if($delete){
                return response()->json(['status' => 200, 'message' => 'Product deleted successfully']);
            }else{
                return response()->json(['status' => 400, 'message' => 'Failed to delete product']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }
    
}
