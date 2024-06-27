<?php

use App\Events\MessageSent;
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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('hit', function () {
//     // broadcast(new MessageSent('Hey From web'))->toOthers();
//     // event(new MessageSent('sdsssfs'));
//     dd(broadcast(new MessageSent('Hey From web'))->toOthers());
// });

Route::get('/{any?}', function () {
    return view('layouts.app');
})->where('any', '.*');
