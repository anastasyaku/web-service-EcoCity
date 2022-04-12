<?php

use App\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define("RECYCLABLES_TITLES", [
    "CLOTH" => "одежды",
    "GLASS" => "стеклотары и алюминия",
    "PLASTIC" => "пластика",
    "PAPER" => "макулатуры",
    "SCRAP" => "металлолома",
    "TECH" => "бытовой техники",
    "BATTERIES" => "аккумуляторов",
    "BULBS" => "ртутных ламп",
]);

Route::view('/', 'index')->name("index");
Route::post("/feedback", function (Request $request) {
    $feedback = new Feedback([
        "name" => $request->input("name"),
        "email" => $request->input("email"),
        "text" => $request->input("text"),
    ]);
    $feedback->save();
    return view("feedback-thanks");
});

Route::match(['get', 'post'], 'login', 'LoginLogoutController@login');

Route::view('/dispatch', 'dispatch', ["recyclablesTitles" => RECYCLABLES_TITLES])
    ->middleware("auth")
    ->name("dispatch");

Route::get("/logout", 'LoginLogoutController@logout');
