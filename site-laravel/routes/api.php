<?php

use Illuminate\Support\Facades\Route;

Route::resource('/marks', 'MarkController');
Route::resource('/trash', 'TrashMarkController');
Route::resource('/events', 'EventMarkController');
Route::resource('/banned-users', 'BannedUserController');

