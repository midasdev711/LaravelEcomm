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
    use Modules\Admin\Http\Controllers\AdminController;
    use Modules\Admin\Http\Controllers\HomeController;

    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin-profile');
        Route::post('/profile/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update');
        Route::get('/messages/five', [AdminController::class, 'messageFive'])->name('messages.five');
    });
    Route::prefix('user')->middleware('auth')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('user');
// Profile
        Route::get('/profile', [HomeController::class, 'profile'])->name('user-profile');
        Route::post('/profile/{id}', [HomeController::class, 'profileUpdate'])->name('user-profile-update');
//  Order
        Route::get('/order', [HomeController::class, 'orderIndex'])->name('user.order.index');
        Route::get('/order/show/{id}', [HomeController::class, 'orderShow'])->name('user.order.show');
        Route::delete('/order/delete/{id}', [HomeController::class, 'userOrderDelete'])->name('user.order.delete');
// Product Review
        Route::get('/user-review', [HomeController::class, 'productReviewIndex'])->name('user.productreview.index');
        Route::delete('/user-review/delete/{id}',
            [HomeController::class, 'productReviewDelete'])->name('user.productreview.delete');
        Route::get('/user-review/edit/{id}', [HomeController::class, 'productReviewEdit'])->name('user.productreview.edit');
        Route::patch('/user-review/update/{id}',
            [HomeController::class, 'productReviewUpdate'])->name('user.productreview.update');
// Post comment
        Route::get('user-post/comment', [HomeController::class, 'userComment'])->name('user.post-comment.index');
        Route::delete('user-post/comment/delete/{id}',
            [HomeController::class, 'userCommentDelete'])->name('user.post-comment.delete');
        Route::get('user-post/comment/edit/{id}',
            [HomeController::class, 'userCommentEdit'])->name('user.post-comment.edit');
        Route::patch('user-post/comment/update/{id}',
            [HomeController::class, 'userCommentUpdate'])->name('user.post-comment.update');
// Password Change
        Route::get('change-password', [HomeController::class, 'changePassword'])->name('user.change.password.form');
        Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('user.change.password');
    });
