<?php

use App\Models\User;
use App\Models\category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ChatifyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminCateController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminIdCardController;
use App\Http\Controllers\AdminUpdateSingleUser;
use App\Http\Controllers\RoleRequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\ReportController;

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

Route::get('/', function () {
    return view('home', [
        'active' => 'home'
    ]);
});

Route::get('/home', function () {
    return view('home', [
        'active' => 'home'
    ]);
});

Route::get('/game', [CategoryController::class, 'showcategory']);
// Route::get('/users', [CategoryController::class, 'showuserbycategory']);

// Route::get('/users/{id}', [CategoryController::class, 'showuserbycategory']);
// Route::get('/users/{category:slug}', function (category $category) {
//     return view('test', [
//         'category' =>  $category->user
//     ]);
// });

Route::get('/categories/{category:slug}', [CategoryController::class,'showuserbycategory']);
Route::get('/user/{user:username}', [UserController::class,'showsingleuser'])->name('user')->middleware('exceptAdmin');

// login
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');

//register
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::post('/logout',[LogoutController::class,'logout'])->middleware('auth');

Route::get('/addtocart/{user:username}', [OrderController::class,'index'])->middleware('exceptAdmin')->middleware('auth');
Route::resource('/addtocart', OrderController::class)->middleware('exceptAdmin')->middleware('auth');
// Route::post('/addtocart', [OrderController::class,'store'])->middleware('exceptAdmin')->middleware('auth');
Route::post('/save-schedule-and-cart', [OrderController::class,'saveScheduleAndCart'])->middleware('exceptAdmin')->middleware('auth');



Route::get('/cart/{user:username}', [OrderController::class,'GetCartByUserId'])->middleware('exceptAdmin');
Route::put('/cart/{id}', [OrderController::class, 'update'])->middleware('exceptAdmin');
Route::post('/orderpage', [OrderController::class,'showOrderPage'])->name('order.page')->middleware('exceptAdmin');
Route::get('/test', [OrderController::class,'tes'])->middleware('isPlayer')->middleware('isCoach');
Route::get('/order-request', [OrderController::class, 'orderRequest'])->name('order.request')->middleware('exceptAdmin')->middleware('auth');

Route::post('/order-request/{id}/accept', [OrderController::class, 'acceptOrder'])->name('order.process');
Route::post('/order-request/{id}/reject', [OrderController::class, 'rejectOrder'])->name('order.reject');
Route::post('/processorder', 'OrderController@processOrder')->name('process.order');
Route::post('/reduce-points', 'UserController@reducePoints')->name('reduce.points');

Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order')->middleware('exceptAdmin');
Route::get('/dashboard', function(){
    return view('admin.index',
    [
        "title" => "Dashboard",
        'active' => 'dashboard'
    ]);
})->middleware('isAdmin');
Route::post('/ordervalidation', [OrderController::class,'processOrder'])->name('order.validation')->middleware('isPlayer')->middleware('isCoach');

Route::resource('/role/request', RoleRequestController::class)->middleware('exceptAdmin');
Route::resource('/dashboard/categories', AdminCategoryController::class)->middleware('isAdmin');
Route::resource('/dashboard/role', AdminRoleController::class)->middleware('isAdmin');
Route::resource('/dashboard/idcard', AdminIdCardController::class)->middleware('isAdmin');

// Route::get('/chatify', [ChatifyController::class,'showChatify']);
Route::get('/top_up', [TopUpController::class, 'index'])->middleware('exceptAdmin')->name('top_up')->middleware('auth');
Route::get('/top_up_fail', [TopUpController::class, 'indexFail'])->middleware('exceptAdmin')->name('top_up_fail')->middleware('auth');
Route::post('/top_up', [TopUpController::class, 'store'])->middleware('exceptAdmin')->name('store_top_up')->middleware('auth');
Route::post('/top_up', [TopUpController::class, 'store'])->middleware('exceptAdmin')->name('store_top_up')->middleware('auth');
Route::get('/top_up/sukses', [TopUpController::class, 'success'])->name('topup.sukses')->middleware('exceptAdmin')->middleware('auth');
Route::post('/idcardnumber/request', [TopUpController::class, 'idnumcardreq'])->name('idnumreq')->middleware('exceptAdmin')->middleware('auth');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index')->middleware('auth')->middleware('exceptAdmin');
Route::get('/usertransaction', [TransactionController::class, 'foruser'])->name('transactions.foruser')->middleware('auth')->middleware('exceptAdmin');
Route::put('/transactions/{id}/mark-as-done', [TransactionController::class, 'markAsDone'])->name('transactions.markAsDone');
Route::get('/history', [TransactionController::class, 'history'])->name('transactions.history')->middleware('auth')->middleware('exceptAdmin');
Route::get('/getcart', [OrderController::class, 'getCartData']);
Route::resource('/rating', RatingController::class);
Route::get('/rating', [RatingController::class, 'index'])->middleware('auth')->middleware('exceptAdmin');


Route::post('/schedule', [ScheduleController::class,'store']);
Route::post('/save-schedule', [ScheduleController::class,'saveSchedules']);



Route::get('/userschedule', [ScheduleController::class,'userSchedules'])->name('user.schedules')->middleware('auth')->middleware('exceptAdmin');
Route::get('/sellerschedule', [ScheduleController::class,'sellerSchedules'])->name('user.schedules')->middleware('auth')->middleware('exceptAdmin');

Route::resource('/withdrawal', WithdrawalController::class)->middleware('auth')->middleware('exceptAdmin');



//gaperlu
// Route::get('/updatesingleuser', [UserController::class, 'showUpdateForm'])->name('profile.update');
// Route::put('/updatesingleuser', [UserController::class, 'updateSingleUser']);


Route::put('/updateavailabletimes', [ScheduleController::class, 'updateSchedule']);
Route::get('/editavailabletimes', [ScheduleController::class, 'showEditSchedule'])->name('schedule.viewedit')->middleware('auth')->middleware('exceptAdmin');



Route::get('/admin/pending-updates', [AdminUpdateSingleUser::class, 'showPendingUpdates'])->name('admin.pending-updates')->middleware('isAdmin');
Route::put('/admin/approve-update/{id}', [AdminUpdateSingleUser::class, 'approveUpdate'])->name('admin.approve-update')->middleware('isAdmin');

Route::post('/update-time-left/{itemId}', [OrderController::class, 'updateTimeLeft'])->middleware('auth');

Route::get('/report/{user:username}', [ReportController::class,'index'])->middleware('exceptAdmin')->middleware('auth');

Route::post('/createreport', [ReportController::class, 'store']);

Route::get('/report-users', [AdminReportController::class, 'index'])->middleware('isAdmin');
Route::get('/report-detail/{user:username}', [AdminReportController::class, 'index_detail'])->middleware('isAdmin');


Route::post('/report/{id}/ban', [ReportController::class, 'ban'])->name('report.ban');
Route::post('/report/{id}/unban', [ReportController::class, 'unban'])->name('report.unban');

Route::get('/rating-detail/{user:username}', [RatingController::class, 'show'])->name('rating.detail');

Route::get('/getUnreadMessagesCount', [ChatifyController::class, 'updateUnreadMessagesCount'])->middleware('auth');
Route::post('/accept/report/{report}', [AdminReportController::class, 'acceptReport'])->name('accept.report');
Route::post('/reject/report/{report}', [AdminReportController::class, 'rejectReport'])->name('reject.report');
Route::get('/categories/{category:slug}/filterByRole', [CategoryController::class, 'filterByRole'])->name('filterByRole');
