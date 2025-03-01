<?php

use App\Http\Controllers\Web\Backend\DefaultMembershipArticleController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\StripeWebhookController;
use App\Http\Controllers\Web\Backend\AdminController;
use App\Http\Controllers\API\DonationPaymentController;
use App\Http\Controllers\Web\Backend\PresidingController;
use App\Http\Controllers\API\DonationWithStripeController;
use App\Http\Controllers\Web\Backend\MembershipController;
use App\Http\Controllers\Web\Backend\KeyDocumentController;
use App\Http\Controllers\Web\Backend\PublicationController;
use App\Http\Controllers\Web\Backend\SystemSettingController;
use App\Http\Controllers\Web\Backend\CMS\Home\AboutController;
use App\Http\Controllers\Web\Backend\CMS\Home\BannerController;
use App\Http\Controllers\Web\Backend\CorePublicationController;
use App\Http\Controllers\Web\Backend\CMS\Home\HistoryController;
use App\Http\Controllers\Web\Backend\CMS\Home\DonationController;
use App\Http\Controllers\Web\Backend\PublicationCategoryController;
use App\Http\Controllers\Web\Backend\CMS\Home\PresidingCouncilController;
use App\Http\Controllers\Web\Backend\CMS\Home\HowToJoinTheGroupController;
use App\Http\Controllers\Web\Backend\TermsAndConditionAndPrivacyPolicyController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->email === null || Auth::user()->password === null) {
            Auth::logout();
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
    return view('auth.login');
});


