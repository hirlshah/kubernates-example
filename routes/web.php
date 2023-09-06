<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Frontend\ProspectionVideoController;

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

Route::group(['middleware' => 'language'], function () {
    Route::get('/', function () {
        return redirect('/');
    });

    /**
     * Sso login route
     */
    Route::get('sso/login/{ssoToken}', [App\Http\Controllers\Auth\LoginController::class, 'ssoLogin'])->name('sso.login');

    Auth::routes(['verify' => true]);

    /**
     * Social login Route
     */
    Route::get('login/{provider}', 'App\Http\Controllers\Auth\LoginController@redirect');
    Route::get('login/{provider}/callback', 'App\Http\Controllers\Auth\LoginController@Callback');

    Route::get('validate-register-fields', [App\Http\Controllers\Auth\RegisterController::class, 'validateRegisterFields'])->name('validate.register.fields');

    Route::group(['prefix' => 'stripe'], function () {
        Route::post('/webhook', [App\Http\Controllers\StripeController::class, 'stripeWebhook'])->name('stripe.webhook');
    });

    Route::get('/get-user-list', [App\Http\Controllers\CommonController::class, 'getPeopleList'])->name('get-user-list');

    /**
     * Frontend Route : Start
     */
    Route::get('/', [App\Http\Controllers\Frontend\FrontendController::class, 'index'])->name('home');
    Route::post('/visiter-video-form', [App\Http\Controllers\Frontend\ProspectionVideoController::class, 'store'])->name('frontend.video.visiter.form');
    Route::post('/visiter-video-visit', [App\Http\Controllers\Frontend\ProspectionVideoController::class, 'sendVideoVisiterMail'])->name('visiter.video.visit');

    Route::get('/pr-video/{slug}', [App\Http\Controllers\Frontend\ProspectionVideoController::class, 'index'])->name('prospection.slug');

    Route::get('prospection/{slug}/{video_visiter_table_id}/{referral}/survey', [App\Http\Controllers\Frontend\ProspectionVideoController::class, 'survey'])->name('frontend.prospection.survey');
    Route::post('prospection/{slug}/survey', [App\Http\Controllers\Frontend\ProspectionVideoController::class, 'saveProspectionSurvey'])->name('frontend.prospection.survey.store');

    Route::get('/prospection-survey-thank-you', [App\Http\Controllers\Frontend\ProspectionVideoController::class, 'prospectionSurveyThankyou'])->name('frontend.prospection.survey.thankyou');

    Route::get('/register/success', [App\Http\Controllers\Auth\RegisterController::class, 'registerSuccess'])->name('register.success');
    Route::get('/register/thank-you', [App\Http\Controllers\Auth\RegisterController::class, 'registerThankYou'])->name('register.thank-you');
    Route::get('/plans', [App\Http\Controllers\Frontend\FrontendController::class, 'plans'])->name('plans');
    Route::get('/{slug}/survey', [App\Http\Controllers\Frontend\SurveyController::class, 'survey'])->name('frontend.survey');
    Route::post('/{slug}/survey', [App\Http\Controllers\Frontend\SurveyController::class, 'saveSurvey'])->name('frontend.survey.store');
    Route::get('/events', [App\Http\Controllers\Frontend\EventController::class, 'index']);
    Route::get('/events/{slug}', [App\Http\Controllers\Frontend\EventController::class, 'eventDetails'])->name('frontend.event.details');
    Route::get('/events/register-auth-user/{id}', [App\Http\Controllers\Frontend\EventController::class, 'registerAuthUser'])->name('register.auth.user');
    Route::post('/events/store-contacts', [App\Http\Controllers\Frontend\EventController::class, 'storeContacts'])->name('frontend.store.contacts');
    Route::post('/event-date/{id}', [App\Http\Controllers\Frontend\EventController::class, 'getEventDate'])->name('get.event.date');
    Route::post('/contact', [App\Http\Controllers\Frontend\ContactUsFormController::class, 'ContactUsForm'])->name('frontend.contact.store');
    Route::get('document/{document}/download', [App\Http\Controllers\Frontend\EventController::class, 'downloadDocument'])->name('frontend.document-download');
    Route::get('video/{video}/download', [App\Http\Controllers\Frontend\EventController::class, 'downloadVideo'])->name('frontend.video-download');

    /**
     * Seller Route : Start
     */
    Route::post('/add-plan', [App\Http\Controllers\Seller\SettingController::class, 'createPlan'])->name('seller.plan.create');
    Route::get('/plan/payment/confirmation/{planId}/{userId}/{stripePaymentIntentId}', [App\Http\Controllers\Frontend\FrontendController::class, 'planPaymentConfirmation'])->name('plan.payment.confirmation');
     
    Route::group(['prefix' => 'seller', 'middleware' => 'check_plan_permission'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('seller-dashboard');
        Route::get('/dashboard/follow-ups', [App\Http\Controllers\Seller\DashboardController::class, 'getFollowUps'])->name('seller.dashboard.follow_ups');
        Route::get('/zoom-status/{id}', [App\Http\Controllers\Seller\DashboardController::class, 'zoomStatus'])->name('seller.zoom-status');

        Route::get('/my-profile', [App\Http\Controllers\Seller\MemberController::class, 'myProfile'])->name('my-profile');
        Route::get('/member-searched-people', [App\Http\Controllers\Seller\MemberController::class, 'getPeopleList'])->name('seller.member.searched-people');
        Route::post('/profile', [App\Http\Controllers\Seller\MemberController::class, 'profileUpdate'])->name('seller.update-profile');
        Route::post('/update-profile-photo', [App\Http\Controllers\Seller\MemberController::class, 'profilePhotoUpdate'])->name('seller.update-profile-photo');
        Route::post('/update-profile-info', [App\Http\Controllers\Seller\MemberController::class, 'profileInfoUpdate'])->name('seller.update-profile-info');
        Route::get('/events/one-on-one-event', [App\Http\Controllers\Seller\EventController::class, 'oneOnOneCall'])->name('oneOnOneCall');

        Route::get('/analytics-ajax-search', [App\Http\Controllers\Seller\AnalyticController::class, 'eventDataAjax'])->name('analytics.ajax.search');
        Route::get('/analytics-chart-data', [App\Http\Controllers\Seller\AnalyticController::class, 'analyticsData'])->name('analytics.chart.data');
        Route::get('/analytics-panel-data', [App\Http\Controllers\Seller\AnalyticController::class, 'analyticsPanelData'])->name('analytics.panel.data');
        Route::get('/analytics-column-data', [App\Http\Controllers\Seller\AnalyticController::class, 'columnsData'])->name('analytics.column.data');
        Route::get('/analytics-column-contacts', [App\Http\Controllers\Seller\AnalyticController::class, 'columnContactsData'])->name('analytics.column.contacts');
        Route::get('/analytics-personal-stats', [App\Http\Controllers\Seller\AnalyticController::class, 'personalContactStats'])->name('analytics.personal.stats');
        Route::get('/analytics-team-stats', [App\Http\Controllers\Seller\AnalyticController::class, 'teamContactStats'])->name('analytics.team.stats');

        Route::get('/analytics', [App\Http\Controllers\Seller\AnalyticController::class, 'index'])->name('analytics');
        Route::get('/analytics-data', [App\Http\Controllers\Seller\AnalyticController::class, 'getData'])->name('analytics.data');
        Route::get('/chart-analytics-data', [App\Http\Controllers\Seller\AnalyticController::class, 'chartData'])->name('chart.analytics.data');
        Route::get('/get-chart-analytics-data', [App\Http\Controllers\Seller\AnalyticController::class, 'getChartData'])->name('get.chart.analytics.data');
        Route::get('/get-zoom-meetings', [App\Http\Controllers\Seller\AnalyticController::class, 'getZoomMeetings'])->name('get.zooming.meetings');
        Route::get('/get-member-statistics', [App\Http\Controllers\Seller\AnalyticController::class, 'getAnalyticsMemberStats'])->name('get.member.statistics');
        Route::get('/update-statistics-flag', [App\Http\Controllers\Seller\AnalyticController::class, 'updateStatisticsFlag'])->name('update.statistics.flag');

        /**
         * Members Routes
         */
        Route::get('/my-profile', [App\Http\Controllers\Seller\MemberController::class, 'myProfile'])->name('my-profile');
        Route::get('/member-profile/{id}', [App\Http\Controllers\Seller\MemberController::class, 'memberProfile'])->name('seller.member.profile');
        Route::post('/member/update-parent', [App\Http\Controllers\Seller\MemberController::class, 'updateParent'])->name('seller.member.update-parent');
        Route::get('/events', [App\Http\Controllers\Seller\EventController::class, 'index'])->name('events');
        Route::get('/events/{slug}', [App\Http\Controllers\Seller\EventController::class, 'eventDetail'])->name('event-detail');
        Route::get('/dashboard-member-personal-stats', [App\Http\Controllers\Seller\DashboardController::class, 'getMemberStats'])->name('dashboard-member-personal-stats');

        Route::get('/dashboard-member-team-stats', [App\Http\Controllers\Seller\DashboardController::class, 'getMemberTeamStats'])->name('dashboard-member-team-stats');
        
        Route::group(['prefix' => 'members'], function () {
            Route::get('/', [App\Http\Controllers\Seller\DashboardController::class, 'members'])->name('seller.members');
            Route::get('/members-tree-data', [App\Http\Controllers\Seller\DashboardController::class, 'membersTreeData'])->name('seller.members.tree.data');
            Route::post('/add-member', [App\Http\Controllers\Seller\MemberController::class, 'addMember'])->name('seller.add-member');
            Route::get('/stats', [App\Http\Controllers\Seller\MemberController::class, 'stats'])->name('seller.member.stats');
            Route::post('/message-sent-stats', [App\Http\Controllers\Seller\MemberController::class, 'getMessageSentStat'])->name('seller.member.message-sent-stats');
            Route::post('/new-customer-stats', [App\Http\Controllers\Seller\MemberController::class, 'getNewCustomerStat'])->name('seller.member.new-customer-stats');
            Route::post('/new-distributor-stats', [App\Http\Controllers\Seller\MemberController::class, 'getNewDistributorStat'])->name('seller.member.new-distributor-stats');

            Route::post('/add-favourite', [App\Http\Controllers\Seller\MemberController::class, 'addFavourite'])->name('seller.member.add-favourite');
            Route::post('/remove-favourite', [App\Http\Controllers\Seller\MemberController::class, 'removeFavourite'])->name('seller.member.remove-favourite');
        });

        Route::post('/events/create', [App\Http\Controllers\Seller\EventController::class, 'create'])->name('seller.event.create');
        Route::post('/events/store', [App\Http\Controllers\Seller\EventController::class, 'store'])->name('seller.event.store');
        Route::patch('/events/update/{id}', [App\Http\Controllers\Seller\EventController::class, 'update'])->name('seller.event.update');
        Route::get('/events/show/{id}', [App\Http\Controllers\Seller\EventController::class, 'show'])->name('seller.event.show');
        Route::delete('/event-delete/{id}', [App\Http\Controllers\Seller\EventController::class, 'destroy'])->name('seller.event.destroy');
        Route::post('/events/status', [App\Http\Controllers\Seller\EventController::class, 'statusChanges'])->name('seller.event.status');
        Route::get('/events/ics/download/{id}', [App\Http\Controllers\Seller\EventController::class, 'icsDownload'])->name('seller.event.download-ics');
        Route::get('/events/confirm-presence/{event}', [App\Http\Controllers\Seller\EventController::class, 'confirmPresence'])->name('seller.event.confirm-presence');
        Route::get('/survey/create', [App\Http\Controllers\Seller\SurveyController::class, 'create'])->name('survey.create');
        Route::post('/survey/store', [App\Http\Controllers\Seller\SurveyController::class, 'store'])->name('survey.store');
        Route::get('/survey/get-list', [App\Http\Controllers\Seller\SurveyController::class, 'getList'])->name('survey.getList');

        Route::post('/add-card', [App\Http\Controllers\Seller\SettingController::class, 'cardAdd'])->name('seller.add-card');
        Route::post('/activate-card/{card}', [App\Http\Controllers\Seller\SettingController::class, 'activateCard'])->name('seller.card.activate');
        Route::post('/plan/update', [App\Http\Controllers\Seller\SettingController::class, 'updatePlan'])->name('seller.plan.update');
        Route::get('/plan/give-free-month', [App\Http\Controllers\Seller\SettingController::class, 'freeMonthPlan'])->name('seller.plan.free-month-plan');
        Route::post('/plan/cancel', [App\Http\Controllers\Seller\SettingController::class, 'cancelPlan'])->name('seller.plan.cancel');
        Route::post('/coupon/validate', [App\Http\Controllers\Seller\SettingController::class, 'validateCoupon'])->name('seller.coupon.validate');
        Route::get('/show-banner-video', [App\Http\Controllers\Seller\SettingController::class, 'showBannerVideo'])->name('show.banner.video');

        // Not used for now - start
        Route::post('/add-education', [App\Http\Controllers\Seller\MemberController::class, 'addEducation'])->name('seller.profile.add-education');
        Route::post('/delete-education/{id}', [App\Http\Controllers\Seller\MemberController::class, 'deleteEducation'])->name('seller.profile.delete-education');

        Route::post('/add-experience', [App\Http\Controllers\Seller\MemberController::class, 'addExperience'])->name('seller.profile.add-experience');
        Route::post('/delete-experience/{id}', [App\Http\Controllers\Seller\MemberController::class, 'deleteExperience'])->name('seller.profile.delete-experience');
        // Not used for now - end

        /**
         * Contacts Routes
         */
        Route::get('/contacts', [App\Http\Controllers\Seller\ContactController::class, 'board'])->name('seller.contacts.board');
        Route::get('/contacts/get-contact-board-data', [App\Http\Controllers\Seller\ContactController::class, 'getContactBoardData'])->name('seller.contacts.board.get-contact-board-data');
        Route::get('/contacts/status/{id}', [App\Http\Controllers\Seller\ContactController::class, 'getBoardStatusData'])->name('seller.contacts.board.status-data');
        Route::get('/contacts/filter', [App\Http\Controllers\Seller\ContactController::class, 'boardFilter'])->name('seller.contacts.board.filter');
        Route::post('/contacts/store', [App\Http\Controllers\Seller\ContactController::class, 'store'])->name('seller.contacts.store');
        Route::post('/contacts/update-status', [App\Http\Controllers\Seller\ContactController::class, 'updateStatus'])->name('seller.contacts.update-status');
        Route::get('/contacts/{id}', [App\Http\Controllers\Seller\ContactController::class, 'show'])->name('seller.contacts.show');
        Route::post('/contacts/update/{id}', [App\Http\Controllers\Seller\ContactController::class, 'update'])->name('seller.contacts.update');
        Route::delete('/contacts/{id}', [App\Http\Controllers\Seller\ContactController::class, 'destroy'])->name('seller.contacts.destroy');
        Route::post('/contacts/send-message', [App\Http\Controllers\Seller\ContactController::class, 'sendMessage'])->name('seller.contacts.send-message');
        Route::post('/contacts/follow-up', [App\Http\Controllers\Seller\ContactController::class, 'followUp'])->name('seller.contacts.follow-up');
        Route::get('/contacts/follow-up-date/{id}', [App\Http\Controllers\Seller\ContactController::class, 'getFollowUpDate']);
	    Route::post('/contact-labels-update/{id}', [App\Http\Controllers\Seller\ContactController::class, 'contactLabelsUpdate'])->name('seller.contacts.labels.update');

        Route::post('/contacts-import', [App\Http\Controllers\Seller\ContactController::class, 'uploadContacts'])->name('seller.contacts.upload');
        Route::post('/contacts-read-upload-data', [App\Http\Controllers\Seller\ContactController::class, 'readContactsUploadData'])->name('seller.contacts.read.upload-data');
        Route::post('/openai/generate/message', [App\Http\Controllers\Seller\ContactController::class, 'generateAiMessage'])->name('generate-ai-message');
        Route::get('/openai/models', [App\Http\Controllers\Seller\ContactController::class, 'getAiModels'])->name('openai.models');

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/account', [App\Http\Controllers\Seller\SettingController::class, 'account'])->name('seller.setting.account');
            Route::get('/notifications', [App\Http\Controllers\Seller\SettingController::class, 'notification'])->name('seller.setting.notification');
            Route::post('/save-notifications/{id}', [App\Http\Controllers\Seller\SettingController::class, 'saveNotification'])->name('seller.setting.savenotification');
            Route::get('/terms-and-policy', [App\Http\Controllers\Seller\SettingController::class, 'termsAndPolicy'])->name('seller.setting.terms-and-policy');
            Route::get('/my-subscription', [App\Http\Controllers\Seller\SettingController::class, 'mySubscription'])->name('seller.setting.my-subscription');
            Route::patch('/account/update/{id}', [App\Http\Controllers\Seller\SettingController::class, 'accountUpdate'])->name('seller.setting.account-update');
            Route::get('/account/delete-photo', [App\Http\Controllers\Seller\SettingController::class, 'deleteImage'])->name('seller.setting.delete-photo');
        });

        Route::post('/tag/store', [App\Http\Controllers\Seller\TagController::class, 'store'])->name('tag.store');
        Route::get('/tag/get-list', [App\Http\Controllers\Seller\TagController::class, 'getList'])->name('tag.get-list');

        Route::post('/categories/store', [App\Http\Controllers\Seller\CategoryController::class, 'store'])->name('seller.category.store');
        Route::post('/categories/update/{id}', [App\Http\Controllers\Seller\CategoryController::class, 'update'])->name('seller.category.update');
        Route::get('/categories/show/{id}', [App\Http\Controllers\Seller\CategoryController::class, 'show'])->name('seller.category.show');
        Route::delete('/categories/{id}', [App\Http\Controllers\Seller\CategoryController::class, 'destroy'])->name('seller.category.destroy');
        Route::post('/categories/subcategorystore', [App\Http\Controllers\Seller\CategoryController::class,'subCategoryStore'])->name('seller.category.subCategoryStore');
        Route::get('/categories/subcategory', [App\Http\Controllers\Seller\CategoryController::class,'subCategoryShow'])->name('seller.category.sub-categories');
        Route::delete('/delete/subcategory/{type}/{id}', [App\Http\Controllers\Seller\CategoryController::class, 'destroySubCategory'])->name('seller.category.destroy-subCategory');
        Route::post('/sub-category/update/{id}', [App\Http\Controllers\Seller\CategoryController::class, 'updateSubCategory'])->name('seller.sub-category.update');
        /**
         * Video Route
         */
        Route::resource('/videos', App\Http\Controllers\Seller\VideoController::class);
        Route::get('video/{video}/download', ['as' => 'video-download', 'uses' => 'App\Http\Controllers\Seller\VideoController@downloadVideo']);

        Route::get('/video-completed/{video}', ['as' => 'seller.add-video-completed', 'uses' => 'App\Http\Controllers\Seller\VideoController@addVideoCompleted']);

        Route::post('video/drag-drop', ['as' => 'video-drag-drop', 'uses' => 'App\Http\Controllers\Seller\VideoController@dragDropVideo']);
        Route::post('category/drag-drop', ['as' => 'category-drag-drop', 'uses' => 'App\Http\Controllers\Seller\VideoController@dragDropCategory']);
        
        Route::get('/video/{id}', [App\Http\Controllers\Seller\VideoController::class,'videoDetail'])->name('seller.video-detail');

        /**
         * Document Routes
         */
        Route::resource('documents', App\Http\Controllers\Seller\DocumentController::class);
        Route::get('document/{document}/download', ['as' => 'document-download', 'uses' => 'App\Http\Controllers\Seller\DocumentController@downloadDocument']);

        /**
         * Leaderboard Route
         */
        Route::get('/leaderboard', [App\Http\Controllers\Seller\LeaderboardController::class, 'index'])->name('seller-leaderboard');
        Route::get('/leaderboard-data/presentation-given', [App\Http\Controllers\Seller\LeaderboardController::class, 'presentationGivenData'])->name('selle.leaderboard-stats.presentation-given');
        Route::get('/leaderboard-data/customer-acquisition', [App\Http\Controllers\Seller\LeaderboardController::class, 'customerAcquisitionData'])->name('seller.leaderboard-stats.customer-acquisition');
        Route::get('/leaderboard-data/distributor-acquisition', [App\Http\Controllers\Seller\LeaderboardController::class, 'distributorAcquisitionData'])->name('seller.leaderboard-stats.distributor_acquisition');
        Route::get('/leaderboard-data/presentations', [App\Http\Controllers\Seller\LeaderboardController::class, 'presentationsData'])->name('seller.leaderboard-stats.presentations');
        Route::get('/leaderboard-data/message_sent', [App\Http\Controllers\Seller\LeaderboardController::class, 'messageSentData'])->name('seller.leaderboard-stats.message_sent');
        Route::get('/leaderboard-data/present_at_zoom', [App\Http\Controllers\Seller\LeaderboardController::class, 'presentAtZoomData'])->name('seller.leaderboard-stats.present_at_zoom');

	    Route::group(['prefix' => 'dailies'], function () {
		    Route::post('/store', [App\Http\Controllers\Seller\TaskController::class, 'store'])->name('seller.tasks.store');
		    Route::get('/list', [App\Http\Controllers\Seller\TaskController::class, 'getList'])->name('seller.tasks.list');
            Route::get('/get-task-data', [App\Http\Controllers\Seller\TaskController::class, 'taskData'])->name('seller.tasks');
		    Route::post('/user-task-update', [App\Http\Controllers\Seller\TaskController::class, 'userTaskUpdate'])->name('seller.tasks.user-task-update');
            Route::get('/completed-task-dates', [App\Http\Controllers\Seller\TaskController::class, 'getCompletedTaskDates'])->name('seller.tasks.completed-task-dates');
	    });

	    /**
	     * Task Route
	     */
        Route::get('/task-board', [App\Http\Controllers\Seller\TrelloController::class, 'index'])->name('seller.trello-boards');
	    Route::get('/task-board/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'detail'])->name('seller-task-board');
        Route::get('/user-task-board-stats/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'userTaskBoardStats'])->name('seller-user-task-board-stats');
        Route::get('/trello-board/status/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'getStatusColumnData'])->name('seller.trello-board.status-column-data');
        Route::get('/get-trello-board-category-list', [App\Http\Controllers\Seller\TrelloController::class, 'getTrelloBoardCategoryList'])->name('seller.trello-board-categories');
        Route::get('/get-people-list', [App\Http\Controllers\Seller\TrelloController::class, 'getPeopleList'])->name('seller.people-list');
        Route::get('/get-trello-task-details', [App\Http\Controllers\Seller\TrelloController::class, 'getTrelloTaskDetails'])->name('seller.get-trello-task-details');
        Route::get('/get-trello-task-comments', [App\Http\Controllers\Seller\TrelloController::class, 'getTrelloTaskComments'])->name('seller.get-trello-task-comments');
        Route::get('/delete-trello-task-attachment', [App\Http\Controllers\Seller\TrelloController::class, 'deleteTrelloTaskAttachment'])->name('seller.trello-board.task.delete-attachment');
        Route::get('/trello-board-id-store', [App\Http\Controllers\Seller\TrelloController::class, 'trelloBoardIdStoreScript'])->name('seller-trello-board-id');
        Route::post('/trello-board-add', [App\Http\Controllers\Seller\TrelloController::class, 'addTrelloBoard'])->name('seller.add-trello-board');
        Route::post('/trello-board-update', [App\Http\Controllers\Seller\TrelloController::class, 'updateTrelloBoard'])->name('seller.update-trello-board');
        Route::post('/add-trello-task-comment', [App\Http\Controllers\Seller\TrelloController::class, 'addTrelloTaskComment'])->name('seller.add-trello-task-comment');
        Route::post('/add-trello-board-category', [App\Http\Controllers\Seller\TrelloController::class, 'addTrelloBoardCategory'])->name('seller.add-trello-board-category');
        Route::post('/add-people-to-trello-board', [App\Http\Controllers\Seller\TrelloController::class, 'addPeopleToTrelloBoard'])->name('seller.add-people-to-trello-board');
	    Route::post('/add-trello-task', [App\Http\Controllers\Seller\TrelloController::class, 'addTrelloTask'])->name('seller.trello-task-store');
	    Route::post('/task-update-event', [App\Http\Controllers\Seller\TrelloController::class, 'taskUpdateEvent'])->name('seller.task.update.event');
	    Route::delete('/task/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'destroyTrelloTask'])->name('seller.task.destroy');
	    Route::post('/add-trello-status', [App\Http\Controllers\Seller\TrelloController::class, 'addTrelloStatus'])->name('seller.add-trello-status');
	    Route::post('/trello-task-update/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'trelloTaskUpdate'])->name('seller.trello-task-update');
	    Route::get('/trello-status-edit/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'editTrelloStatus'])->name('seller.edit-trello-status');
	    Route::post('/trello-status-update/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'updateTrelloStatus'])->name('seller.trello-status-update');
	    Route::delete('/destroy-trello-status/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'destroyTrelloStatus'])->name('seller.destroy-trello-status');
	    Route::post('/task-status-update-event', [App\Http\Controllers\Seller\TrelloController::class, 'taskStatusUpdateEvent'])->name('seller.task.status.update.event');
        Route::delete('/trello-board/{id}', [App\Http\Controllers\Seller\TrelloController::class, 'destroy'])->name('seller.trello-board.destroy');

	    /**
	     * label Route
	     */
	    Route::get('/labels/{id}/{type}', [App\Http\Controllers\Seller\LabelController::class, 'index'])->name('seller.label.list');
	    Route::post('/label-add', [App\Http\Controllers\Seller\LabelController::class, 'store'])->name('seller.label.store');
	    Route::post('/label-update/{id}', [App\Http\Controllers\Seller\LabelController::class, 'update'])->name('seller.label.update');
	    Route::get('/label-data/{id}', [App\Http\Controllers\Seller\LabelController::class, 'show'])->name('seller.label.show');
        Route::get('/label-destroy/{id}', [App\Http\Controllers\Seller\LabelController::class, 'destroy'])->name('seller.label.destroy');

        /**
	     * help Route
	     */
        Route::get('/help', [App\Http\Controllers\Seller\HelpController::class, 'index'])->name('seller.helps.index');

        /**
	     * Prospection Video Route
	     */
        Route::resource('/prospection', App\Http\Controllers\Seller\ProspectionVideoController::class);
        Route::delete('/prospection-delete/{id}', [App\Http\Controllers\Seller\ProspectionVideoController::class, 'destroy'])->name('prospection.video.destroy');
        Route::get('/prospection/{slug}/analytics', [App\Http\Controllers\Seller\ProspectionVideoController::class,'analyticsData'])->name('prospection.analytics');
        Route::get('/prospection-people-view/{slug}/{id}', [App\Http\Controllers\Seller\ProspectionVideoController::class,'analyticsProspectionVisitors'])->name('prospection.analytics.people-view');
        Route::get('/prospection-visitor-stats/{slug}/{id}', [App\Http\Controllers\Seller\ProspectionVideoController::class,'videoVisitorsUserStatistics'])->name('prospection.analytics.visitor-stats');
        Route::get('prospection-full-view-chart-data', [App\Http\Controllers\Seller\ProspectionVideoController::class,'prospectionFullViewGraphData'])->name('prospection-full-view-graph');
        Route::get('prospection-partial-view-chart-data', [App\Http\Controllers\Seller\ProspectionVideoController::class,'prospectionPartialViewGraphData'])->name('prospection-partial-view-graph');
        Route::get('prospection-not-played-chart-data', [App\Http\Controllers\Seller\ProspectionVideoController::class,'prospectionNotPlayedGraphData'])->name('prospection-not-played-graph');

        Route::get('/prospection-survey/{id}', [App\Http\Controllers\Seller\ProspectionVideoController::class,'getSurveyData'])->name('prospection.survey.question-answer');
        Route::post('/prospection-survey-update', [App\Http\Controllers\Seller\SurveyController::class,'update'])->name('prospection.survey.update');
    });

    /**
     * Admin Route : Start
     */
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin-dashboard');

        /**
         * User Routes
         */
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::get('users-data', ['as' => 'users.data', 'uses' => 'App\Http\Controllers\Admin\UserController@getData']);
        Route::get('unverified-users', ['as' => 'users.unverified', 'uses' => 'App\Http\Controllers\Admin\UserController@unverifiedUserView']);
        Route::get('unverified-users-data', ['as' => 'users.unverified.data', 'uses' => 'App\Http\Controllers\Admin\UserController@getunVerifiedUserData']);
        Route::get('verified-user/{id}', ['as' => 'users.verified', 'uses' => 'App\Http\Controllers\Admin\UserController@setVerifiedUser']);
        Route::get('/import', [App\Http\Controllers\Admin\UserController::class, 'importForm'])->name('import-form');
        Route::post('/users-import', [App\Http\Controllers\Admin\UserController::class, 'importUsers'])->name('admin.users.import');

        /**
         * Role Routes
         */
        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
        Route::get('roles-data', ['as' => 'roles.data', 'uses' => 'App\Http\Controllers\Admin\RoleController@getData']);

        /**
         * Event Routes
         */
        Route::resource('events', App\Http\Controllers\Admin\EventController::class);
        Route::get('events-data', ['as' => 'events.data', 'uses' => 'App\Http\Controllers\Admin\EventController@getData']);

        /**
         * Coupon Routes
         */
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
        Route::get('coupons-data', ['as' => 'coupons.data', 'uses' => 'App\Http\Controllers\Admin\CouponController@getData']);

        /**
         * Page Routes
         */
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
        Route::get('pages-data', ['as' => 'pages.data', 'uses' => 'App\Http\Controllers\Admin\PageController@getData']);

        /**
	     * help Route
	     */
        Route::get('/helps', [App\Http\Controllers\Admin\HelpController::class, 'index'])->name('admin.helps.index');
        Route::get('/helps/create', [App\Http\Controllers\Admin\HelpController::class, 'create'])->name('admin.helps.create_update');
        Route::post('/helps/store', [App\Http\Controllers\Admin\HelpController::class, 'store'])->name('admin.helps.store');
        Route::get('helps-data', ['as' => 'helps.data', 'uses' => 'App\Http\Controllers\Admin\HelpController@getData']);


        Route::group(['prefix' => 'helps/{help}'],function(){
            Route::get('/edit', [App\Http\Controllers\Admin\HelpController::class, 'edit'])->name('admin.helps.edit');
            Route::get('/show', [App\Http\Controllers\Admin\HelpController::class, 'show'])->name('admin.helps.show');
            Route::patch('/update', [App\Http\Controllers\Admin\HelpController::class, 'update'])->name('admin.helps.update');
            Route::delete('/', [App\Http\Controllers\Admin\HelpController::class, 'destroy'])->name('admin.helps.destroy');
        });
    });

    Route::get('language/{locale}', function ($locale) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
        if(Auth::check()) {
            $user = User::find(Auth::user()->id);
            $user->lang = $locale;
            $user->update();
        }
        return response()->json(['success' => true], 200);
    });
    

});
$undefinedVariable = 'This will trigger an error';
