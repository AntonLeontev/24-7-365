<?php

use App\Http\Controllers\SocialsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Models\Tariff;
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

Route::get('/', function () {
    $tariffs = Tariff::all();
    return view('welcome', compact('tariffs'));
})->name('home');

Auth::routes(['verify' => true]);

Route::middleware('guest')->group(function () {
    Route::get('/auth/{driver}/redirect', [SocialsController::class, 'redirect'])
        ->name('auth.social');
    Route::get('/auth/{driver}/callback', [SocialsController::class, 'callback']);
});

Route::prefix('personal')
    ->middleware('auth')->group(function () {
        Route::get('profile', [UserProfileController::class, 'profile'])
            ->middleware('can:see own profile')
            ->name('users.profile');
        Route::post('save_profile/{user}', [UserProfileController::class, 'storeProfile'])
        ->name('save_profile');
        Route::post('save_profile_organization/{user}', [UserProfileController::class, 'storeProfileOrganization'])
        ->name('save_profile_organization');
        Route::post('save_profile_requisites/{user}', [UserProfileController::class, 'storeProfileRequisites'])
        ->name('save_profile_requisites');
        Route::post('save_profile_password/{user}', [UserProfileController::class, 'passwordReset'])
        ->name('save_profile_password');
       
    });

Route::prefix('admin')
    ->middleware('auth')->group(function () {
        Route::post('users/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('can:assign roles')
            ->name('users.update-role');

        Route::post('users/create', [UserController::class, 'create'])
            ->middleware('can:create users')
            ->name('users.create');
            
        Route::get('users/{user}', [UserController::class, 'show'])
            ->middleware('can:see other profiles')
            ->name('users.show');

        Route::get('users', [UserController::class, 'index'])
            ->middleware('can:see other profiles')
            ->name('users.index');
    });
