<?php

use App\Http\Controllers\admin\AdminCredentials;
use App\Http\Controllers\admin\AdminPagesController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ManufacturerProfileController;
use App\Http\Controllers\menufacturer\ManufacturerPagesController;
use App\Http\Controllers\menufacturer\MenufacturerCredentialsController;
use App\Http\Controllers\menufacturer\SubscriptionController;
use App\Http\Controllers\SocialiteAuthConntroller;
use App\Http\Controllers\SocialKakaoLoginConntroller;
use App\Http\Controllers\surface\SurfacePagesController;
use App\Http\Controllers\wholesaler\CredentialsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/test-email', [SurfacePagesController::class, 'testEmail']);



Route::get('/', [SurfacePagesController::class, 'indexPage']);
Route::post('/filter-manufacturers', [SurfacePagesController::class, 'filterManufacturers'])->name('filter.manufacturers');
Route::get('/manufacturers', [SurfacePagesController::class, 'manufacturersPage']);
Route::get('/help', [SurfacePagesController::class, 'helpPage']);
Route::get('/privacy-policy', [SurfacePagesController::class, 'PrivacyPolicyPage']);
Route::get('/terms-of-use', [SurfacePagesController::class, 'TermsOfUsePage']);
Route::get('/contact-us', [SurfacePagesController::class, 'ContactUs']);

Route::get('/create-account', [SurfacePagesController::class, 'accountSelection']);



Route::post('/serach-menufacturer', [SurfacePagesController::class, 'searchMenufacturer']);
Route::get('/manufacturers/{manufacturer_name}/{manufacturer_uid}', [SurfacePagesController::class, 'specManufacturer']);
Route::post('/wholesaler/review-manufacturer', [SurfacePagesController::class, 'reviewManufacturer'])->middleware('wholesaler');



// wholesaler
Route::get('/wholesaler/login', [CredentialsController::class, 'wholesalerSignInPage']);
Route::post('/wholesaler/sign-in-verification', [CredentialsController::class, 'wholesalerSignInVerification']);
Route::get('/wholesaler/signup', [CredentialsController::class, 'wholesalerSignupPages']);
Route::post('/wholesaler/verify-signup', [CredentialsController::class, 'verifySignUp']);
Route::post('/wholesaler/otp-verification', [CredentialsController::class, 'OTPVerification']);

Route::get('/wholesaler/forget-password', [CredentialsController::class, 'wholesalerForgetPwd']);
Route::post('/wholesaler/forget-password-request', [CredentialsController::class, 'wholesalerForgetPwdRequest']);
Route::get('/wholesaler/reset-link-sent', [CredentialsController::class, 'resetLinkSent']);
Route::get('/wholesaler/verify-reset-password/{reset_token}', [CredentialsController::class, 'verifyResetPassword']);
Route::post('/wholesaler/setup-new-password', [CredentialsController::class, 'resetPassword']);

Route::get('/wholesaler/profile-setup', [CredentialsController::class, 'completeWholeSalerProfile'])->middleware('wholesaler');
Route::post('/wholesaler/complete-profile-setup', [CredentialsController::class, 'wholesalerProfileSetup'])->middleware('wholesaler');
Route::post('/wholesaler/logout', [CredentialsController::class, 'logoutWholeSaler'])->middleware('wholesaler');



Route::get('/wholesaler/set-up-wholesaler-{page_type}', [CredentialsController::class, 'wholesalerProfile'])->middleware('wholesaler');
Route::post('/wholesaler/update-profile-image', [CredentialsController::class, 'changeProfilePicture'])->middleware('wholesaler');
Route::post('/wholesaler/change-email-address', [CredentialsController::class, 'changeEmailAddress'])->middleware('wholesaler');
Route::post('/wholesaler/change-account-password', [CredentialsController::class, 'changeAccountPassword'])->middleware('wholesaler');
Route::post('/wholesaler/change-language-selection', [CredentialsController::class, 'changeLanguageChoice'])->middleware('wholesaler');
Route::post('/wholesaler/delete-account', [CredentialsController::class, 'deleteAccount'])->middleware('wholesaler');
Route::get('/wholesaler/account-deleted', [CredentialsController::class, 'deletedAccountPage']);














