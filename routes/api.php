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

Route::middleware(['auth.jwt', 'validate.user.status'])->group(function () {
    Route::get('/profile', \App\Http\Controllers\api\ProfileController::class . '@index');

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
