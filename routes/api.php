<?php






use App\Http\Controllers\{adminController, clientauthcontroller, ClientOrderController, Exportcontroller, paymentcontroller, postcontroller, wokrerprofilecontroller, workerauthcontroller, WorkerReviewController};
use App\Http\Controllers\AdminDashboard\AdminNotificationController;
use App\Http\Controllers\AdminDashboard\PostStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {

    Route::controller(adminController::class)->prefix('admin')->group(
        function () {
            Route::post('/login', 'login');
            Route::post('/register', 'register');
            Route::post('/logout', 'logout');
            Route::post('/refresh', 'refresh');
            Route::get('/user-profile', 'userProfile');
        }
    );



    Route::controller(workerauthcontroller::class)->prefix('worker')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/verify/{token}', 'verify');
    });





    Route::controller(clientauthcontroller::class)->prefix('client')->group(function ($router) {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });
});


Route::controller(wokrerprofilecontroller::class)->prefix('worker')->group(function () {
    Route::get('/worker-profile', 'workerProfile')->middleware('auth:worker');
    Route::get('/show-worker-profile', 'showtoupdate')->middleware('auth:worker');
    Route::post('/updateaftershow-worker-profile', 'updateaftershow')->middleware('auth:worker');
    Route::delete('/delete/posts', 'delete')->middleware('auth:worker');
});

Route::controller(postcontroller::class)->prefix('worker/post')->group(function () {
    Route::post('/add', 'store')->middleware('auth:worker');
    Route::get('/getall', 'index')->middleware('auth:admin');
    Route::get('/approved', 'approved');
});


Route::prefix('worker')->group(function () {

    Route::get("pending/orders", [ClientOrderController::class, 'workerorder'])->middleware('auth:worker');
    Route::post("updatestatusorder/{id}", [ClientOrderController::class, 'updatestatusorder'])->middleware('auth:worker');
    Route::post("ClinetReview", [WorkerReviewController::class, 'store'])->middleware('auth:client');
    Route::get("review/post/{id}", [WorkerReviewController::class, 'postrate']);
});


Route::prefix('admin')->group(function () {
    Route::controller(PostStatusController::class)->prefix('/post')->group(function () {
        Route::post('/status', 'changestatus');
    });
});

Route::controller(AdminNotificationController::class)->prefix('admin/notification')->group(function () {
    Route::get('/all', 'index')->middleware('auth:admin');
    Route::get('/unread', 'unread')->middleware('auth:admin');
    Route::get('/read', 'read')->middleware('auth:admin');
    Route::post('/markasread', 'markasread')->middleware('auth:admin');
    Route::post('/markoneasread/{id}', 'markoneasread')->middleware('auth:admin');
    Route::post('/deletenotifications', 'deletenotifications')->middleware('auth:admin');
    Route::post('/deleteonce/{id}', 'deletenotification');
});

Route::prefix('client')->group(function () {
    Route::controller(ClientOrderController::class)->prefix('/order')->group(function () {
        Route::post('/request', 'addorder')->middleware('auth:client');
    });
});


Route::controller(paymentcontroller::class)->group(function () {
    Route::get('/pay', 'pay');
});


Route::prefix('worker')->group(function () {
    Route::get('export', [Exportcontroller::class, 'export']);
    Route::post('import', [Exportcontroller::class, 'import']);
});
