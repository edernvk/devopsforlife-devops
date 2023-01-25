<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'approved-only'])->namespace('Api')->group(function () {
    Route::get('/navigation', 'NavigationController');

    Route::get('/users/all', 'UserController@all');
    Route::get('/users/birthday', 'UserController@birthday');
    Route::get('/users/all-collection', 'UserController@allCollection');
    Route::get('/users/alert', 'UserController@getUsersAlert');
    Route::get('/users/byteam/{id}', 'UserController@allByTeams');
    Route::put('/users/{id}/approve', 'UserController@approve');
    Route::put('/users/{id}/disapprove', "UserController@disapprove");
    Route::put('/users/{id}/allow-terms', "UserController@checkTerms");
    Route::put('/users/{id}/update-password', 'UserController@updatePassword');
    Route::put('/users/{id}/avatar', 'UserController@avatar')->middleware('optimizeImages');
    Route::patch('/users/update-vcard/{users}', 'UserController@updateVcard');
    Route::resource('/users', 'UserController')->except('create', 'edit');

    Route::get('/teams/all', 'TeamController@all');
    Route::resource('/teams', 'TeamController')->except('create', 'edit', 'index');

    Route::get('/group/all', 'GroupController@all');
    Route::get('/group/{group}/user', 'GroupController@showGroups');
    Route::post('/group/{group}/user', 'GroupController@addUsers');
    Route::delete('/group/{group}/user/{user}', 'GroupController@deleteUsers');
    Route::delete('/group/delete-users', 'GroupController@deleteUsersInGroup');
    Route::apiResource('/group', 'GroupController');

    Route::get('/messages/inbox/unread/count', 'MessageController@inboxCount');
    Route::get('/messages/inbox/{id}/formatted/unread', 'MessageController@inboxFormattedUnread');
    Route::get('/messages/inbox/{id}/formatted/read', 'MessageController@inboxFormattedRead');
    Route::get('/messages/inbox/{id}/formatted', 'MessageController@inboxFormatted');
    Route::get('/messages/inbox/{id}', 'MessageController@inbox');
    Route::get('/messages/reads/{id}', 'MessageController@reads');
    Route::get('/messages/outbox/{id}', 'MessageController@outbox');
    Route::post('/messages/image/store', 'MessageController@imageStorage')->middleware('optimizeImages');
    Route::put('/messages/read/{id}', 'MessageController@read');
    Route::put('/messages/unread/{id}', 'MessageController@unread');
    Route::put('/messages/publish/{id}', 'MessageController@publish');
    Route::put('/messages/inactivate/{id}', 'MessageController@inactive');
    Route::post('/messages/groups', 'MessageController@storeMessageByGroup');
    Route::resource('/messages', 'MessageController')->except('create', 'edit');

    Route::resource('/roles', 'RoleController')->except('create', 'edit');

    Route::get('/files/user', 'FileController@getFilesToUser');
    Route::get('/files/{id}/user', 'FileController@getFilesByUser');

    Route::patch('/files/{id}/status', 'FileController@patchCheckFileExpired');
    Route::post('/files/upload', 'FileController@postUpload');
    Route::apiResource('/files', 'FileController');

    Route::apiResource('/sing-file', 'FileSignatureController')->only(['store', 'index']);

    Route::get('/folders/{id}/files', 'FolderController@getFolderWithFiles');
    Route::get('/folders/files', 'FolderController@getAllWithFiles');
    Route::get('/folders/user/{id}', 'FolderController@getAllUsersInFolder');
    Route::get('/folders/user/is-not/{id}', 'FolderController@getAllUsersIsNotInFolder');
    Route::post('/folders/user', 'FolderController@saveUsersInFolder');
    Route::delete('/folders/delete-users', 'FolderController@removeUserFromFolder');
    Route::apiResource('/folders', 'FolderController');

    Route::get('/analysis/get', 'AnalysisController@retrieve');
    Route::get('/analysis/get-data', 'AnalysisController@retrieveData');
    Route::get('/analysis/get-activity', 'AnalysisController@retrieveActivity');

    Route::get('/magazines/all', 'MagazineController@all');
    Route::get('/magazines/recent', 'MagazineController@recent');
    Route::post('/magazines/cover', 'MagazineController@cover')->middleware('optimizeImages');
    Route::resource('/magazines', 'MagazineController')->except('create', 'edit');

    Route::get('/newsletters/all', 'NewsletterController@all');
    Route::get('/newsletters/recent', 'NewsletterController@recent');
    Route::post('/newsletters/cover', 'NewsletterController@cover')->middleware('optimizeImages');
    Route::apiResource('/newsletters', 'NewsletterController');

    Route::get('/newsletters-news/all', 'NewsletterNewsController@all');
    Route::get('/newsletters-news/index', 'NewsletterNewsController@index');
    Route::get('/newsletters-news/recents', 'NewsletterNewsController@recent');
    Route::post('/newsletters-news/{id}/like', 'NewsletterNewsController@postLike');
    Route::delete('/newsletters-news/{id}/unlike-user/{idUser}', 'NewsletterNewsController@deleteLike');
    Route::get('/newsletters-news/{id}/users-like', 'NewsletterNewsController@getUsersLike');
    Route::get('/newsletters-news/{id}/findByUser/{idUser}', 'NewsletterNewsController@findByUserLike');
    Route::post('/newsletters-news/{id}/read', 'NewsletterNewsController@postRead');
    Route::get('/newsletters-news/{id}/users-read', 'NewsletterNewsController@getUsersRead');
    Route::get('/newsletters-news/{id}/users-unread', 'NewsletterNewsController@getUsersUnRead');
    Route::get('/newsletters-news/{id}/report', 'NewsletterNewsController@getReportNewsletter');
    Route::get('/newsletters-news/{id}/tax-opened', 'NewsletterNewsController@getTaxOpened');
    Route::get('/newsletters-news/{id}/report/reads', 'NewsletterNewsController@getUserReadView');
    Route::get('/newsletters-news/{id}/report/unreads', 'NewsletterNewsController@getUserUnreadView');
    Route::get('/newsletters-news/is-actives', 'NewsletterNewsController@getIsActives');
    Route::post('/newsletters-news/thumbnail/store', 'NewsletterNewsController@thumbnail')->middleware('optimizeImages');
    Route::post('/newsletters-news/image/store', 'NewsletterNewsController@imageStorage')->middleware('optimizeImages');
    Route::put('/newsletters-news/{id}/thumbnail/store', 'NewsletterNewsController@updateThumbnail')->middleware('optimizeImages');
    Route::patch('/newsletters-news/change-status/{id}', 'NewsletterNewsController@changeNewsletterStatus');
    Route::get('/newsletters-news/contrast', 'NewsletterNewsController@getContrast');
    Route::get('/newsletters-news/publisheds', 'NewsletterNewsController@getPublisheds');
    Route::apiResource('/newsletters-news', 'NewsletterNewsController');


    Route::get('/news/comment/all', 'CommentController@all');
    Route::get('/news/comment/show/{newsletters}', 'CommentController@showCommentNews');
    Route::patch('/news/comment/update-status/{newsletters}', 'CommentController@updateStatus');
    Route::post('/news/comment/delete-comment', 'CommentController@deleteCommentsInNewsletter');
    Route::patch('/news/comment/status-comment', 'CommentController@statusCommentsMark');
    Route::apiResource('/news/comment', 'CommentController');


    Route::post('/pushes/all', 'PushController@sendAll');
    Route::post('/pushes/users', 'PushController@sendExternalUser');
    Route::get('/pushes', 'PushController@index');

    Route::get('/codes/actives', 'IosCodeController@getCodeActive');
    Route::post('/codes', 'UserIosCodeTrackedController@useCodeIos');

    Route::get('/videocasts/all', 'VideocastController@all');
    Route::apiResource('/videocasts', 'VideocastController');
    Route::post('/videocasts/{id}/read', 'VideocastController@postRead');
    Route::get('/videocasts/{id}/users-read', 'VideocastController@getUsersRead');
    Route::get('/videocasts/{id}/users-unread', 'VideocastController@getUsersUnRead');
    Route::get('/videocasts/{id}/report', 'VideocastController@getReportVideoCast');
    Route::get('/videocasts/{id}/report/reads', 'VideocastController@getUserReadView');
    Route::get('/videocasts/{id}/report/unreads', 'VideocastController@getUserUnreadView');
    Route::get('/videocasts/{id}/findByUser/{idUser}', 'VideocastController@findByUserRead');

    Route::post('/videocasts/confirm', 'UserVideocastTrackedController@store');
    Route::get('/videocasts/confirmed/users/{id}', 'UserVideocastTrackedController@getPresencesByUser');
    Route::get('/videocasts/confirmed/videos/{id}', 'UserVideocastTrackedController@getPresencesByVideo');

    Route::get('/products/all', 'ProductController@getAll');
    Route::apiResource('/products', 'ProductController');

    Route::get('/managers/all', 'ManagerController@getAll');
    Route::get('/managers/{id}/cities-states', 'ManagerController@getManagersWithCitiesAndStates');
    Route::delete('/managers/{manager}/{city}', 'ManagerController@deleteCityFromManager');
    Route::apiResource('/managers', 'ManagerController');

    Route::post('/product-not-found', 'ProductNotFoundController@store');

    Route::prefix('extensions')->group(function () {
        Route::get('/divisions/all', 'ExtensionDivisionController@all');
        // public user-facing list endpoint
        Route::get('/divisions/numbers', 'ExtensionDivisionController@allDivisionsWithAreasAndNumbers');
        Route::get('/divisions/{division}/areas', 'ExtensionDivisionController@getAreas');
        Route::apiResource('/divisions', 'ExtensionDivisionController')->except('index');

        Route::get('/areas/all', 'ExtensionAreaController@all');
        Route::get('/areas/divisionless', 'ExtensionAreaController@getDivisionless');
        Route::apiResource('/areas', 'ExtensionAreaController')->except('index');

        Route::get('/numbers/all', 'ExtensionNumberController@all');
        Route::apiResource('/numbers', 'ExtensionNumberController')->except('index');
    });

    Route::prefix('partners')->group(function () {
        Route::get('/divisions/all', 'BenefitDivisionController@all');
        // public user-facing list endpoint
        Route::get('/divisions/benefits', 'BenefitDivisionController@allDivisionsWithAreasAndBenefits');
        Route::get('/divisions/{division}/areas', 'BenefitDivisionController@getAreas');
        Route::apiResource('/divisions', 'BenefitDivisionController')->except('index');

        Route::get('/areas/divisionless', 'BenefitAreaController@getDivisionless');
        Route::get('/areas/all', 'BenefitAreaController@all');
        Route::apiResource('/areas', 'BenefitAreaController')->except('index');

        Route::get('/benefits/all', 'BenefitController@all');
        Route::apiResource('/benefits', 'BenefitController')->except('index');
    });

    Route::get('/healthdocs/all/{id}', 'HealthDocsController@allByUser');
    Route::post('/healthdocs/pdf', 'HealthDocsController@storePdfDoc');
    Route::post('/healthdocs/load/pdf', 'HealthDocsController@storeLoadPdfDoc');
    Route::resource('/healthdocs', 'HealthDocsController')->only('store', 'index', 'destroy');

    Route::post('/reports/count-users', 'ReportController@countUsers');
    Route::post('/reports/user-access', 'ReportController@createUserAccess');
    Route::post('/reports/allow-terms-accepted/selected-users', 'AllowTermsAcceptedReportController@createSelectedUsers');
    Route::post('/reports/allow-terms-accepted/all', 'AllowTermsAcceptedReportController@createAllUsers');

    // 2020-11-17: MANAGED WITH APPLICATION SETTINGS APPROACH, ARE NOT MANAGED RESOURCES
    Route::get('/benefits/simplified', 'SimplifiedBenefitController@get');
    Route::post('/benefits/simplified', 'SimplifiedBenefitController@set');
    Route::post('/benefits/simplified/poster', 'SimplifiedBenefitController@poster')->middleware('optimizeImages');
    // END - MANAGED SETTINGS

    Route::get('/users/{user}/paycheck-access', 'PaycheckAccessController@show');
    Route::post('/users/{user}/paycheck-access', 'PaycheckAccessController@store');
    Route::put('/users/{user}/paycheck-access', 'PaycheckAccessController@update');
    Route::delete('/users/{user}/paycheck-access', 'PaycheckAccessController@destroy');

    Route::get('/users/{user}/christmas-token', 'ChristmasTokenController@show');

    //    2020-11-17: WHEN SHOULD WE DISABLE THOSE ENDPOINTS?
    //    2020-11-28: I THINK THIS WOULD HAVE BEEN MORE MEANINGFUL IF IT WAS '/me/active-ticket'
    //    2020-11-30: I REGRET THE DECISION TO USE IT AS '/me/xxxx-yyyy', IT IS NOT CONVENTIONED
    //    Route::get('/users/{user}/active-ticket', 'TicketController@activeTicket');
    //    Route::apiResource('/tickets', 'TicketController')->only('store');

    Route::prefix('campaigns')->group(function () {
        Route::get('vaccine', 'VaccineCampaignController@retrieve');
        Route::post('vaccine', 'VaccineCampaignController@store');

        Route::prefix('christmas-baskets')->group(function () {
            Route::get('/', 'ChristmasBasketController@retrieve');
            Route::post('/', 'ChristmasBasketController@store');
            Route::delete('/', 'ChristmasBasketController@remove');
        });

        Route::get('/burguesa-jacket', 'BurguesaJacketCampaignController@retrieve');
        Route::post('/burguesa-jacket', 'BurguesaJacketCampaignController@store');
        Route::delete('/burguesa-jacket', 'BurguesaJacketCampaignController@remove');

        //        2021-10-11: DISABLED ENDPOINTS
        //        Route::get('/vaccine-survey', 'VaccineSurveyCampaignController@retrieve');
        //        Route::post('/vaccine-survey', 'VaccineSurveyCampaignController@store');
        //        Route::delete('/vaccine-survey', 'VaccineSurveyCampaignController@remove');

        Route::prefix('drawing-contest')->group(function () {
            Route::get('/campaign-details/{slug}', 'DrawingContestController@getCampaignDetails');
            Route::get('/all', 'DrawingContestController@getAllChoices');
            Route::get('/choices', 'DrawingContestController@getChoices');
            Route::get('/stage/{campaignStage}/user-votes/{user}', 'DrawingContestController@getUserVotes');
            Route::post('/votes', 'DrawingContestController@saveVote');
        });

        Route::prefix('cup-shirt')->group(function () {
            Route::get('/products', 'CupShirtCampaingController@retrieveProducts');
            Route::get('/', 'CupShirtCampaingController@retrieve');
            Route::post('/', 'CupShirtCampaingController@saveCampaing');
        });

        Route::get('/{slug}', 'CampaignController@getCampaignDetails');
    });

    Route::prefix('bulk')->group(function () {
        // POST /api/bulk/extensions/areas
        Route::post('/extensions/areas', 'ExtensionAreaController@bulkStore');
        // POST /api/bulk/partners/areas
        Route::post('partners/areas', 'BenefitAreaController@bulkStore');

        //        2021-10-11: DISABLED ENDPOINT
        Route::post('/drawing-contest/votes', 'DrawingContestController@saveBatchVotes');
    });

    Route::prefix('quiz')->group(function () {
        Route::post('/', 'QuizController@store');
        Route::put('/', 'QuizController@update');
        Route::post('/edit', 'QuizController@edit');
        Route::post('/save-question', 'QuizController@saveOneQuestion');
        Route::put('/edit-question', 'QuizController@updateQuestion');
        Route::post('/save-option', 'QuizController@saveOneOption');
        Route::put('/edit-option', 'QuizController@updateOption');
        Route::get('/all', 'QuizController@getAll');
        Route::get('/get-question/{id}', 'QuizController@getOneQuestion');
        Route::get('/get-questions/{id}', 'QuizController@getQuestionsByQuizId');
        Route::get('/get-quiz/{id}', 'QuizController@getQuizById');
        Route::get('/complete-quiz/{slug}', 'QuizController@getCompleteQuizBySlug');
        Route::get('/get-options/{id}', 'QuizController@getOptionsByQuestionId');
        Route::get('/get-active-quiz', 'QuizController@getActiveQuiz');
        Route::get('/publish-quiz/{id}', 'QuizController@publishQuiz');
        Route::delete('/remove-option/{id}', 'QuizController@removeOption');
        Route::delete('/remove-question/{id}', 'QuizController@removeQuestion');
    });
});

