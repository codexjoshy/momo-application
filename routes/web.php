<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\Auth\LoginController::class,'showLoginForm']);
Route::get('/home', function () {
    $smsBalance = [];
    return view('home', compact('smsBalance'));
});


Route::get('/test', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Auth::routes();
// Route::get('/home', function ()
// {
//     dd('hekko');
// })->name('admin.home');
Route::middleware(['auth','isAdmin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/schedules', [App\Http\Controllers\MomoScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/create', [App\Http\Controllers\MomoScheduleController::class, 'create'])->name('schedule.create');
    Route::delete('/schedule/{momoSchedule}', [App\Http\Controllers\MomoScheduleController::class, 'destroy'])->name('schedule.delete');
    Route::post('/schedule/preview', [App\Http\Controllers\AjaxController::class, 'customerListPreview'])->name('process.customerList');
    Route::post('/schedule/upload', [App\Http\Controllers\MomoScheduleController::class, 'store'])->name('schedule.upload');

    Route::get('/feature/', [App\Http\Controllers\AppFeatureController::class, 'index'])->name('feature.schedule.index');
    Route::get('/feature/create', [App\Http\Controllers\AppFeatureController::class, 'create'])->name('feature.schedule.create');
    Route::post('/feature/store', [App\Http\Controllers\AppFeatureController::class, 'store'])->name('feature.schedule.upload');


});

