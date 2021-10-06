<?php

use App\Http\Controllers\HomeController;
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

    Route::get('get-streams/{id}',[HomeController::class,'getFormStreams'])->name('dashboard.get_streams');
    Route::get('get-fields/{id}',[HomeController::class,'getStreamFields'])->name('dashboard.get_fields');
    Route::post('save_graph',[HomeController::class,'saveGraph'])->name('dashboard.save_graph');
    Route::post('delete_graph',[HomeController::class,'deleteGraph'])->name('dashboard.delete_graph');

    Route::group(['prefix' => 'projects',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\ProjectController::class,'index'])->name('dashboard.projects');
        Route::post('/store', [App\Http\Controllers\ProjectController::class,'store'])->name('dashboard.project.store');
        Route::post('/update', [App\Http\Controllers\ProjectController::class,'update'])->name('dashboard.project.update');
        Route::get('/project/{id?}', [App\Http\Controllers\ProjectController::class,'delete'])->name('dashboard.project.delete');
    });

    Route::group(['prefix' => 'vendors',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\VendorController::class,'index'])->name('dashboard.vendors');
        Route::post('/store', [App\Http\Controllers\VendorController::class,'store'])->name('dashboard.vendor.store');
        Route::post('/update', [App\Http\Controllers\VendorController::class,'update'])->name('dashboard.vendor.update');
        Route::get('/vendor/{id?}', [App\Http\Controllers\VendorController::class,'delete'])->name('dashboard.vendor.delete');
    });

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
        Route::post('/add-update-form-summary', [App\Http\Controllers\FormController::class,'addUpdateFormSummary'])->name('dashboard.form.add_update_form_summary');
        Route::get('/form/{id?}', [App\Http\Controllers\FormController::class,'delete'])->name('dashboard.form.delete');
    });

    Route::group(['prefix' => 'streams', 'middleware' => 'auth'], function(){
        Route::get('/index/{form_id}', [App\Http\Controllers\StreamController::class,'index'])->name('dashboard.streams');
        Route::get('/create/{form_id}/{stream_id?}', [App\Http\Controllers\StreamController::class,'create'])->name('dashboard.stream.create');
        Route::post('/store', [App\Http\Controllers\StreamController::class,'store'])->name('dashboard.stream.store');
        Route::get('/edit/{form_id}/{stream_id?}', [App\Http\Controllers\StreamController::class,'edit'])->name('dashboard.stream.edit');
        Route::post('/update', [App\Http\Controllers\StreamController::class,'update'])->name('dashboard.stream.update');
        Route::get('/stream/{id?}', [App\Http\Controllers\StreamController::class,'destroy'])->name('dashboard.stream.delete');
        Route::post('/add-update-stream-summary', [App\Http\Controllers\StreamController::class,'addUpdateStreamSummary'])->name('dashboard.form.add_update_stream_summary');
        Route::post('/updatestatus', [App\Http\Controllers\StreamController::class,'UpdateStatus'])->name('dashboard.form.update_status');
        Route::get('/stream_update', [App\Http\Controllers\StreamController::class,'stream_update'])->name('dashboard.stream.stream_update');
        Route::get('/stream_update_two', [App\Http\Controllers\StreamController::class,'stream_update_two'])->name('dashboard.stream.stream_update_two');
        Route::get('/render/{id}', [App\Http\Controllers\StreamController::class,'render'])->name('dashboard.stream.render');
        Route::post('/stream-post', [App\Http\Controllers\StreamController::class,'streamPost'])->name('dashboard.stream.stream_post');
        Route::post('/delete-field', [App\Http\Controllers\StreamController::class,'streamField'])->name('dashboard.stream.delete_field');
        Route::post('/delete-grid-field', [App\Http\Controllers\StreamController::class,'deleteGridField'])->name('dashboard.stream.delete_grid_field');
    });

    Route::group(['prefix' => 'periods',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\PeriodController::class,'index'])->name('dashboard.periods');
        Route::get('/create', [App\Http\Controllers\PeriodController::class,'create'])->name('dashboard.period.create');
        Route::post('/store', [App\Http\Controllers\PeriodController::class,'store'])->name('dashboard.period.store');
        Route::get('/edit/{id}', [App\Http\Controllers\PeriodController::class,'edit'])->name('dashboard.period.edit');
        Route::post('/update{id}', [App\Http\Controllers\PeriodController::class,'update'])->name('dashboard.period.update');
        Route::get('/period/{id?}', [App\Http\Controllers\PeriodController::class,'delete'])->name('dashboard.period.delete');
        Route::post('/sync', [App\Http\Controllers\PeriodController::class,'syncData'])->name('dashboard.period.sync_data');
    });

    Route::group(['prefix' => 'reports',  'middleware' => 'auth'], function(){
        Route::get('/', [App\Http\Controllers\ReportController::class,'index'])->name('dashboard.reports');
        Route::get('/stream/{id}', [App\Http\Controllers\ReportController::class,'getStreamReport'])->name('dashboard.reports.stream');
        Route::post('/stream/download', [App\Http\Controllers\ReportController::class,'downReport'])->name('dashboard.reports.stream.download');
        Route::get('/stream/pdf-download/{form_id}', [App\Http\Controllers\ReportController::class,'pdfReport'])->name('dashboard.reports.stream.pdf_download');
        Route::get('/stream/doc-download/{form_id}', [App\Http\Controllers\ReportController::class,'generateWordDoc'])->name('dashboard.reports.stream.doc_download');
        Route::get('/stream/project-pdf-download/{period_id}/{project_id}', [App\Http\Controllers\ReportController::class,'pdfProjectReport'])->name('dashboard.reports.stream.project_pdf_download');
        Route::get('/stream/project-doc-download/{period_id}/{project_id}', [App\Http\Controllers\ReportController::class,'docProjectReport'])->name('dashboard.reports.stream.project_doc_download');
        Route::get('/stream/csv-download/{field_id}', [App\Http\Controllers\ReportController::class,'generateCsv'])->name('dashboard.reports.stream.csv_download');
    });

    Route::group(['prefix' => 'permissions',  'middleware' => 'auth'], function(){
        Route::get('/create/{id}', [App\Http\Controllers\PermissionsController::class,'create'])->name('dashboard.permissions');
        Route::post('/store', [App\Http\Controllers\PermissionsController::class,'store'])->name('dashboard.permission.store');
    });

});

Route::get('/get-users/{id}', [\App\Http\Controllers\PermissionsController::class, 'getUsers']);
Route::get('/get-forms/{project_id}/{period_id}', [\App\Http\Controllers\PermissionsController::class, 'getForms']);
Route::get('/get-streams/{id}', [\App\Http\Controllers\PermissionsController::class, 'getStreams']);
Route::get('/get-permissioned-users/{period_id}/{project_id}/{form_id}/{stream_id}', [\App\Http\Controllers\PermissionsController::class, 'getPermissionedUsers']);

Route::post('/stream-order', [\App\Http\Controllers\StreamController::class, 'streamOrder']);
Route::post('/form-order', [\App\Http\Controllers\FormController::class, 'formOrder']);
