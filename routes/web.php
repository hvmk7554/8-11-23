<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
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

Route::controller(UserRoleController ::class)->group(function(){
    Route::get('/roles','index');
    Route::post('/roles','create');
    Route::post('/updateRole','update');
    Route::post('/switchRole','switchRole');
    Route::post('/deleteRole','destroy');
});

Route::controller(UserController ::class)->group(function(){
    Route::get('/users','index');
    Route::post('/users','create');
    Route::post('/updateUSRole','updateUSRole');
    Route::post('/updateUSstatus','updateUSstatus');
    Route::post('/updateUSname','updateUSname');
    Route::post('/updateUSmail','updateUSmail');
    Route::post('/deleteUser','destroy');
});