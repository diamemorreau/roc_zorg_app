<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\QualificationFileController;
use App\Http\Controllers\competitionController;
use App\Http\Controllers\StudentFileController;

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('log', LogController::class);

    Route::patch('{log}', [App\Http\Controllers\ApproveController::class, 'approveLog'])->name('approveLog');
    
    Route::resource('qualification_file', QualificationFileController::class);

    // Route::get('/qualification_file', [App\Http\Controllers\qualificationfileController::class, 'index']);
    // Route::post('/qualification_file', [App\Http\Controllers\qualificationfileController::class, 'store']);
    // Route::patch('/qualification_file/{qualification_file}', [App\Http\Controllers\qualificationfileController::class, 'update']);
    // Route::delete('/qualification_file/{qualification_file}', [App\Http\Controllers\qualificationfileController::class, 'destroy']);

    Route::resource('competition', competitionController::class);
    
    Route::resource('student_file', StudentFileController::class);
    
    Route::get('/profiles', [App\Http\Controllers\ProfilesController::class, 'index']);
    Route::get('/profiles/{user}', [App\Http\Controllers\ProfilesController::class, 'show'])->name('profile');
    Route::patch('/profiles/{user}', [App\Http\Controllers\ProfilesController::class, 'update']);
    
    Route::resource('/profiles/intern', InternshipController::class);

});
