<?php

use App\Console\Commands\Test1;
use App\Http\Controllers\Admin\AdminSellerBlogController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BuyerOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Member\MarketPlaceController;
use App\Http\Controllers\Member\SitePagesController;
use App\Http\Controllers\Member\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellOrderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\ContactUsFormController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

// Test Routes:
Route::get('/test1', [Test1::class, 'handle'])->name('test1');

/****** Front end routes */
Route::namespace('frontend')->group(function () {
    Route::get('backlink-market-wp-plugin', function () {
        return view('frontend.plugin');
    })->name('plugin');

    Route::get('/', function () {
        return view('frontend.index');
    })->name('homepage');

    Route::get('buy-backlinks', function () {
        return view('frontend.buy');
    })->name('buy');

    Route::get('sell-backlinks', function () {
        return view('frontend.sell');
    })->name('sell');

    Route::get('review', [ReviewController::class, 'reviewPage'])->name('review');

    Route::get('faq', function () {
        return view('frontend.faqs');
    })->name('faq');

    Route::get('contact', function () {
        return view('frontend.contact');
    })->name('contact');

    Route::get('privacy', function () {
        return view('frontend.privacy');
    })->name('privacy');

    Route::get('cookie-privacy', function () {
        return view('frontend.cookie_policy');
    })->name('cookie-privacy');

    Route::get('terms', function () {
        return view('frontend.terms');
    })->name('terms');

});

Auth::routes();
Route::tickets(TicketController::class);

//Everywhere Routes:
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::redirect('download-plugin', 'check-version?action=download&slug=backlink-market-connector', 301)->name('download-plugin');
Route::redirect('check-plugin-version', 'check-version?action=get_metadata&slug=backlink-market-connector', 301)->name('check-plugin-version');
Route::post('/contact', [ContactUsFormController::class, 'ContactUsForm'])->name('ContactUsForm');
Route::get('/check-version', function () {
            $server = new Wpup_UpdateServer(env('APP_URL').'/check-version');
            $server->handleRequest();
    });
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $request->user()->verifiedEmail();
    return redirect('/dashboard')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');




//Guest Routes
Route::middleware('guest')->group(function () {

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
    Route::get('/paypro-ipn', [OrderController::class, 'payproIPN'])->name('paypro-ipn');
    Route::post('/paypro-ipn', [OrderController::class, 'payproIPN'])->name('paypro-ipn-post');


});
//Member Routes
Route::middleware('role:member')->group(function () {

});
//Member Routes
Route::middleware('auth', 'verified')->group(function () {


    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('show-user-location-data', [LocationController::class, 'index']);

    Route::get('/notification-checked', [UserController::class, 'notificationChecked'])->name('notification_checked');

    Route::get('/editprofile', [UserController::class, 'editProfile'])->name('editprofile');
    Route::get('/seller_sites', [UserController::class, 'sellerSites'])->name('seller_sites');
    Route::get('/deletesite/{id}', [UserController::class, 'deleteSite'])->name('deletesite');
    Route::get('/site_pages/{id}/recheck', [UserController::class, 'recheckForPages'])->name('recheck');
    Route::get('/seller_pages/{id}', [UserController::class, 'sellerPages'])->name('seller_pages');
    Route::post('/updateprofile', [UserController::class, 'updateProfile'])->name('updateprofile');

    Route::post('/save_sites', [UserController::class, 'saveSites'])->name('save_sites');
    Route::post('/verify_site', [UserController::class, 'verifySite'])->name('verify_site');
    Route::post('/seller_pages/{id}', [UserController::class, 'sellerPagesSave'])->name('seller_pages_save');
    Route::get('/site_pages/{id}/versionrecheck', [UserController::class, 'recheckVersion'])->name('recheck_version');

    Route::get('/seller/create-page-form/{id?}', [SitePagesController::class, 'sellerCreatePageForm'])->name('marketplace.page.create.form');
    Route::get('/site_pages/{id}', [SitePagesController::class, 'sitePages'])->name('site_pages');
    Route::post('/seller/pageSave', [SitePagesController::class, 'savePage'])->name('seller.save.page');
    Route::post('/seller/editPage', [SitePagesController::class, 'editPage'])->name('seller.edit.page');
    Route::post('/site_pages/{id}/recheck', [SitePagesController::class, 'activatePagesRecheck'])->name('deactivate_pages_recheck');
    Route::post('/site_pages/{id}', [SitePagesController::class, 'sitePagesAction'])->name('site_pages_action');

    Route::get('/marketplace/delete/{id}', [MarketPlaceController::class, 'deletePage'])->name('marketplace.delete.page');
    Route::get('/seller/page-form/{id?}', [MarketPlaceController::class, 'sellerPageForm'])->name('marketplace.page.form');
    Route::get('/marketplace', [MarketPlaceController::class, 'index'])->name('marketplace');
    Route::post('/marketplace/save', [MarketPlaceController::class, 'savePage'])->name('marketplace.save.page');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/add_to_cart', [CartController::class, 'saveCart'])->name('add_to_cart');
    Route::post('/update_to_cart', [CartController::class, 'updateCart'])->name('update_to_cart');
    Route::get('/deletecartitem/{id}', [CartController::class, 'deleteCartItem'])->name('deletecartitem');

    Route::post('/getContentAPI', [MarketPlaceController::class, 'getContentAPI'])->name('getContentAPI');

    Route::post('/order/saveorder', [OrderController::class, 'saveOrder'])->name('saveorder');
    Route::post('/thank-you-order', [OrderController::class, 'thankYouOrder'])->name('thank-you-order');

    Route::get('/earnings', [SellOrderController::class, 'earnings'])->name('earnings');
    Route::get('/stopsubscription/{id}', [SellOrderController::class, 'stopSubscription'])->name('stopsubscription');
    Route::get('/seller-links', [SellOrderController::class, 'sellerlinks'])->name('sellerlinks');
    Route::get('/seller-orders', [SellOrderController::class, 'sellerorders'])->name('sellerorders');
    // not used anymore
    //Route::get('/purchase-order-history', [BuyerOrderController::class, 'index'])->name('buyerorder');


    Route::get('/buyer-orders', [BuyerOrderController::class, 'buyerorders'])->name('buyerorders');
    Route::get('/buyer-links', [BuyerOrderController::class, 'buyerlinks'])->name('buyerlinks');
    Route::get('/buyerstopsubscription/{link_id}', [BuyerOrderController::class, 'stopSubscription'])->name('buyerstopsubscription');
    //Route::get('/buyerresumesubscription/{link_id}', [BuyerOrderController::class, 'resumeSubscription'])->name('buyerresumesubscription');
    Route::post('/update_order_detail', [BuyerOrderController::class, 'updateOrderDetail'])->name('update_order_detail');
    Route::post('/update-links', [BuyerOrderController::class, 'updateLinks'])->name('updateLinks');

    Route::post('review-save', [ReviewController::class, 'reviewSave'])->name('review.save.post');

    Route::get('/notification', [NotificationController::class, 'index'])->name('notification');

    Route::get('/my-withdraw-list', [WithdrawController::class, 'myindex'])->name('mywithdrawlist');
    Route::get('/withdraw-request', [WithdrawController::class, 'create'])->name('withdrawrequest');
    Route::get('/temp', [WithdrawController::class, 'temp'])->name('temp');
    Route::post('/save-withdraw-request', [WithdrawController::class, 'store'])->name('savewithdrawrequest');

    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');

    Route::get('/receipt/{id}', [ReceiptController::class, 'index'])->name('receipt');

});

