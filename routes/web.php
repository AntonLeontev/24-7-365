<?php

use App\Http\Controllers\ApplicationSettingsController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\IncomeCalculatorController;
use App\Http\Controllers\NewContractController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SmscodeController;
use App\Http\Controllers\SocialsController;
use App\Http\Controllers\SuggestionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\CanSeeContract;
use App\Http\Middleware\CheckBlockedUser;
use Illuminate\Notifications\Messages\MailMessage;
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
Route::view('/', 'welcome')->withoutMiddleware(CheckBlockedUser::class)
    ->middleware('guest')
    ->name('home');

/**
 * DELETE
 */
//TODO delete
Route::get('24-pay-in/{contract_id}', function ($id) {
    Artisan::call("24:pay-in", ['contract' => $id, '--take' => 1]);

    return back();
})->name('pay-in');

Route::get('24-pay-out/{contract_id}', function ($id) {
    Artisan::call("24:pay-out", ['contract' => $id, '--take' => 1]);

    return back();
})->name('pay-out');

Route::get('24-period/{contract_id}', function ($id) {
    Artisan::call("24:period", ['contract' => $id]);

    return back();
})->name('period');

Route::get('24-period-2/{contract_id}', function (int $id) {
    Artisan::call("24:period", ['contract' => $id, '--number' => 2]);

    return back();
})->name('period2');

Route::get('24-period-5/{contract_id}', function (int $id) {
    Artisan::call("24:period", ['contract' => $id, '--number' => 5]);

    return back();
})->name('period5');

Route::get('24-rescon/{contract_id}', function ($id) {
    Artisan::call("24:rescon", ['contract' => $id]);
    Artisan::call("24:pay-in", ['contract' => $id, '--take' => 1]);

    return back();
})->name('reset-contract');

Route::get('test', function () {
    // return view('test');
    return (new MailMessage())
                    ->subject("Счет на оплату")
                    ->greeting('Приветули!')
                    ->line("Сгенерирован счет по договору 36.")
                    ->line("Счет во вложении, также его можно скачать:")
                    ->action('Скачать', route('invoice.pdf', 12))
                    ->salutation('Поки-чмоки!')
                    ->render();
});

/*------------------------------------------*/

Route::post('suggestions/company', [SuggestionsController::class,'company'])
    ->name('suggestions.company');
Route::post('suggestions/bank', [SuggestionsController::class,'bank'])
    ->name('suggestions.bank');

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
    Route::post('profile/save', [UserProfileController::class, 'storeProfile'])
        ->middleware('phone')
        ->name('users.profile.save');
    Route::post('profile/validate', [UserProfileController::class, 'checkProfileInput'])
        ->middleware('phone')
        ->name('users.profile.validate');
    Route::post('user/update_phone', [UserController::class, 'updatePhone'])
        ->middleware('phone')
        ->name('users.updatePhone');
    Route::post('user/validate_phone', [UserController::class, 'validatePhone'])
        ->middleware('phone')
        ->name('users.validatePhone');
    
    Route::get('contracts', [ContractController::class, 'index'])
        ->middleware('can:see own profile')
        ->name('users.contracts');
    
    Route::get('contracts/{contract}/show', [ContractController::class, 'show'])
        ->middleware(CanSeeContract::class)
        ->name('users.contract_show');

    Route::get('contracts/pdf', [PdfController::class, 'contract'])
        ->middleware('can:see own profile')
        ->name('users.contract.pdf');
    
    Route::get('contracts/create/text', [ContractController::class, 'agree'])
        ->middleware('can:see own profile')
        ->name('contracts.agree');
    Route::any('contracts/create', [ContractController::class, 'create'])
        ->middleware('can:see own profile')
        ->name('users.add_contract');


    Route::post('contracts/store', [ContractController::class, 'store'])
        ->middleware('can:see own profile')
        ->name('contracts.store');

    Route::get('contracts/{contract}/cancel', [ContractController::class, 'cancel'])
        ->middleware('can:see own profile')
        ->name('contracts.cancel');
    
    Route::get('contracts/{contract}/cancel_prolongation', [ContractController::class, 'cancelProlongation'])
        ->middleware('can:see own profile')
        ->name('contracts.cancel.prolongation');

    Route::get('contracts/{contract}/edit', [ContractController::class, 'edit'])
        ->middleware('can:see own profile')
        ->name('contracts.edit');

    Route::post('contracts/{contract}/update', [ContractController::class, 'update'])
        ->middleware('can:see own profile')
        ->name('contracts.update');

    Route::get('contracts/{contract}/cancel_change', [ContractController::class, 'cancelChange'])
        ->middleware('can:see own profile')
        ->name('contracts.cancel_change');

    Route::get('calculator', [IncomeCalculatorController::class, 'show'])
        ->name('income_calculator');

    Route::post('organization/save', [NewContractController::class, 'saveRequesites'])
        ->name('organization.save');
    
        
    Route::get('payments', [PaymentController::class, 'indexForUser'])
        ->middleware('can:see own profile')
        ->name('payments.for_user');

    Route::get('payments/{payment}/send-invoice', [PaymentController::class, 'sendInvoice'])
        ->middleware('can:see own profile')
        ->name('payments.send-invoice');

    Route::get('invoices/{payment}/pdf', [PdfController::class, 'invoice'])->name('invoice.pdf');
    
    Route::post('smscode/check/{type}', [SmscodeController::class,'checkCode'])
        ->where('type', 'phone_confirmation|contract_creating')
        ->middleware(['can:see own profile', 'throttle:20'])
        ->name('smscode.check');
    
    Route::post('smscode/create/{type}', [SmscodeController::class,'createCode'])
        ->where('type', 'phone_confirmation|contract_creating')
        ->middleware(['can:see own profile', 'throttle:2', 'phone'])
        ->name('smscode.create');

    Route::post('notifications/unread', [NotificationsController::class, 'unread'])
        ->name('notifications.unread');

    Route::get('notifications/read-all', [NotificationsController::class, 'readAll'])
        ->name('notifications.read_all');
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
