<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\QuestionController;

Auth::routes();
Route::get('/', [TopController::class, 'top'])->name('top');

Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/new-question', [QuestionController::class, 'new'])->name('questions.new');
    Route::post('/new-question', [QuestionController::class, 'create'])->name('questions.create');
    Route::get('/edit-question/{question}', [QuestionController::class, 'edit'])->name('questions.edit');
});
