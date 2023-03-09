<?php

use App\Http\Controllers\ApplicationSettingsController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SmscodeController;
use App\Http\Controllers\SocialsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Models\Tariff;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// TODO перенести в контороллер
Route::get('/', function () {
    $tariffs = Tariff::all();
    return view('welcome', compact('tariffs'));
})->name('home');

//TODO Delete
Route::get('24-pay-in/{contract_id}', function ($id) {
    Artisan::call("24:pay-in", ['contract' => $id, '--take' => 1]);

    return back();
})->name('pay-in');
Route::get('24-period/{contract_id}', function ($id) {
    Artisan::call("24:period", ['contract' => $id]);

    return back();
})->name('period');

//TODO Удалить лишние роуты
Route::get('pdf', [PdfController::class, 'get']);
Route::get('pdf/get', [PdfController::class, 'get'])->name('pdf.invoice');

Auth::routes(['verify' => true]);

Route::middleware('guest')->group(function () {
    Route::get('/auth/{driver}/redirect', [SocialsController::class, 'redirect'])
        ->where('driver', 'yandex|vkontakte|google')
        ->name('auth.social');
    Route::get('/auth/{driver}/callback', [SocialsController::class, 'callback'])
        ->where('driver', 'yandex|vkontakte|google');
});

Route::prefix('personal')
    ->middleware('auth')->group(function () {
        Route::get('profile', [UserProfileController::class, 'profile'])
            ->name('users.profile');
        Route::post('save_profile/{user}', [UserProfileController::class, 'storeProfile'])
        ->name('save_profile');
        Route::post('save_profile_organization/{user}', [UserProfileController::class, 'storeProfileOrganization'])
        ->name('save_profile_organization');
        Route::post('save_profile_requisites/{user}', [UserProfileController::class, 'storeProfileRequisites'])
        ->name('save_profile_requisites');
        Route::post('save_profile_password/{user}', [UserProfileController::class, 'passwordReset'])
        ->name('save_profile_password');
        
        Route::get('contracts', [ContractController::class, 'index'])
        ->middleware('can:see own profile')
        ->name('users.contracts');
        
        Route::get('contracts/{contract}/show', [ContractController::class, 'show'])
        ->middleware('can:see own profile')
        ->name('users.contract_show');
        
        Route::get('add_contract', [ContractController::class, 'create'])
        ->middleware('can:see own profile')
        ->name('users.add_contract');


        Route::post('add_contract', [ContractController::class, 'store'])
            ->middleware('can:see own profile')
            ->name('contracts.store');

        Route::get('contracts/{contract}/cancel', [ContractController::class, 'cancel'])
            ->middleware('can:see own profile')
            ->name('contracts.cancel');

        Route::post('contracts/{contract}/increase_amount', [ContractController::class, 'increaseAmount'])
            ->middleware('can:see own profile')
            ->name('contracts.increase_amount');

        Route::get('contracts/{contract}/cancel_change', [ContractController::class, 'cancelChange'])
            ->middleware('can:see own profile')
            ->name('contracts.cancel_change');

        Route::get('payments', [PaymentController::class, 'indexForUser'])
            ->middleware('can:see own profile')
            ->name('payments.for_user');

        
        Route::get('phone_confirmation', [SmscodeController::class,'phoneConfirmation'])
        ->middleware('can:see own profile')
        ->name('users.phone_confirmation');
        
        Route::get('create_smscode/{operation_type}', [SmscodeController::class,'createCode'])
        ->middleware('can:see own profile')
        ->name('users.create_smscode');
                Route::get('dadata_test', [UserProfileController::class,'dadataTest'])
        ->middleware('can:see own profile')
        ->name('users.create_smscode');

    });

Route::prefix('admin')
    ->middleware('auth')->group(function () {
        Route::post('users/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('can:assign roles')
            ->name('users.update-role');

        Route::post('users/{user}/block', [UserController::class, 'blockUser'])
            ->middleware('can:block users')
            ->name('users.block');

        Route::post('users/{user}/unblock', [UserController::class, 'unblockUser'])
            ->middleware('can:block users')
            ->name('users.unblock');

        Route::post('users/create', [UserController::class, 'create'])
            ->middleware('can:create users')
            ->name('users.create');
            
        Route::get('users/{user}', [UserController::class, 'show'])
            ->middleware('can:see other profiles')
            ->name('users.show');

        Route::get('users', [UserController::class, 'index'])
            ->middleware('can:see other profiles')
            ->name('users.index');

        Route::post('settings/update', [ApplicationSettingsController::class, 'update'])
            ->middleware('can:change settings')
            ->name('settings.update');

        Route::get('settings', [ApplicationSettingsController::class, 'index'])
            ->middleware('can:change settings')
            ->name('settings.index');
    });