//Admin Routes
Route::middleware('role:admin')->group(function () {
    Route::post('/saveuser/{id}', [UserController::class, 'saveUser'])->name('saveuser');
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/changestatus/{id}', [UserController::class, 'changeStatus'])->name('changestatus');
    Route::get('/deleteuser/{id}', [UserController::class, 'deleteUser'])->name('deleteuser');
    Route::get('/getuser/{id}', [UserController::class, 'getUser'])->name('getuser');
    Route::get('/adminorder', [AdminOrderController::class, 'index'])->name('adminorder');
    Route::get('/adminnotifications', [AdminOrderController::class, 'adminnotifications'])->name('adminnotifications');

    Route::get('/adminorderlinks', [AdminOrderController::class, 'orderLinks'])->name('adminorderlinks');
    Route::get('/admin-purchase-transaction-history', [AdminOrderController::class, 'transaction'])->name('admin-transactions');
    Route::get('/admin-seller-blogs', [AdminSellerBlogController::class, 'index'])->name('adminsellerblogs');
    Route::get('/admin-blog-pages/{id}', [AdminSellerBlogController::class, 'getBlogPages'])->name('admin-blog-pages');
    Route::post('/admin-blog-pages/{id}', [AdminSellerBlogController::class, 'getBlogPagesAction'])->name('admin-blog-pages-actions');
    Route::get('/ban-blog/{id}', [AdminSellerBlogController::class, 'banBlogAction'])->name('ban-blog');
    Route::get('/relist-blog/{id}', [AdminSellerBlogController::class, 'relistBlogAction'])->name('relist-blog');
    Route::get('/delete-blog/{id}', [AdminSellerBlogController::class, 'deleteBlogAction'])->name('delete-blog');
    Route::get('/withdraw-list', [WithdrawController::class, 'index'])->name('withdrawlist');
    Route::get('/declinewitdraw/{id}', [WithdrawController::class, 'declineWitdraw'])->name('declinewitdraw');
    Route::get('/approvewitdraw/{id}', [WithdrawController::class, 'approveWitdraw'])->name('approvewitdraw');
    Route::get('/admin-review-list', [ReviewController::class, 'index'])->name('adminreviewlist');
    Route::get('/declinereview/{id}', [ReviewController::class, 'declineReview'])->name('declinereview');
    Route::get('/approvereview/{id}', [ReviewController::class, 'approveReview'])->name('approvereview');
    Route::get('/deletereview/{id}', [ReviewController::class, 'deletereview'])->name('deletereview');
    Route::get('/income', [AdminOrderController::class, 'income'])->name('adminincome');
});


