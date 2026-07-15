<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\{
    ValidateUser
};

use App\Http\Controllers\Api\{
  AuthController,
  PunchingController,
  LeaveRequestController,
  CustomerController,
  FollowupHistoryController,
  SampleNOrdersController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'Userlogin']);

Route::middleware([ValidateUser::class])->group(function () {
    
    Route::post('profile', [AuthController::class, 'UserProfileData']);
    Route::post('editprofile', [AuthController::class, 'UpdateProfileData']);
    Route::post('logout', [AuthController::class, 'UserLogout']);
    
    Route::post('punchin', [PunchingController::class, 'PunchingStatusEntry']);
    Route::post('odometerhistory', [PunchingController::class, 'OdometerHistory']);
    
    Route::post('addleave', [LeaveRequestController::class, 'LeaveRequestsEntry']);
    Route::post('leavehistory', [LeaveRequestController::class, 'LeaveHistory']);
    
    Route::post('addclient', [CustomerController::class, 'AddClient']);
    Route::post('schoolslist', [CustomerController::class, 'ClientsList']);
    Route::post('editschoollist', [CustomerController::class, 'EditClient']);
    
    Route::post('saveorderform', [FollowupHistoryController::class, 'SaveFollowupHistory']);
    Route::post('followuphistory', [FollowupHistoryController::class, 'GetFollowupHistory']);
    Route::post('editfollowup', [FollowupHistoryController::class, 'EditnUpdateFollowupHistory']);
    Route::post('schoolvisits', [FollowupHistoryController::class, 'GetSchoolVisits']);
    
    Route::post('fefdyproducts', [SampleNOrdersController::class, 'ProductsList']);
    Route::post('saveallproducts', [SampleNOrdersController::class, 'SaveSampleNOrders']);
    Route::post('savesingleproduct', [SampleNOrdersController::class, 'SaveSingleSampleNOrder']);
    Route::post('getsavedproducts', [SampleNOrdersController::class, 'GetSavedSampleNOrders']);
    Route::post('removeorder', [SampleNOrdersController::class, 'RemoveSavedSampleNOrders']);
    Route::post('orderhistory', [SampleNOrdersController::class, 'GetOrdersHistory']);
    
});