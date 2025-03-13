<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\CMS\HomeController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\KeyDocumentController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\StripeWebhookController;
use App\Http\Controllers\API\CorePublicationController;
use App\Http\Controllers\API\DonationPaymentController;
use App\Http\Controllers\API\Auth\SocialLoginController;
use App\Http\Controllers\API\PresidingCouncilController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\DonationWithStripeController;

Route::group(['middleware' => 'guest:api'], static function () {
    //register
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
    Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
    //login
    Route::post('login', [LoginController::class, 'login']);
    //forgot password
    Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ResetPasswordController::class, 'VerifyOTP']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
    //social login
    Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
});

Route::group(['middleware' => 'guest:api'], static function () {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LogoutController::class, 'logout']);

    //Teacher Profile management
    Route::get('/teacher/profile', [UserController::class, 'TeacherProfile']);
    Route::post('/teacher/upload-avatar', [UserController::class, 'TeacherUploadAvatar']);
    Route::post('/teacher/update-profile', [UserController::class, 'TeacherUpdateProfile']);
    Route::delete('/teacher/delete-profile', [UserController::class, 'TeacherDeleteProfile']);
    Route::post('/change-password', [ResetPasswordController::class, 'teacherPasswordManager']);

    //core Publications
    Route::get('/core-publications', [CorePublicationController::class, 'CorePublications']);
    Route::get('/presiding-council', [PresidingCouncilController::class, 'PresidingCouncil']);
    Route::get('/key-document', [KeyDocumentController::class, 'KeyDocument']);
    /* ============================Contact all Routes Start ========================= */
    Route::post('/contact', [ContactController::class, 'Contact']);
    Route::get('/contact/list', [ContactController::class, 'ContactList']);
    /* ============================Contact all Routes End ========================= */

    /* ============================CMS Home  Start ========================= */
    Route::get('/cms/home/banner', [HomeController::class, 'Banner']);
    Route::get('/cms/home/core-publication', [HomeController::class, 'CorePublication']);
    Route::get('/cms/home/history', [HomeController::class, 'History']);
    Route::get('/cms/home/join-group', [HomeController::class, 'JoinGroup']);
    Route::get('/cms/home/presiding-council', [HomeController::class, 'PresidingCouncil']);
    Route::get('/cms/home/donation', [HomeController::class, 'Donation']);
    Route::get('/cms/home/about', [HomeController::class, 'About']);
    /* ============================CMS Home  End ========================= */

    /* ============================CMS Key Document  Start ========================= */
    Route::get('/cms/key-document/banner', [\App\Http\Controllers\API\CMS\KeyDocumentController::class, 'Banner']);

    /* ============================CMS Key Document Banner End ========================= */

    /* ============================CMS Contact Start ========================= */
    Route::get('/cms/contact/banner', [\App\Http\Controllers\API\CMS\ContactController::class, 'Banner']);

    /* ============================CMS Key Contact End ========================= */

    /* ============================CMS Presiding Council Start ========================= */
    Route::get('/cms/presiding/council/banner', [\App\Http\Controllers\API\CMS\PresidingCouncilController::class, 'Banner']);
    Route::get('/cms/presiding/council/about', [\App\Http\Controllers\API\CMS\PresidingCouncilController::class, 'About']);

    /* ============================CMS Presiding Council End ========================= */
    /* ============================CMS Contact Start ========================= */
    Route::get('/cms/membership/content', [\App\Http\Controllers\API\CMS\MembershipController::class, 'Content']);
    Route::get('/memberships', [\App\Http\Controllers\API\CMS\MembershipController::class, 'GetMembership']);
    Route::get('/default-article', [\App\Http\Controllers\API\CMS\MembershipController::class, 'defaultArticle']);

    /* ============================CMS Key Contact End ========================= */

    /*==========================User Member ship all routes =============================*/
    Route::get('/user/membership/list', [\App\Http\Controllers\API\UserMembershipController::class, 'UserList']);
    Route::post('/user/membership/join', [\App\Http\Controllers\API\UserMembershipController::class, 'joinMembership']);
    // Route::post('/user/membership/leave', [\App\Http\Controllers\API\UserMembershipController::class, 'LeaveMembership']);
    /*==========================User Member ship all routes End============================*/
});

//auth routes
Route::group(['middleware' => 'auth:api'], static function () {

    /**check user  */
    Route::get('/check/user', [UserController::class, 'checkUser']);
    /**============================================Publication ==========================*/
    Route::get('/articles', [\App\Http\Controllers\API\PublicationController::class, 'Publications']);
    Route::get('/article/details/{id}', [\App\Http\Controllers\API\PublicationController::class, 'PublicationDetails']);

    /**============================================end publication End==============================*/
    Route::post('/payment', [PaypalController::class, 'makePayment'])->name('make.payment');
});
//donation with PayPal
Route::post('/payment/donation', [DonationPaymentController::class, 'DonationPayment'])->name('donation.payment');
Route::get('/terms-and-conditions', [\App\Http\Controllers\API\TermsAndConditionController::class, 'getTermsAndConditions']);
Route::get('/privacy-policy', [\App\Http\Controllers\API\PrivacyController::class, 'getPrivacyPolicy']);

//Donation with stripe
Route::post('/donate', [DonationWithStripeController::class, 'donate']);
