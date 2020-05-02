<?php

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

// Welcome
// Route::get('/', function () {
//     return view('welcome');
// });

// Auth
// Auth::routes();
Auth::routes(['verify' => true]); //for email verification==>['verify' => true]
// message for verification
Route::get('showVerificationMsg', function () { //register 후에 email verification을 위하여 삽입.
    //만약 이메일 인증이 되었으면 아래 메세지가 나오고(그럴리는 없지만...) 그렇지 않으면
    //resources/views/auth/verify.blade.php에 있는 메시가 나온다.[=>middleware('verified')]
    return "your email already verified."; //현재는 register 후에 곧바로 이 url로 오도록 RegisterController에 코딩
})->middleware('verified');                //되어 있다.
// Home
Route::get('/', function () {
    return view('home');
});
Route::get('/home', 'HomeController@index')->name('home');
// Admin
Route::get('/admin', 'AdminController@index')->name('admin.home')->middleware('can:isAdmin');
// User
Route::get('/user-index', 'UserController@index')->name('user.index')->middleware('can:isAdmin');
Route::get('/get-users', 'UserController@getUsers')->middleware('can:isAdmin');
Route::post('/edit-user', 'UserController@editUser')->middleware('can:isAdmin');
// Product
Route::get('/select-product', 'ProductController@selectProduct')->name('product.select')->middleware('can:isAdminOrEditor');
Route::post('/product', 'ProductController@index')->name('product.index')->middleware('can:isAdminOrEditor');
Route::get('/showCreateFeederForm', 'ProductController@showCreateFeederForm')->name('product.showCreateFeederForm');
Route::get('/feeder-jsutNames', 'ProductController@jsutNames')->middleware('can:isAdmin');
Route::post('/check-feeder', 'ProductController@checkFeeder')->name('check.feeder')->middleware('can:isAdmin');
Route::post('/create-feeder', 'ProductController@createFeeder')->name('create.feeder')->middleware('can:isAdmin');
Route::get('/get-feeders', 'ProductController@getFeeders')->middleware('can:isAdmin');
Route::post('/delete-feeder', 'ProductController@deleteFeeder')->name('delete.feeder')->middleware('can:isAdmin');
// Part
// Route::get('/refill/{feeder}', 'PartController@showRefill')->name('part.refill');//not use
Route::post('/refill', 'PartController@updateRefill')->name('refill.update')->middleware('auth','verified');
Route::get('/wholeReading','PartController@wholeReading')->name('part.wholeReading')->middleware('auth','verified');
Route::get('/showReadingForm','PartController@showReadingForm')->name('part.showReadingForm');
Route::get('/selectLogsForm','PartController@selectLogsForm')->name('part.selectLogsForm');
Route::post('/selectLogsForm','PartController@showLogs')->name('part.showLogs');
Route::post('/create-part', 'PartController@createPart')->name('create.part')->middleware('can:isAdmin');
Route::get('/create-part', 'PartController@createPartForm')->name('create.partForm')->middleware('can:isAdmin');
Route::get('/get-parts', 'PartController@getParts')->middleware('can:isAdmin');
Route::post('/delete-part', 'PartController@deletePart')->middleware('can:isAdmin');
// Order
Route::get('/make-order', 'OrderController@showForm')->name('form.order')->middleware('can:isEditor');
Route::post('/make-order', 'OrderController@makeOrder')->name('make.order')->middleware('can:isEditor');
Route::get('/ask-show-orders', 'OrderController@askShowOrders')->name('ask.show.orders');
Route::post('/order-index', 'OrderController@index')->name('order.index');
Route::get('/get-orders', 'OrderController@getOrders')->middleware('can:isEditor');
Route::get('/order-justNames', 'OrderController@orderJustNames')->middleware('can:isEditor');
Route::post('/delete-order', 'OrderController@deleteOrder')->middleware('can:isEditor');
Route::post('/edit-order', 'OrderController@editOrder')->middleware('can:isEditor');
// Create
Route::get('/showMachineForm', 'CreateController@showMachineForm')->name('create.showMachineForm')->middleware('can:isAdmin');
Route::get('/get-machines', 'CreateController@getMachines')->middleware('can:isAdmin');
Route::post('/create-machine', 'CreateController@createMachine')->middleware('can:isAdmin');
Route::post('/delete-machine', 'CreateController@deleteMachine')->middleware('can:isAdmin');
Route::get('/showDepartmentForm', 'CreateController@showDepartmentForm')->name('create.showDepartmentForm')->middleware('can:isAdmin');
Route::get('/get-departments', 'CreateController@getDepartments')->middleware('can:isAdmin');
Route::post('/create-department', 'CreateController@createdepartment')->middleware('can:isAdmin');
Route::post('/delete-department', 'CreateController@deleteDepartment')->middleware('can:isAdmin');
Route::get('/showShiftForm', 'CreateController@showShiftForm')->name('create.showShiftForm')->middleware('can:isAdmin');
Route::get('/get-shifts', 'CreateController@getShifts')->middleware('can:isAdmin');
Route::post('/create-shift', 'CreateController@createShift')->middleware('can:isAdmin');
Route::post('/delete-shift', 'CreateController@deleteShift')->middleware('can:isAdmin');
Route::get('/showProductnameForm', 'CreateController@showProductnameForm')->name('create.showProductnameForm')->middleware('can:isAdmin');
Route::get('/get-productnames', 'CreateController@getProductnames')->middleware('can:isAdmin');
Route::post('/create-productname', 'CreateController@createProductname')->middleware('can:isAdmin');
Route::post('/delete-productname', 'CreateController@deleteProductname')->middleware('can:isAdmin');
Route::get('/showProductForm', 'CreateController@showProductForm')->name('create.showProductForm')->middleware('can:isAdmin');
Route::get('/get-products', 'CreateController@getProducts')->middleware('can:isAdmin');
Route::post('/create-product', 'CreateController@createProduct')->middleware('can:isAdmin');
Route::post('/delete-product', 'CreateController@deleteProduct')->middleware('can:isAdmin');
