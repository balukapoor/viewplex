<?php

/**
 * Public Routes
 * -----------------------------------------------------------
 */


// Login is the first page a new visitor should see

Route::get('/', function () {
    return redirect('login');
});

// The Public Tour Route
Route::post('/public-subscription', 'PublicPageController@PublicSubscription');

Route::get('/contact', function() {
	return view('contact');
});
Route::post('/contact', 'PublicPageController@contact');

Route::get('/public/{domain}/{tourId}', 'PublicController@viewTour');

Route::post('/likes', 'PublicPageController@TourLikes');
// Legal & Misc
Route::get('/guide', 'PublicPageController@guideIndex');
Route::get('/legal/terms-and-conditions', 'PublicPageController@termsIndex');
Route::get('/legal/privacy-policy', 'PublicPageController@privacyIndex');
Route::get('/legal/dmca', 'PublicPageController@dmcaIndex');


// Splashes
Route::get('/deactivation-confirmation', 'PublicPageController@deactivationIndex')->name('deactivation-confirmation');
Route::get('/no-js', 'PublicPageController@noJs');
Route::get('/not-compatible', function (){ return view('errors.browser-compability'); });



/**
 * Private Routes protected by Auth Facade
 * ----------------------------------------------------------
 */		Auth::routes();

// User verification
Route::get('/user/verify-account', 'UsersController@verifyIndex');
Route::post('/user/verify-account', 'UsersController@ajaxVerify');
Route::post('/user/verify-account-code', 'UsersController@ajaxVerifyCode');
Route::post('/user/verify-account/redirect', 'UsersController@verifyRedirect');


// User reactivation
Route::get('/user/reactivation', 'UsersController@reactivateIndex');
Route::post('/user/reactivation', 'UsersController@reactivateAccount');


// Routes protected by various account & subscription related middleware
Route::group(
	['middleware' => [
		'account.activated', 'account.verified', 'subscription.paid']
	],

	function () {

	/**
	 * Tours
	 */
	Route::get('/tours', 'ToursController@index')->name('tours');
	Route::get('/tours/search', 'ToursController@searchResults');
	Route::get('/tours/new', 'UploadController@uploadIndex');

	Route::post('/tours/new', 'UploadController@ajaxMakeTour');
	Route::post('/tours/new/store-image', 'UploadController@ajaxStoreImage');
	
	Route::post('/tours/new/redirect', 'UploadController@uploadSuccessRedirect');

	Route::get('/tours/new/redirect', 'UploadController@uploadSuccessRedirect');
	
	
	Route::get('/tours/{tourId}/make-public', 'ToursController@makeTourPublic')->middleware('subscription.allowance');
    
	Route::get('/tours/{tourId}/make-private', 'ToursController@makeTourPrivate');
	Route::get('/tours/{tourId}/edit', 'ToursController@editTour');
	Route::post('/tours/{tourId}/update', 'ToursController@updateTour');
	Route::get('/tours/{tourId}/delete', 'ToursController@deleteTour');
	Route::get('/tours/{userid}/{tourId}', 'PublicPageController@viewPublicTour');

	/**
	 * User
	 */
	Route::get('/user/settings', 'UsersController@settingsIndex')->name('settings');
	Route::post('/user/settings/{setting}', 'UsersController@settingsUpdate');

	Route::get('/user/billing', 'UsersController@billingIndex')->name('billing');
	Route::get('/user/billing/change-payment-method', 'UsersController@billingChange')->name('billing');
	Route::get('/user/subscription', 'SubscriptionController@subscriptionIndex')->name('subscription');
	Route::get('/user/subscription/cancel', 'SubscriptionController@subscriptionCancelIndex');
	Route::post('/user/subscription/cancel', 'SubscriptionController@subscriptionCancel');
	Route::get('/user/subscription/upgrade', 'SubscriptionController@subscriptionUpgradeIndex');
	Route::get('/update-confirm', function() {
		return view('update-confirm');
	});
	Route::post('/user/change/card', 'SubscriptionController@updateCardInfo');
	Route::get('/user/subscription/order', 'SubscriptionController@subscriptionOrderIndex');
	Route::post('/user/subscription/order', 'SubscriptionController@newSubscriptionRequest');
	Route::get('/order-confirmation', 'SubscriptionController@orderConfirmation');
	Route::get('/upgrade-subscription', 'SubscriptionController@upgradeSplash')->name('upgrade-subscription');

	Route::get('/user/deactivate-account', 'UsersController@deactivateIndex');
	Route::post('/user/deactivate-account', 'UsersController@deactivateAccount');

	/**
	 * Misc routes, small pages, redirects etc
	 */

	Route::get('/support', 'PublicPageController@supportIndex');

	Route::get('/', function () {
	    return redirect('tours');
	});

	Route::get('/home', function () {
	    return redirect('tours');
	});


	/**
	 * Dev Routes
	 * --------------------------------------------------------
	 */

	if (getenv('APP_ENV') != 'production') {
		;
		Route::get('/populate-tours', 'ToursController@tempPopulateTours')->name('populate-tours');
		Route::get('/tours/viewstatic', 'ViewerController@viewerIndex');

	}

});