<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users/{id?}',[UserApiController::class,'showUser']);

Route::post('/add-user',[UserApiController::class,'addUser']);

Route::post('/add-multiple-user',[UserApiController::class,'addMultipleUser']);

Route::put('/update-user-details/{id}',[UserApiController::class,'updateUserDetails']);

Route::patch('/update-singel-record/{id}',[UserApiController::class,'updateSingleRecord']);

Route::delete('/delete-singel-user/{id}',[UserApiController::class,'deleteSingleUser']);

Route::delete('/delete-singel-user-with-json',[UserApiController::class,'deleteUserJson']);

Route::delete('/delete-multiple-user/{ids}',[UserApiController::class,'deleteMultipleUser']);

Route::delete('/delete-multiple-user-with-json',[UserApiController::class,'deleteMultipleUserJson']);

//passport use registron login and logout
Route::post('/register-user-using-passport',[UserApiController::class,'registerUserUsingPassport']);

Route::post('/login-user-using-passport',[UserApiController::class,'loginUserUsingPassport']);

Route::post('/logout-user-using-passport',[UserApiController::class,'logoutUserUsingPassport']);