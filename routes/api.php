<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login', 
    array(
        'uses' =>  'UserController@login', 
        'as'   =>  'login'
    )
);
Route::get('/logout', 
    array(
        'uses' =>  'UserController@logout', 
        'as'   =>  'logout'
    )
);
Route::get('/refresh_token', 
    array(
        'uses' =>  'UserController@refreshToken', 
        'as'   =>  'refreshToken'
    )
);
//license page API
Route::get('/get_license', 
    array(
        'uses' =>  'UserController@getLicense', 
        'as'   =>  'getLicense'
    )
);
Route::post('/create_license', 
    array(
        'uses' =>  'UserController@createUser', 
        'as'   =>  'createUser'
    )
);
Route::post('/renew_license', 
    array(
        'uses' =>  'UserController@changeUser', 
        'as'   =>  'changeUser'
    )
);
Route::post('/delete_license', 
    array(
        'uses' =>  'UserController@deleteUser', 
        'as'   =>  'deleteUser'
    )
);
Route::get('/search_events', 
    array(
        'uses' =>  'UserController@searchEvents', 
        'as'   =>  'searchEvents'
    )
);
