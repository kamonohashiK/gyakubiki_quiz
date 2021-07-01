<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\QuestionController;

Route::get('/', [TopController::class, 'top'])->name('top');

Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/questions/new', [QuestionController::class, 'new'])->name('questions.new');
Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