// manufacturer 
Route::get('/manufacturer/login', [MenufacturerCredentialsController::class, 'manufacturerSigninPage']);
Route::post('/manufacturer/sign-in-verification', [MenufacturerCredentialsController::class, 'manufacturerSignInVerfication']);

Route::get('/manufacturer/signup', [MenufacturerCredentialsController::class, 'manufacturerSignupPages']);
Route::post('/manufacturer/verify-signup', [MenufacturerCredentialsController::class, 'verifySignUp']);
Route::post('/manufacturer/otp-verification', [MenufacturerCredentialsController::class, 'OTPVerification']);










// GOOGLE SOCIALITE
Route::get('/manufacturer/signup-with-google', [SocialiteAuthConntroller::class, 'manufacturerGoogleSignUp']);
Route::get('/manufacturer/auth/google/callback', [SocialiteAuthConntroller::class, 'manufacturerGoogleCallback']);
Route::get('/manufacturer/login-with-google', [SocialiteAuthConntroller::class, 'manufacturerGoogleLogin']);
Route::get('/manufacturer/auth/google-login-callback', [SocialiteAuthConntroller::class, 'manufacturerGoogleLoginCallback']);


Route::get('/wholesaler/signup-with-google', [SocialiteAuthConntroller::class, 'wholesalerGoogleSignUp']);
Route::get('/wholesaler/auth/google/callback', [SocialiteAuthConntroller::class, 'wholesalerGoogleCallback']);
Route::get('/wholesaler/login-with-google', [SocialiteAuthConntroller::class, 'wholesalerGoogleLogin']);
Route::get('/wholesaler/auth/google-login-callback', [SocialiteAuthConntroller::class, 'wholesalerGoogleLoginCallback']);



// Manufacturer - Kakao
Route::get('/manufacturer/signup-with-kakao', [SocialKakaoLoginConntroller::class, 'manufacturerKakaoSignUp']);
Route::get('/manufacturer/auth/kakao/callback', [SocialKakaoLoginConntroller::class, 'manufacturerKakaoCallback']);
Route::get('/manufacturer/login-with-kakao', [SocialKakaoLoginConntroller::class, 'manufacturerKakaoLogin']);
Route::get('/manufacturer/auth/kakao-login-callback', [SocialKakaoLoginConntroller::class, 'manufacturerKakaoLoginCallback']);

// Wholesaler - Kakao
Route::get('/wholesaler/signup-with-kakao', [SocialKakaoLoginConntroller::class, 'wholesalerKakaoSignUp']);
Route::get('/wholesaler/auth/kakao/callback', [SocialKakaoLoginConntroller::class, 'wholesalerKakaoCallback']);
Route::get('/wholesaler/login-with-kakao', [SocialKakaoLoginConntroller::class, 'wholesalerKakaoLogin']);
Route::get('/wholesaler/auth/kakao-login-callback', [SocialKakaoLoginConntroller::class, 'wholesalerKakaoLoginCallback']);
 





// Route::get('/manufacturer/application', [MenufacturerCredentialsController::class, 'completeApplication'])->middleware('manufacturer');
// Route::post('/manufacturer/complete-application', [MenufacturerCredentialsController::class, 'completeApplicationSubmit'])->middleware('manufacturer');
// Route::get('/manufacturer/application-successful', [MenufacturerCredentialsController::class, 'applicationSuccessful'])->middleware('manufacturer');

// Manufacturer Profile Completion Routes
Route::get('/manufacturer/application', [ManufacturerProfileController::class, 'showStep'])->name('manufacturer.application')->middleware('manufacturer');
Route::post('/manufacturer/application/step/{step}', [ManufacturerProfileController::class, 'saveStep'])->name('manufacturer.application.step.save')->middleware('manufacturer');
Route::get('/manufacturer/application/step/{step}', [ManufacturerProfileController::class, 'showStep'])->name('manufacturer.application.step')->middleware('manufacturer');
Route::post('/manufacturer/application/final-submit', [ManufacturerProfileController::class, 'finalSubmit'])->name('manufacturer.application.final.submit')->middleware('manufacturer');
Route::get('/manufacturer/application-successful', [ManufacturerProfileController::class, 'applicationSuccessful'])->name('manufacturer.application.successful')->middleware('manufacturer');



Route::post('/manufacturer/logout', [MenufacturerCredentialsController::class, 'logoutManufacturer'])->middleware('manufacturer');

