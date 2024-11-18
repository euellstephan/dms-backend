<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ServiceController;

use App\Http\Controllers\AlertController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('users', [AuthController::class, 'users']);
    Route::get('userProfile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);


  
    Route::get('residense', [ResidenceController::class, 'index']);
    Route::get('resident/{residence}', [ResidenceController::class, 'show']);
    Route::patch('resident/{residence}', [ResidenceController::class, 'update']);
    Route::delete('resident/{residence}', [ResidenceController::class, 'destroy']);


    Route::get('officials', [OfficialController::class, 'index']);
    Route::post('official', [OfficialController::class, 'store']);
    Route::get('official/{official}', [OfficialController::class, 'show']);
    Route::patch('official/{official}', [OfficialController::class, 'update']);
    Route::delete('official/{official}', [OfficialController::class, 'destroy']);
    
    Route::get('assistants', [AssistantController::class, 'index']);
    Route::post('assistant', [AssistantController::class, 'store']);
    Route::get('assistant/{assistant}', [AssistantController::class, 'show']);
    Route::patch('assistant/{assistant}', [AssistantController::class, 'update']);
    Route::delete('assistant/{assistant}', [AssistantController::class, 'destroy']);


    Route::get('resources' , [ResourceController::class, 'index']);
    Route::post('resource', [ResourceController::class, 'store']);
    Route::get('resource/{resource}', [ResourceController::class, 'show']);
    Route::patch('resource/{resource}', [ResourceController::class, 'update']);
    Route::delete('resource/{resource}', [ResourceController::class, 'destroy']);


    Route::get('services', [ServiceController::class, 'index']);
    Route::post('service', [ServiceController::class, 'store']);
    Route::get('service/{service}', [ServiceController::class, 'show']);
    Route::patch('service/{service}', [ServiceController::class, 'update']);
    Route::delete('service/{service}', [ServiceController::class, 'destroy']);

    Route::get('alerts', [AlertController::class, 'index']);
    Route::post('alert', [AlertController::class, 'store']);
    Route::get('alert/{alert}', [AlertController::class, 'show']);
    Route::patch('alert/{alert}', [AlertController::class, 'update']);
    Route::delete('alert/{alert}', [AlertController::class, 'destroy']);

});

  Route::post('resident', [ResidenceController::class, 'store']);