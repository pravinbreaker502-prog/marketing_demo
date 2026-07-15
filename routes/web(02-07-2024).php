<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    IndexController,
    ProductController,
    CustomerController,
    OrdersController,
    InvoiceController,
    EmployeeController
};
use App\Http\Controllers\Packing\{
    PackingOrdersController
};


Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('admin-login');
Route::post('user-check', [AuthController::class, 'CheckUserlogin'])->name('user-check');
Route::post('logout', [AuthController::class, 'logout']);
Route::get('/dashboard', function () {
    $datas['headerlinks'] = HeaderLinks();
    $datas['scriptlinks'] = ScriptLinks();
    $datas['headermenu'] = HeaderMenu();
    $datas['sidemenubar'] = SideMenuBars();
    return view('index', compact('datas'));
});

Route::post('get-countries', [IndexController::class, 'GteCountries'])->name('countries.get');
Route::post('get-states', [IndexController::class, 'GetStates'])->name('states.get');
Route::post('get-cities', [IndexController::class, 'GetCities'])->name('cities.get');

Route::get('/products', [ProductController::class, 'index']);
Route::get('/add_products', [ProductController::class, 'CreatePage']);
Route::post('create-products', [ProductController::class, 'createProducts'])->name('products.create');
Route::post('get-products', [ProductController::class, 'getProducts'])->name('products.get');
Route::get('/product/{id}', [ProductController::class, 'EditPage'])->name('products.edit');
Route::post('update-products', [ProductController::class, 'updateProducts'])->name('products.update');
Route::post('delete-products', [ProductController::class, 'deleteProducts'])->name('products.delete');

Route::get('/clients', [CustomerController::class, 'index']);
Route::get('/add_client', [CustomerController::class, 'CreatePage']);
Route::post('create-customers', [CustomerController::class, 'createCustomers'])->name('customers.create');
Route::post('get-customers', [CustomerController::class, 'getCustomers'])->name('customers.get');
Route::get('/client/{id}', [CustomerController::class, 'EditPage'])->name('customers.edit');
Route::post('update-customers', [CustomerController::class, 'updateCustomers'])->name('customers.update');
Route::post('delete-customers', [CustomerController::class, 'deleteCustomers'])->name('customers.delete');

Route::get('/orders', [OrdersController::class, 'index']);
Route::get('/assign_orders', [OrdersController::class, 'CreatePage']);
Route::get('/order/{id}', [OrdersController::class, 'EditPage'])->name('order.edit');
Route::post('get-orders', [OrdersController::class, 'getOrders'])->name('orders.get');
Route::post('get-products-for-input', [OrdersController::class, 'GetProductsForInput'])->name('products-for-input.get');
Route::post('create-orders', [OrdersController::class, 'createOrders'])->name('orders.create');
Route::post('update-orders', [OrdersController::class, 'updateOrders'])->name('orders.update');
Route::post('change-order-status', [OrdersController::class, 'changeOrderStatus'])->name('order-status.change');
Route::post('delete-orders', [OrdersController::class, 'deleteOrders'])->name('orders.delete');
Route::post('/preview-invoice', [OrdersController::class, 'previewInvoice'])->name('invoice.preview');
Route::post('/generate-invoice', [OrdersController::class, 'generateInvoice'])->name('invoice.generate');
// Route::post('invoice', [OrdersController::class, 'invoice'])->name('invoices.invoice');

Route::get('/invoice', [InvoiceController::class, 'index']);
Route::post('get-invoices', [InvoiceController::class, 'getInvoices'])->name('invoices.get');

Route::get('/employee_list', [EmployeeController::class, 'index']);
Route::get('/add_employee', [EmployeeController::class, 'CreatePage']);
Route::get('/employee/{id}', [EmployeeController::class, 'EditPage'])->name('employees.edit');
Route::post('create-employees', [EmployeeController::class, 'createEmployees'])->name('employees.create');
Route::post('get-employees', [EmployeeController::class, 'getEmployees'])->name('employees.get');
Route::post('update-employees', [EmployeeController::class, 'updateEmployees'])->name('employees.update');
Route::post('delete-employees', [EmployeeController::class, 'deleteEmployees'])->name('employees.delete');

Route::get('/pack-pending-orders', [PackingOrdersController::class, 'index']);
Route::post('get-pack-pending-orders', [PackingOrdersController::class, 'getPackPendingOrders'])->name('pack-pending-orders.get');
Route::post('update-pack-pending-orders-status', [PackingOrdersController::class, 'updatePackPendingOrders'])->name('pack-pending-orders-status.update');
Route::get('/pack-packed-orders', [PackingOrdersController::class, 'packPackedPage']);
Route::post('get-pack-packed-orders', [PackingOrdersController::class, 'getPackPackedOrders'])->name('pack-packed-orders.get');