Route::get('/manufacturer/forget-password', [MenufacturerCredentialsController::class, 'manufacturerForgetPwd']);
Route::post('/manufacturer/forget-password-request', [MenufacturerCredentialsController::class, 'manufacturerForgetPwdRequest']);
Route::get('/manufacturer/reset-link-sent', [MenufacturerCredentialsController::class, 'resetLinkSent']);
Route::get('/manufacturer/verify-reset-password/{reset_token}', [MenufacturerCredentialsController::class, 'verifyResetPassword']);
Route::post('/manufacturer/setup-new-password', [MenufacturerCredentialsController::class, 'resetPassword']);




Route::get('/manufacturer/packages', [SubscriptionController::class, 'manufacturerSubscription'])->name('manufacturer.subscription');
Route::post('/manufacturer/check-coupon-code', [SubscriptionController::class, 'checkCouponCode']);


Route::post('/manufacturer/purchase-subscription', [SubscriptionController::class, 'purchaseSubscription'])->middleware('approved_manufacturer');
Route::post('/manufacturer/process-payment', [SubscriptionController::class, 'processSubscriptionPayment'])->name('manufacturer.process-payment')->middleware('approved_manufacturer');

Route::get('/manufacturer/subscription-success', [SubscriptionController::class, 'subscriptionSuccess'])->name('manufacturer.subscription-success')->middleware('approved_manufacturer');
Route::get('/manufacturer/subscription-cancel', [SubscriptionController::class, 'subscriptionCancel'])->name('manufacturer.subscription-cancel')->middleware('approved_manufacturer');


Route::get('/manufacturer/manage-subscription', [SubscriptionController::class, 'manageSubscription'])->name('manufacturer.manage-subscription')->middleware('manufacturer');
Route::post('/manufacturer/cancel-subscription', [SubscriptionController::class, 'cancelSubscription'])->name('manufacturer.cancel-subscription')->middleware('approved_manufacturer');



Route::get('/manufacturer/set-up-manufacturer-{page_type}', [ManufacturerPagesController::class, 'manufacturerProfilePage'])->middleware('manufacturer');
Route::post('/manufacturer/change-email-address', [MenufacturerCredentialsController::class, 'changeEmailAddress'])->middleware('manufacturer');
Route::post('/manufacturer/change-account-password', [MenufacturerCredentialsController::class, 'changeAccountPassword'])->middleware('manufacturer');
Route::post('/manufacturer/change-language-selection', [MenufacturerCredentialsController::class, 'changeLanguageChoice'])->middleware('manufacturer');
Route::post('/manufacturer/delete-account', [MenufacturerCredentialsController::class, 'deleteAccount'])->middleware('manufacturer');
Route::get('/manufacturer/account-deleted', [MenufacturerCredentialsController::class, 'deletedAccountPage']);









// message routes.
Route::get('/manufacturer/chats', [ChatsController::class, 'chatRecords'])->middleware('manufacturer');
Route::get('/wholesaler/chats', [ChatsController::class, 'chatRecords'])->middleware('wholesaler');
Route::get('/wholesaler/chats/{manufacturer_uid}', [ChatsController::class, 'chatWithSpecManufacturer'])->middleware('wholesaler');

Route::post('/fetch-chats', [ChatsController::class, 'fetchChats'])->middleware('manufacturer_or_wholesaler');
Route::post('/send-text-message', [ChatsController::class, 'sendMessage'])->middleware('manufacturer_or_wholesaler');
Route::post('/send-file-message', [ChatsController::class, 'sendFileMessage'])->middleware('manufacturer_or_wholesaler');
Route::post('/get-user-chat-info', [ChatsController::class, 'getUserChatInfo'])->middleware('manufacturer_or_wholesaler');
Route::post('/get-unread-count', [ChatsController::class, 'getUnreadCount'])->middleware('manufacturer_or_wholesaler');
Route::post('/update-last-active', [ChatsController::class, 'updateLastActive'])->middleware('manufacturer_or_wholesaler');
Route::post('/get-chat-list-item', [ChatsController::class, 'getChatListItem'])->middleware('manufacturer_or_wholesaler');
Route::post('/mark-messages-seen', [ChatsController::class, 'markMessagesAsSeen'])->middleware('manufacturer_or_wholesaler');
Route::post('/update-last-active', [ChatsController::class, 'updateLastActive'])->middleware('manufacturer_or_wholesaler');
Route::post('/mark-all-unseen-seen', [ChatsController::class, 'markAllUnseenAsSeen']);


