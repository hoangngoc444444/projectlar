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

// Khong duoc xoa
Auth::routes();

// Route::get('/', function () {
//     return view('frontend.default.index');
// })->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Backend'], function() {
//    User
    Route::get('/users', 'UserController@index')->name('user.index');
    Route::get('/users/create', 'UserController@create')->name('user.create');
    Route::post('/users', 'UserController@store')->name('user.store');
    Route::get('/users/{id}', 'UserController@show')->where('id','[0-9]+')->name('user.show');
    Route::put('/users/{id}', 'UserController@update')->name('user.update');
    Route::delete('/users/{id}', 'UserController@delete')->name('user.delete');
    
//    Category
    Route::get('/categories', 'CategoryController@index')->name('category.index');
    Route::get('/categories/create', 'CategoryController@create')->name('category.create');
    Route::post('/categories', 'CategoryController@store')->name('category.store');
    Route::get('/categories/{id}', 'CategoryController@show')->where('id','[0-9]+')->name('category.show');
    Route::put('/categories/{id}', 'CategoryController@update')->name('category.update');
    Route::delete('/categories/{id}', 'CategoryController@delete')->name('category.delete');

//    Product
    Route::get('/products', 'ProductController@index')->name('product.index');
    Route::get('/products/create', 'ProductController@create')->name('product.create');
    Route::post('/products', 'ProductController@store')->name('product.store');
    Route::get('/products/{id}', 'ProductController@show')->where('id','[0-9]+')->name('product.show');
    Route::put('/products/{id}', 'ProductController@update')->name('product.update');
    Route::delete('/products/{id}', 'ProductController@delete')->name('product.delete');
    Route::patch('/products/{id}', 'ProductController@setFeature')->name('product.setFeature');
    
});

// Route::get('/home', 'HomeController@index');

Route::group(['as' => 'frontend.', 'namespace' => 'Frontend'], function() {
    //    User
        Route::get('/', 'Homecontroller@index')->name('home.index');
        Route::get('/product/{slug}/{id}.html', 'Homecontroller@show')->name('home.show');
        Route::post('/products/{id}', 'HomeController@comment')->name('product.comment');
       
       
    });


//Route::get('/users/{id}/abc/{name}', 'UserController@show')->where([
//    'id' => '[0-9]+',
//    'name' => '[a-zA-Z]+'
//]);