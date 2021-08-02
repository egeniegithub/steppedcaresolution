<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;

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
    if(auth()->user()){
        return redirect('dashboard');
    }else{
        return redirect('login');
    }

});

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('/activate_account/{id}', [App\Http\Controllers\GuestUserController::class, 'activate_user_account'])->name('activate_user_account');
Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'], function(){

    Route::group(['prefix' => 'users',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\UserController::class,'index'])->name('dashboard.users');
        Route::get('/create', [App\Http\Controllers\UserController::class,'create'])->name('dashboard.user.create');
        Route::post('/store', [App\Http\Controllers\UserController::class,'store'])->name('dashboard.user.store');
        Route::get('/edit', [App\Http\Controllers\UserController::class,'edit'])->name('dashboard.user.edit');
        Route::post('/update', [App\Http\Controllers\UserController::class,'update'])->name('dashboard.user.update');
        Route::get('/view', [App\Http\Controllers\UserController::class,'show'])->name('dashboard.user.view');
        Route::get('/user/{id?}', [App\Http\Controllers\UserController::class,'delete'])->name('dashboard.user.delete');
    });

    Route::group(['prefix' => 'forms', 'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\FormController::class,'index'])->name('dashboard.forms');
        Route::post('/store', [App\Http\Controllers\FormController::class,'store'])->name('dashboard.form.store');
        Route::post('/update', [App\Http\Controllers\FormController::class,'update'])->name('dashboard.form.update');
        Route::get('/form/{id?}', [App\Http\Controllers\FormController::class,'delete'])->name('dashboard.form.delete');
    });

    Route::group(['prefix' => 'streams', 'middleware' => 'auth'], function(){
        Route::get('/index/{form_id}', [App\Http\Controllers\StreamController::class,'index'])->name('dashboard.streams');
        Route::get('/create/{form_id}', [App\Http\Controllers\StreamController::class,'create'])->name('dashboard.stream.create');
        Route::post('/store', [App\Http\Controllers\StreamController::class,'store'])->name('dashboard.stream.store');
        Route::post('/update', [App\Http\Controllers\StreamController::class,'update'])->name('dashboard.stream.update');
        Route::get('/stream/{id?}', [App\Http\Controllers\StreamController::class,'delete'])->name('dashboard.stream.delete');
    });

    Route::group(['prefix' => 'project',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\ProjectController::class,'index'])->name('dashboard.projects');
        Route::get('/create', [App\Http\Controllers\ProjectController::class,'create'])->name('dashboard.project.create');
        Route::post('/store', [App\Http\Controllers\ProjectController::class,'store'])->name('dashboard.project.store');
        Route::get('/edit', [App\Http\Controllers\ProjectController::class,'edit'])->name('dashboard.project.edit');
        Route::post('/update', [App\Http\Controllers\ProjectController::class,'update'])->name('dashboard.project.update');
        Route::get('/view', [App\Http\Controllers\ProjectController::class,'edit'])->name('dashboard.project.view');
        Route::delete('/project/{id?}', [App\Http\Controllers\ProjectController::class,'delete'])->name('dashboard.project.delete');
    });


    Route::group(['prefix' => 'periods',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\PeriodController::class,'index'])->name('dashboard.periods');
        Route::get('/create', [App\Http\Controllers\PeriodController::class,'create'])->name('dashboard.period.create');
        Route::post('/store', [App\Http\Controllers\PeriodController::class,'store'])->name('dashboard.period.store');
        Route::get('/edit/{id}', [App\Http\Controllers\PeriodController::class,'edit'])->name('dashboard.period.edit');
        Route::post('/update{id}', [App\Http\Controllers\PeriodController::class,'update'])->name('dashboard.period.update');
        Route::get('/user/{id?}', [App\Http\Controllers\PeriodController::class,'delete'])->name('dashboard.period.delete');
    });

    Route::group(['prefix' => 'reports',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\ReportController::class,'index'])->name('dashboard.reports');
    });

    Route::group(['prefix' => 'permissions',  'middleware' => 'auth'], function(){
        Route::get('/create', [App\Http\Controllers\PermissionsController::class,'create'])->name('dashboard.permissions');
    });

});

Route::get('/get-forms/{id}', [\App\Http\Controllers\PermissionsController::class, 'getForms']);
Route::get('/get-streams/{id}', [\App\Http\Controllers\PermissionsController::class, 'getStreams']);