Route::post('/get-total-unread-count', [ChatsController::class, 'getTotalUnreadCount'])->middleware('manufacturer_or_wholesaler');














// admin panel
Route::get('/admin/login', [AdminCredentials::class, 'adminLoginPage']);
Route::post('/admin/verify-login', [AdminCredentials::class, 'verifyAndLogin']);
Route::post('/admin/logout', [AdminCredentials::class, 'adminLogout']);


Route::get('/admin/dashboard', [AdminPagesController::class, 'adminDashboard'])->middleware('admin');
Route::get('/admin/users/manufacturers', [AdminPagesController::class, 'showManufacturers'])->middleware('admin');
Route::post('/admin/users/manufacturers/change-status', [AdminPagesController::class, 'changeManufacturerStatus'])->middleware('admin');
Route::get('/admin/manufacturers/{manufacturer_uid}/reviews', [AdminPagesController::class, 'showManufacturerReviews'])->middleware('admin');
Route::get('/admin/reviews/{review_id}/{option}', [AdminPagesController::class, 'reviewToggle'])->middleware('admin');
Route::delete('/admin/reviews/delete/{review_id}', [AdminPagesController::class, 'deleteReview'])->middleware('admin');


Route::get('/admin/users/wholesalers', [AdminPagesController::class, 'showWholesalers'])->middleware('admin');
Route::post('/admin/wholesaler-toggle-restriction', [AdminPagesController::class, 'toggleWholesalerRestriction'])->middleware('admin');


Route::get('/admin/settings/general', [AdminPagesController::class, 'websiteSettingsPage'])->middleware('admin');
Route::post('/admin/update-website-settings', [AdminPagesController::class, 'updateWebsiteSettings'])->middleware('admin');
Route::get('/admin/subscription-records', [AdminPagesController::class, 'subscriptionRecords'])->middleware('admin');
Route::get('/admin/frequently-asked-questions', [AdminPagesController::class, 'showFAQs'])->middleware('admin');
Route::post('/admin/frequently-asked-questions/create', [AdminPagesController::class, 'createFAQ'])->middleware('admin');
Route::post('/admin/frequently-asked-questions/update', [AdminPagesController::class, 'updateFAQ'])->middleware('admin');
Route::post('/admin/frequently-asked-questions/delete', [AdminPagesController::class, 'deleteFAQ'])->middleware('admin');

Route::post('/admin/coupon-code/create', [AdminPagesController::class, 'addNewCouponCode'])->middleware('admin');
Route::get('/admin/settings/coupon-codes', [AdminPagesController::class, 'couponCodes'])->middleware('admin');
Route::post('/admin/coupon-code/update', [AdminPagesController::class, 'updateCouponCode'])->middleware('admin');
Route::post('/admin/coupon-code/delete', [AdminPagesController::class, 'deleteCouponCode'])->middleware('admin');


Route::post('/admin/settings/subscription-packages/create', [AdminPagesController::class, 'addNewSubscriptionPackage'])->middleware('admin');
Route::get('/admin/settings/subscription-packages', [AdminPagesController::class, 'SubscriptionPackages'])->middleware('admin');
Route::post('/admin/settings/subscription-packages/update', [AdminPagesController::class, 'updateSubscriptionPackage'])->middleware('admin');
Route::post('/admin/settings/subscription-packages/delete', [AdminPagesController::class, 'deleteSubscriptionPackage'])->middleware('admin');


Route::get('/admin/account-settings', [AdminPagesController::class, 'adminAccount'])->middleware('admin');
Route::post('/admin/verify-account-email-update', [AdminPagesController::class, 'adminEmailVerification'])->middleware('admin');
Route::post('/admin/verify-email-otp', [AdminPagesController::class, 'verifyEmailChangeOTP'])->middleware('admin');
Route::post('/admin/verify-account-password-update', [AdminPagesController::class, 'adminPasswordVerification'])->middleware('admin');
Route::post('/admin/verify-password-otp', [AdminPagesController::class, 'verifyPasswordChangeOTP'])->middleware('admin');








Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
    return 'Cache Configed, Cleared. View Cleared & Storage Linked Successfully!';
});
