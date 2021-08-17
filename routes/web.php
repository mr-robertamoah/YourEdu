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

// use Carbon\Carbon;
// use Illuminate\Http\Request;

// Route::get('/test', function (Request $request) {

//     ray(new Carbon("2021-06-30T07:23:10.039Z"))->green();
// });
Route::get('/{any}', 'Api\YourEduController@index')->where('any', '.*');

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
