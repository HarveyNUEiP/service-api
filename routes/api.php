<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Profile
 */
Route::get('/profile', \App\Http\Controllers\api\ProfileController::class . '@index');

/**
 * Two Factor Authentication
 */
Route::get('tfa', \App\Http\Controllers\api\TwoFactorAuthController::class . '@getQRcode')
    ->name('twoFactorAuth.show');
Route::post('tfa', \App\Http\Controllers\api\TwoFactorAuthController::class . '@bind')
    ->name('twoFactorAuth.store');
Route::post('tfa/verify', \App\Http\Controllers\api\TwoFactorAuthController::class . '@verify')
    ->name('twoFactorAuth.verify');
Route::delete('tfa/{id}', \App\Http\Controllers\api\TwoFactorAuthController::class . '@unbind')
    ->name('twoFactorAuth.unbind');

Route::middleware(['verify.tfa'])->group(function () {
    /**
    * Account Management
    */
    Route::group(['prefix' => 'accountManagement'], function () {
        Route::group(['middleware' => 'permission:accountManagement.view'], function () {
            Route::get('/', [\App\Http\Controllers\api\AccountManagementController::class, 'index'])
                ->name('account.viewAny');
            Route::get('/{id}', [\App\Http\Controllers\api\AccountManagementController::class, 'show'])
                ->name('account.view');
        });
        Route::put('/{id}', [\App\Http\Controllers\api\AccountManagementController::class, 'update'])
            ->name('account.update')
            ->middleware('permission:accountManagement.update');
    });

    Route::get('syncAccount', [\App\Http\Controllers\api\AccountSyncController::class, 'index'])
        ->name('account.sync')
        ->middleware('role:admin');
});
