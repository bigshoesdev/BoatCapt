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

/*
|--------------------------------------------------------------------------
| General Routes
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/{account_type}/register', function ($account_type) {
    return view('auth.register', compact('account_type'));
})->middleware('guest');

Route::get('/', 'LanderController@index');

Route::get('/about', 'LanderController@about');

Route::get('/contact', 'LanderController@contact');

Route::get('/terms', 'LanderController@terms');

Route::get('/find-captains', 'CaptainController@findCaptains');
Route::post('/captain-list', 'CaptainController@captainList');

Route::get('{captainId}/captain-bio', 'CaptainController@captainBio');

Route::get('{captainId}/captain-bio-reviews', 'CaptainController@captainBioReviews');
Route::post('captain-bio-reviews', 'CaptainController@reviewList');

Route::get('/owner-booking-email', function(){
        return view('emails.booking-complete');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        if(Auth::user()->role == 1001)
        {
            return redirect('/admin-dashboard');
        }
        else if(Auth::user()->role == 1002)
        {
            return redirect('/owner-dashboard');
        }
        else if(Auth::user()->role == 1003)
        {
            return redirect('/captain-dashboard');
        }
    });
});

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['owner'], 'namespace'=>'Owner'], function () {

    Route::get('paypal-return/{bidId}', 'BidController@completePaymentWithPayPal');
    Route::get('paypal-cancel/{bidId}', 'BidController@paypalPaymentRejected');

    Route::get('/owner-dashboard', 'DashboardController@index');
    
    Route::post('/owner-bid-list', 'BidController@bidList');
    Route::get('/{bidId}/owner-view-bid', 'BidController@index');
    Route::get('/{bidId}/owner-booking', 'BidController@booking');   
    Route::post('/booking-complete', 'BidController@acceptBid');  
    Route::get('/{bidId}/reject-bid', 'BidController@rejectBid');
    Route::get('/{bidId}/owner-booking-complete', 'BidController@completeBooking');

    Route::get('/{captainId}/owner-book-captain', 'BookCaptainController@index');
    Route::post('/owner-book-captain', 'BookCaptainController@bookCaptain');
    Route::get('{captainId}/{tripId}/book-confirm', 'BookCaptainController@bookConfirm');
    Route::post('/request-captain', 'BookCaptainController@requestCaptain');
    Route::get('/{captainId}/send-request-captain', 'BookCaptainController@requestSend');

    Route::get('/owner-trips', 'TripController@index');
    Route::post('/owner-trip-list', 'TripController@tripList');
    Route::get('/{tripId}/owner-trip-detail', 'TripController@detail');
    Route::get('/{tripId}/contact-captain', 'TripController@contactCaptain');
    Route::post('/owner-pending-trips', 'BookCaptainController@bidRequestList');
    Route::get('/{tripId}/owner-pending-trip', 'BookCaptainController@bidRequestDetail');

    Route::get('/owner-profile', 'ProfileController@index');
    Route::post('/update-owner-profile', 'ProfileController@update');

    Route::get('/owner-billing', 'BillingController@index');
    Route::post('/update-owner-billing', 'BillingController@update');

    Route::get('/owner-reviews', 'ReviewController@index');
    Route::post('/owner-review-list', 'ReviewController@reviewList');
    Route::get('/{tripId}/owner-leave-review', 'ReviewController@detail');
    Route::post('/owner-leave-review', 'ReviewController@leaveReview');

});

/*
|--------------------------------------------------------------------------
| Captain Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['captain'], 'namespace'=>'Captain'], function () {

    Route::get('/captain-dashboard', 'DashboardController@index');

    Route::get('/captain-profile', 'ProfileController@index');
    Route::post('/update-captain-profile', 'ProfileController@update');

    Route::get('/captain-billing', 'BillingController@index');
    Route::post('/captain-billing', 'BillingController@update');

    Route::get('/captain-reviews', 'ReviewController@index');
    Route::post('/captain-review-list', 'ReviewController@reviewList');
    Route::get('/{tripId}/captain-leave-review', 'ReviewController@detail');
    Route::post('/captain-leave-review', 'ReviewController@leaveReview');

    Route::get('/captain-trips', 'TripController@index');
    Route::post('/captain-trip-list', 'TripController@tripList');    
    Route::get('/{tripId}/captain-trip-detail', 'TripController@detail');    
    Route::get('/{tripId}/contact-owner', 'TripController@contactOwner');
    Route::post('/captain-bid-list', 'BidController@bidList');
    Route::get('/{bidId}/captain-pending-trip', 'BidController@bidDetail');

    Route::post('/captain-bid-requests', 'BidController@bidRequestList');
    Route::get('/{tripId}/bid-request-detail', 'BidController@bidRequest');
    Route::get('/{tripId}/create-bid', 'BidController@index');
    Route::post('/create-bid', 'BidController@createBid');
    Route::get('/{tripId}/pass-trip', 'BidController@passTrip');
    Route::get('/bid-submitted', 'BidController@bidSubmitted');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['admin']], function () {

    Route::group(['namespace'=>'Admin'], function () {
        Route::get('/admin-dashboard', 'DashboardController@index');
        Route::post('/admin-dashboard-list', 'DashboardController@infoList');

        Route::get('/admin-trips', 'TripController@index');
        Route::post('/admin-trip-list', 'TripController@tripList');
        Route::get('/{tripId}/admin-trip-detail', 'TripController@detail');

        Route::get('/admin-revenue', 'RevenueController@index');
        Route::post('/admin-revenue-list', 'RevenueController@revenueList');
        Route::get('/{tripId}/admin-book-captain', 'BookCaptainController@index');
        Route::post('/admin-book-captain', 'BookCaptainController@bookCaptain');
        Route::get('{captainId}/{tripId}/admin-book-confirm', 'BookCaptainController@bookConfirm');
        Route::post('/admin-request-captain', 'BookCaptainController@requestCaptain');
        Route::get('/{captainId}/admin-request-captain', 'BookCaptainController@requestSend');

        Route::get('/admin-capts', 'CaptainController@index');
        Route::post('/admin-captain-list', 'CaptainController@captainList');
        Route::get('/{captainId}/admin-captain-profile', 'CaptainController@profile');
        Route::post('/admin-captain-profile', 'CaptainController@updateProfile');
        Route::get('/{captainId}/admin-captain-approve', 'CaptainController@approve');
        Route::get('/{captainId}/admin-captain-reject', 'CaptainController@reject');
        Route::get('/{captainId}/admin-captain-reviews', 'CaptainController@reviews');
        Route::post('/{captainId}/admin-captain-reviews', 'CaptainController@reviewsList');

        Route::get('/admin-owners', 'OwnerController@index');
        Route::post('/admin-owner-list', 'OwnerController@ownerList');
        Route::get('/{ownerId}/admin-owner-profile', 'OwnerController@profile');
        Route::post('/admin-owner-profile', 'OwnerController@updateProfile');
        Route::get('/{ownerId}/admin-owner-reviews', 'OwnerController@reviews');
        Route::post('/{ownerId}/admin-owner-reviews', 'OwnerController@reviewsList');

        Route::get('/admin-reviews', 'ReviewController@index');
        Route::post('/admin-review-list', 'ReviewController@reveiwList');

        Route::get('/admin-payments', 'PaymentController@index');
        Route::post('/admin-payment-list', 'PaymentController@paymentList');

        Route::get('/admin-fees', 'FeeController@index');
        Route::post('/admin-fee-list', 'FeeController@feeList');

        Route::get('/admin-nets', 'NetController@index');
        Route::post('/admin-net-list', 'NetController@netList');
    });

    Route::group(['namespace'=>'Owner'], function () {
        Route::get('/{ownerId}/admin-owner-billing', 'BillingController@index');
        Route::post('/{ownerId}/admin-owner-billing', 'BillingController@update');
    });

    Route::group(['namespace'=>'Captain'], function () {
        Route::get('/{captainId}/admin-captain-billing', 'BillingController@index');
        Route::post('/{captainId}/admin-captain-billing', 'BillingController@update');
    });

});