Route::middleware('admin')->group(function () {
    //AdminController Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth', 'admin');

    //SystemSettingsController Routes
    Route::get('/admin/system-settings', [SystemSettingController::class, 'index'])->name('admin.system-settings');
    Route::post('/admin/system-settings', [SystemSettingController::class, 'update'])->name('admin.system-settings.update');
    Route::get('/admin/mail-settings', [SystemSettingController::class, 'mailSetting'])->name('admin.mail-settings');
    Route::post('/admin/mail-settings', [SystemSettingController::class, 'mailSettingUpdate'])->name('admin.mail-settings.update');
    Route::get('/admin/profile', [SystemSettingController::class, 'profileIndex'])->name('admin.profile');
    Route::post('/admin/profile', [SystemSettingController::class, 'profileUpdate'])->name('admin.profile.update');
    Route::post('/admin/password', [SystemSettingController::class, 'passwordUpdate'])->name('admin.password.update');


    Route::get('/admin/paypal-settings', [SystemSettingController::class, 'PaypalSetting'])->name('admin.paypal-settings');
    Route::post('/admin/paypal-settings', [SystemSettingController::class, 'paypalSettingUpdate'])->name('admin.paypal-settings.update');

    Route::get('/admin/stripe-settings', [SystemSettingController::class, 'StripeSetting'])->name('admin.stripe-settings');
    Route::post('/admin/stripe-settings', [SystemSettingController::class, 'stripeSettingUpdate'])->name('admin.stripe-settings.update');

    //Donation History Routes
    Route::get('/admin/donation-history', [\App\Http\Controllers\Web\Backend\DonationHistoryController::class, 'index'])->name('admin.donation-history.index');
    Route::delete('/admin/donation-delete/{id}', [\App\Http\Controllers\Web\Backend\DonationHistoryController::class, 'delete'])->name('admin.donation-hiostory.delete');

    //Contact list History Routes
    Route::get('/admin/contact-history', [\App\Http\Controllers\Web\Backend\ContactHistoryController::class, 'index'])->name('admin.contact-history.index');
    Route::delete('/admin/contact-delete/{id}', [\App\Http\Controllers\Web\Backend\ContactHistoryController::class, 'delete'])->name('admin.contact-hiostory.delete');
    Route::get('/admin/contact-view/{id}', [\App\Http\Controllers\Web\Backend\ContactHistoryController::class, 'view'])->name('admin.contact-hiostory.view');

    //User membership History Routes
    Route::get('/admin/membership-history', [\App\Http\Controllers\Web\Backend\MembershipHistoryController::class, 'index'])->name('admin.membership-history.index');
    Route::delete('/admin/membership-delete/{id}', [\App\Http\Controllers\Web\Backend\MembershipHistoryController::class, 'delete'])->name('admin.membership-hiostory.delete');

    //PresidingController Routes
    Route::get('/admin/presiding', [PresidingController::class, 'index'])->name('admin.presiding_councils.index');
    Route::get('/admin/presiding/create', [PresidingController::class, 'create'])->name('admin.presiding_councils.create');
    Route::post('/admin/presiding', [PresidingController::class, 'store'])->name('admin.presiding_councils.store');
    Route::get('/admin/presiding/{id}', [PresidingController::class, 'edit'])->name('admin.presiding_councils.edit');
    Route::put('/admin/presiding/{id}', [PresidingController::class, 'update'])->name('admin.presiding_councils.update');
    Route::delete('/admin/presiding/{id}', [PresidingController::class, 'destroy'])->name('admin.presiding_councils.destroy');
    Route::get('/admin/presiding/status/{id}', [PresidingController::class, 'changeStatus'])->name('admin.presiding_councils.status');

    //Core Publications Routes
    Route::get('/admin/core-publication', [CorePublicationController::class, 'index'])->name('admin.core_publication.index');
    Route::get('/admin/core-publication/create', [CorePublicationController::class, 'create'])->name('admin.core_publication.create');
    Route::post('/admin/core-publication', [CorePublicationController::class, 'store'])->name('admin.core_publication.store');
    Route::get('/admin/core-publication/{id}', [CorePublicationController::class, 'edit'])->name('admin.core_publication.edit');
    Route::put('/admin/core-publication/{id}', [CorePublicationController::class, 'update'])->name('admin.core_publication.update');
    Route::delete('/admin/core-publication/{id}', [CorePublicationController::class, 'destroy'])->name('admin.core_publication.destroy');

    //Core Categories Routes
    Route::get('/admin/category', [PublicationCategoryController::class, 'index'])->name('admin.category.index');
    Route::get('/admin/category/create', [PublicationCategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/admin/category', [PublicationCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/admin/category/{id}', [PublicationCategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/admin/category/{id}', [PublicationCategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}', [PublicationCategoryController::class, 'destroy'])->name('admin.category.destroy');

    //Reacher Publication Routes
    Route::get('/admin/reacher-publication', [PublicationController::class, 'index'])->name('admin.publication.index');
    Route::get('/admin/reacher-publication/create', [PublicationController::class, 'create'])->name('admin.publication.create');
    Route::post('/admin/reacher-publication', [PublicationController::class, 'store'])->name('admin.publication.store');
    Route::get('/admin/reacher-publication/{id}', [PublicationController::class, 'edit'])->name('admin.publication.edit');
    Route::put('/admin/reacher-publication/{id}', [PublicationController::class, 'update'])->name('admin.publication.update');
    Route::delete('/admin/reacher-publication/{id}', [PublicationController::class, 'destroy'])->name('admin.publication.destroy');
    Route::get('/admin/reacher-publication/status/{id}', [PublicationController::class, 'changeStatus'])->name('admin.publication.status');


    //Key Document Routes
    Route::get('/admin/key/document', [KeyDocumentController::class, 'index'])->name('admin.key.document.index');
    Route::get('/admin/key/document/create', [KeyDocumentController::class, 'create'])->name('admin.key.document.create');
    Route::post('/admin/key/document', [KeyDocumentController::class, 'store'])->name('admin.key.document.store');
    Route::get('/admin/key/document/{id}', [KeyDocumentController::class, 'edit'])->name('admin.key.document.edit');
    Route::put('/admin/key/document/{id}', [KeyDocumentController::class, 'update'])->name('admin.key.document.update');
    Route::delete('/admin/key/document/{id}', [KeyDocumentController::class, 'destroy'])->name('admin.key.document.destroy');


    //Membership  create Routes
    Route::get('/admin/membership', [MembershipController::class, 'index'])->name('admin.membership.index');
    Route::get('/admin/membership/create', [MembershipController::class, 'create'])->name('admin.membership.create');
    Route::post('/admin/membership', [MembershipController::class, 'store'])->name('admin.membership.store');
    Route::get('/admin/membership/{id}', [MembershipController::class, 'edit'])->name('admin.membership.edit');
    Route::put('/admin/membership/{id}', [MembershipController::class, 'update'])->name('admin.membership.update');
    Route::delete('/admin/membership/{id}', [MembershipController::class, 'destroy'])->name('admin.membership.destroy');
    Route::get('/admin/membership/status/{id}', [MembershipController::class, 'status'])->name('admin.membership.status');


    /* ======= CMS Start================*/
    Route::prefix('admin/cms/home/banner')->name('admin.cms.home.banner.')->controller(BannerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });
    Route::prefix('admin/cms/home/about')->name('admin.cms.home.about.')->controller(AboutController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update/content', 'updateContent')->name('update.content');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/list/{id}', 'update')->name('update.list');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('admin/cms/home/core-publication')->name('admin.cms.home.core.publication.')->controller(\App\Http\Controllers\Web\Backend\CMS\Home\PublicationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });

    Route::prefix('admin/cms/home/presiding/council')->name('admin.cms.home.presiding.council.')->controller(PresidingCouncilController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });
    Route::prefix('admin/cms/home/donation')->name('admin.cms.home.donation.')->controller(DonationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });
    Route::prefix('admin/cms/home/how/join/group')->name('admin.cms.home.how.join.group.')->controller(HowToJoinTheGroupController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update/content', 'updateContent')->name('update.content');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/list/{id}', 'update')->name('update.list');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('admin/cms/home/history')->name('admin.cms.home.history.')->controller(HistoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update/content', 'updateContent')->name('update.content');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/list/{id}', 'update')->name('update.list');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('admin/cms/key/document/banner')->name('admin.cms.key.document.banner.')->controller(\App\Http\Controllers\Web\Backend\CMS\KeyDocument\BannerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });

    Route::prefix('admin/cms/contact/banner')->name('admin.cms.contact.banner.')->controller(\App\Http\Controllers\Web\Backend\CMS\Contact\BannerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });

    Route::prefix('admin/cms/presiding/council/banner')->name('admin.cms.presiding.council.banner.')->controller(\App\Http\Controllers\Web\Backend\CMS\PresendingCouncil\BannerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });

    Route::prefix('admin/cms/presiding/council/about')->name('admin.cms.presiding.council.about.')->controller(\App\Http\Controllers\Web\Backend\CMS\PresendingCouncil\AboutController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });
    //default membership Routes
    Route::prefix('admin/cms/default/membership/article')->name('admin.cms.default.membership.article.')->controller(DefaultMembershipArticleController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });
    Route::prefix('admin/cms/membership')->name('admin.cms.membership.')->controller(\App\Http\Controllers\Web\Backend\CMS\Membership\MembershipController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/list/{id}', 'update')->name('update.list');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    });

    //Terms && condition
    Route::controller(TermsAndConditionAndPrivacyPolicyController::class)->prefix('admin/')->name('admin.')->group(function () {
        Route::get('/terms/conditions', 'termsAndCondition')->name('terms.condition.index');
        Route::post('/terms-and-condition/update', 'update')->name('terms.condition.update');

        Route::get('/privacy-policy', 'privacyPolicy')->name('privacy.policy.index');
        Route::post('/privacy-policy/update', 'updatePrivacyPolicy')->name('privacy.policy.update');
    })->middleware(['auth']);
});



Route::get('success/payment', [PaypalController::class, 'success'])->name('payment.success');
Route::get('cancel/payment', [PaypalController::class, 'cancel'])->name('payment.cancel');

Route::get('success/donation', [DonationPaymentController::class, 'donationSuccess'])->name('donation.payment.success');
Route::get('cancel/donation', [DonationPaymentController::class, 'donationsCancel'])->name('donation.payment.cancel');

Route::post('/webhook/stripe', [DonationWithStripeController::class, 'handleWebhook']);

require __DIR__ . '/auth.php';
// require __DIR__.'/api.php';