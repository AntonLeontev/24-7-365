<?php

use App\Http\Controllers\UserController;
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
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::prefix('personal')
	->middleware('auth')->group(function () {
    Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
});

Route::prefix('admin')
	->middleware('auth')->group(function () {
		Route::get('users/{user}', [UserController::class, 'show'])
			->middleware('can:see other profiles')
			->name('users.show');
		Route::get('users', [UserController::class, 'index'])
			->middleware('can:see other profiles')
			->name('users.index');
	}
);
