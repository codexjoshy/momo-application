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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});


Route::get('/test', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Auth::routes();

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

});

