<?php

use App\Models\Quiz;
use App\Repositories\Eloquent\EloquentQuizRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;

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

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('show', 'find');
    Route::put('', 'updateInfo');
    Route::put('change-password', 'updatePassword');
    Route::delete('', 'delete');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('show', 'find');
    Route::put('', 'update');
    Route::delete('', 'delete');
});

Route::controller(QuizController::class)->prefix('quizzes')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('show', 'find');
    Route::put('', 'update');
    Route::delete('', 'delete');
});


Route::get('', function () {
    $d = [
        "title" => "quiz 1",
        "description" => "this is a new quiz for test",
        "start_date" => Carbon::parse(1650730877)->toDateString(),
//        "duration" => 90,
        "category_id" => 1,
        "is_active" => true,
        "end_date" => Carbon::parse(1650730877)->toDateString()
    ];
//dd(Carbon::parse(1650730877)->toDateString());
//    $x = new Quiz;
//    $x->title = 'aa';
//    $x->description = 'this is a new quiz for test';
//    $x->start_date = Carbon::parse(1650730877)->toDateString();
//    $x->end_date = Carbon::parse(1650730877)->toDateString();
//    $x->category_id = 1;
//    $x->is_active = true;
//    $x->save();
    $x =  (new EloquentQuizRepository())->create($d);
    dd($x);
});
