<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController
};


Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login']);
Route::post('user-check', [AuthController::class, 'CheckUserlogin'])->name('user-check');
Route::get('/dashboard', function () {
    $datas['headerlinks'] = HeaderLinks();
    $datas['scriptlinks'] = ScriptLinks();
    $datas['headermenu'] = HeaderMenu();
    $datas['sidemenubar'] = SideMenuBars();
    return view('index', compact('datas'));
});
