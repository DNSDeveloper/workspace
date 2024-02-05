<?php

use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/fetch-service', [TaskController::class, 'fetchService']);
Route::get('/task/status/{status}/{token}', [APIController::class, 'task']);