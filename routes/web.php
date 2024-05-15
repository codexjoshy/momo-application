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

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::get('/home', function () {
    $smsBalance = [];
    return view('home', compact('smsBalance'));
});
Route::get('/bal2', function () {
    // Initialize a cURL session
    $ch = curl_init();

    // Set the URL you want to curl
    //curl_setopt($ch, CURLOPT_URL, "https://www.example.com");
    // curl_setopt($ch, CURLOPT_URL, "http://64.226.97.232:27171/");
    curl_setopt($ch, CURLOPT_URL, "http://64.226.97.232:27171/jmg/web.html?jParams=eyJzaWQiOiJCQUwiLCJjb25maWciOiJhcGkiLCJhayI6IjdkZDVkY2NmODAxMmM2NDEyYjA3OWNiMzI2NjBiOTgxIiwiYXBpZCI6IkpvbGx5VGV0cmEiLCJyYyI6IjEiLCJ0eXBlIjoiNyJ9");
    // Set options to return the transfer as a string and follow any redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Execute the cURL session and store the result in a variable
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        // Output the response
        echo $response;
    }

    // Close the cURL session
    curl_close($ch);
});
Route::get('/bal', function () {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://64.226.97.232:27171/jmg/web.html?jParams=eyJzaWQiOiJCQUwiLCJjb25maWciOiJhcGkiLCJhayI6IjdkZDVkY2NmODAxMmM2NDEyYjA3OWNiMzI2NjBiOTgxIiwiYXBpZCI6IkpvbGx5VGV0cmEiLCJyYyI6IjEiLCJ0eXBlIjoiNyJ9',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_ENCODING => '',
        CURLOPT_CUSTOMREQUEST => 'GET',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    ));

    $response = curl_exec($curl);
    // Check if any error occurred
    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }
    curl_close($curl);
    echo $response;
});


Route::get('/phpinfo', function () {
    phpinfo();
});


Route::get('/test', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Auth::routes();
// Route::get('/home', function ()
// {
//     dd('hekko');
// })->name('admin.home');
Route::middleware(['auth', 'isAdmin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('/schedules', [App\Http\Controllers\MomoScheduleController::class, 'index'])->name('schedule.index');
        Route::get('/schedule/create', [App\Http\Controllers\MomoScheduleController::class, 'create'])->name('schedule.create');
        Route::delete('/schedule/{momoSchedule}', [App\Http\Controllers\MomoScheduleController::class, 'destroy'])->name('schedule.delete');
        Route::post('/schedule/preview', [App\Http\Controllers\AjaxController::class, 'customerListPreview'])->name('process.customerList');
        Route::post('/schedule/upload', [App\Http\Controllers\MomoScheduleController::class, 'store'])->name('schedule.upload');


        Route::get('/feature/', [App\Http\Controllers\AppFeatureController::class, 'index'])->name('feature.schedule.index');
        Route::get('/feature/create', [App\Http\Controllers\AppFeatureController::class, 'create'])->name('feature.schedule.create');
        Route::post('/feature/store', [App\Http\Controllers\AppFeatureController::class, 'store'])->name('feature.schedule.upload');
        Route::get('/feature/{feature}', [App\Http\Controllers\AppFeatureController::class, 'view'])->name('feature.schedule.view');
    });
