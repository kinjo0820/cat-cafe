<?php

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::view('/','index');



//お問合せフォーム
Route::get('/contact',[ContactController::class,'index'])->name('contact');
Route::post('/contact',[ContactController::class,'sendMail']);
Route::get('/contact/complete',[ContactController::class,'complete'])->name('contact.complete');

Route::prefix('/admin')
->name('admin.')
->middleware('auth')
->group(function(){
    //ブログ
    Route::resource('/blogs',AdminBlogController::class)->except('show');
    // Route::get('/blogs',[AdminBlogController::class,'index'])->name('blogs.index');
    // Route::get('/blogs/create',[AdminBlogController::class,'create']);
    // Route::post('/blogs',[AdminBlogController::class,'store'])->name('blogs.store');
    // Route::get('/blogs/{blog}',[AdminBlogController::class,'edit'])->name('blogs.edit');
    // Route::put('/blogs/{blog}',[AdminBlogController::class,'update'])->name('blogs.update');
    // Route::delete('/blogs/{blog}',[AdminBlogController::class,'destroy'])->name('blogs.destroy');


    //ユーザー管理
    Route::get('/users/create',[UserController::class,'create'])->name('users.create');
    Route::post('/users',[UserController::class,'store'])->name('users.store');

    //ログアウト
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


});

//認証
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login']);
