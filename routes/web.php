<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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


Route::get('setup',function(){
    $credentials = [
        'email' => 'admin@admin',
        'password' => 'password'
    ];
    if(!Auth::attempt($credentials)){
        User::create([
            'name' => 'admin',
        ] + $credentials);
    }
    if(Auth::attempt($credentials)){
        $user = Auth::user();
        $adminToken = $user->createToken('admin-token', ['create:*', 'update:*', 'delete:*']);
        $updateToken = $user->createToken('update-token', ['create:*', 'update:*']);
        $basicToken = $user->createToken('basic-token', ['view:*']);

        return [
            'admin' => $adminToken->plainTextToken,
            'update' => $updateToken->plainTextToken,
            'basic' => $basicToken->plainTextToken,
        ];
    }
});