<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', "HomeController@index");
Route::get('/download', "HomeController@download");

Route::get("/upload","UploadController@index");
Route::get("/upload/s3","UploadController@uploadToS3");
Route::post("/upload","UploadController@upload")->name("upload");

Route::get("/symbols","SymbolController@index");
Route::get("/prices","SymbolController@stockPrice");

Route::get('test', function () {
    event(new App\Events\StockAnalyzed('Someone'));
    return "Event has been sent!";
});