Route::middleware(['auth:api', 'approved-only'])->namespace('Mails')->group(function () {
    Route::post('/mails/videocasts/suggestion', 'EmailController@sendVideocastSuggestionEmail');
    Route::post('/mails/contact', 'EmailController@sendContactUsEmail');
});

// auth not required (public info)
Route::namespace('Api')->group(function () {
    Route::resource('/states', 'StateController')->except('create', 'edit');
    Route::get('/cities/state/{id}', 'CityController@allByState');

    Route::get('/teams', 'TeamController@index');

    Route::get('/freshdesk', 'FreshdeskController@freshdesk');

    Route::get('/files/view/{id}', 'FileController@viewDocument')
        ->name('file.view')
        ->middleware('signed');

    Route::get('/sing-file/{id}/file', 'FileSignatureController@viewSignedFileUrl');

    Route::get('/reports/user-access/download', 'ReportController@downloadUserAccess')
        ->name('report.user-access.download')
        ->middleware('signed');
    Route::get('/reports/user-access/view', 'ReportController@viewUserAccess')
        ->name('report.user-access.view')
        ->middleware('signed');

    Route::get('/reports/allow-terms-accepted/download', 'AllowTermsAcceptedReportController@downloadUserAccess')
        ->name('report.allow-terms-accepted.download')
        ->middleware('signed');
    Route::get('/reports/allow-terms-accepted/view', 'AllowTermsAcceptedReportController@viewUserAccess')
        ->name('report.allow-terms-accepted.view')
        ->middleware('signed');
});

Route::namespace('Auth')->group(function () {
    Route::post('/login/register', 'AuthController@register');
});
