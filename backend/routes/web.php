<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\QuestionController;

Route::get('/', [TopController::class, 'top']);

Route::get('/questions', [QuestionController::class, 'index']);