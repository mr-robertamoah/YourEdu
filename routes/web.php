<?php

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

// use App\Events\TestEvent;

// Route::get('/broadcast', function ()
// {
//     broadcast(new TestEvent('hello')) ;
// });
Route::get('/test', function () {

    // ray(unserialize(DB::table('failed_jobs')->where('id', 8)->first()->payload))->green();
});
Route::get('/{any}', 'Api\YourEduController@index')->where('any', '.*');

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
