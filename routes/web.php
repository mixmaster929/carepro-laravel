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


Route::group(['namespace'=>'Site'],function(){

    Route::group(['middleware'=>'frontend'],function(){
        Route::get('/','HomeController@index')->name('homepage');
        Route::get('/contact','HomeController@contact')->name('contact');
        Route::post('/contact/send-mail','HomeController@sendMail')->name('contact.send-mail');
        Route::get('/blog','BlogController@index')->name('blog');
        Route::get('/blog/{blogPost}','BlogController@post')->name('blog.post');
    });

    Route::get('/profiles','ProfilesController@index')->name('profiles');
    Route::get('/profiles/{candidate}','ProfilesController@profile')->name('profile');
    Route::get('/shortlist-candidate/{candidate}','ProfilesController@shortlistCandidate')->name('shortlist-candidate');
    Route::get('/shortlist','ProfilesController@shortlist')->name('shortlist');
    Route::get('/shortlist/remove/{candidate}','ProfilesController@removeFromList')->name('shortlist.remove');

});

Route::get('/vacancies','Candidate\VacanciesController@index')->name('vacancies');
Route::get('/vacancies/{vacancy}','Candidate\VacanciesController@view')->name('view-vacancy');

Route::group(['namespace'=>'Employer','middleware'=>['employer']],function(){
    Route::get('order-forms','OrderController@forms')->name('order-forms');
    Route::get('/order-form/{orderForm}','OrderController@form')->name('order-form');
    Route::post('/order/save-form/{orderForm}','OrderController@save')->name('order.save-form');
    Route::get('/order-complete','OrderController@complete')->name('order.complete');
});




Route::get('test','TestController@index');

Route::get('register/employer','Auth\RegisterController@employer')->name('register.employer');
Route::get('register/candidate','Auth\RegisterController@candidate')->name('register.candidate');
Route::post('register/employer','Auth\RegisterController@registerEmployer')->name('register.save-employer');
Route::post('register/candidate','Auth\RegisterController@registerCandidate')->name('register.save-candidate');

Auth::routes();
Route::get('logout','Auth\LoginController@logout');
Route::get('social/login/{network}','Auth\LoginController@social')->name('social.login');
Route::get('social/employer','Auth\LoginController@completeEmployer')->name('social.employer');
Route::post('social/save-employer','Auth\LoginController@saveEmployer')->name('social.save-employer');
Route::get('social/candidate','Auth\LoginController@completeCandidate')->name('social.candidate');
Route::post('social/save-candidate','Auth\LoginController@saveCandidate')->name('social.save-candidate');

Route::get('email-confirmation','Auth\RegisterController@confirm')->name('register.confirm');
Route::get('confirm/employer/{hash}','Auth\RegisterController@confirmEmployer')->name('confirm.employer');
Route::get('confirm/candidate/{hash}','Auth\RegisterController@confirmCandidate')->name('confirm.candidate');

Route::get('home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('pay/{hash}','Site\CartController@pay')->name('cart.pay');

Route::group(['middleware'=>['auth','admin',\App\Http\Middleware\UserLimit::class],'prefix' => 'admin', 'as' => 'admin.','namespace'=>'Admin'],function() {

    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('users/search','HomeController@search')->name('users.search');
    Route::get('image','HomeController@showImage')->name('image');
    Route::get('download','HomeController@download')->name('download');


    Route::get('approve-invoice/{invoice}','InvoicesController@approve')->name('invoices.approve');
    Route::resource('invoices', 'InvoicesController');
    Route::get('delete-invoice/{id}','InvoicesController@destroy')->name('invoices.delete');
    Route::resource('invoice-categories', 'InvoiceCategoriesController');

    Route::resource('currencies', 'CurrenciesController');

    Route::resource('features', 'FeaturesController');

    Route::resource('article-categories', 'ArticleCategoriesController');

    Route::resource('articles', 'ArticlesController');
    Route::resource('help-posts', 'HelpPostsController');
    Route::resource('help-categories', 'HelpCategoriesController');
    Route::resource('blog-categories', 'BlogCategoriesController');
    Route::resource('blog-posts', 'BlogPostsController');

    Route::get('blog-remove-picture/{id}','BlogPostsController@removePicture')->name('blog.remove-picture');

    Route::get('candidate-remove-picture/{id}','CandidatesController@removePicture')->name('candidate.remove-picture');
    Route::get('candidate-remove-cv/{id}','CandidatesController@removeCv')->name('candidate.remove-cv');

    Route::get('candidate-remove-file/{fieldId}/{userId}','CandidatesController@removeFile')->name('candidate.remove-file');

    Route::get('candidate/download-profile/{id}/{full}','CandidatesController@download')->name('candidate.download');
    Route::get('candidates/search','CandidatesController@search')->name('candidates.search');
    Route::get('candidates/export','CandidatesController@export')->name('candidates.export');
    Route::get('candidates/import-1','CandidatesController@importForm')->name('candidates.import-1');
    Route::post('candidates/import-1','CandidatesController@importUpload')->name('candidates.import-upload');
    Route::get('candidates/import-2','CandidatesController@importFields')->name('candidates.import-2');
    Route::post('candidates/import-2','CandidatesController@saveImportFields')->name('candidates.import-save-fields');
    Route::get('candidates/import-3','CandidatesController@importPreview')->name('candidates.import-preview');
    Route::post('candidates/process-import','CandidatesController@processImport')->name('candidates.import-process');
    Route::get('candidates/import-4','CandidatesController@completeImport')->name('candidates.import-complete');
    Route::get('candidates/test-results/{user}','CandidatesController@tests')->name('candidates.tests');
    Route::resource('candidates', 'CandidatesController');
    Route::resource('categories', 'CategoriesController');

    Route::resource('notes','NotesController');
    Route::get('user-notes/{user}','NotesController@index')->name('notes.index');
    Route::get('user-notes/create/{user}','NotesController@create')->name('notes.create');
    Route::post('user-notes/{user}','NotesController@store')->name('notes.store');

    Route::resource('attachments', 'AttachmentsController');
    Route::get('user-attachments/{user}','AttachmentsController@index')->name('attachments.index');
    Route::get('user-attachments/create/{user}','AttachmentsController@create')->name('attachments.create');
    Route::post('user-attachments/{user}','AttachmentsController@store')->name('attachments.store');

    Route::get('employer-remove-file/{fieldId}/{userId}','EmployersController@removeFile')->name('employer.remove-file');
    Route::get('employers/search','EmployersController@search')->name('employers.search');
    Route::get('employers/export','EmployersController@export')->name('employers.export');
    Route::get('employers/import-1','EmployersController@importForm')->name('employers.import-1');
    Route::post('employers/import-1','EmployersController@importUpload')->name('employers.import-upload');
    Route::get('employers/import-2','EmployersController@importFields')->name('employers.import-2');
    Route::post('employers/import-2','EmployersController@saveImportFields')->name('employers.import-save-fields');
    Route::get('employers/import-3','EmployersController@importPreview')->name('employers.import-preview');
    Route::post('employers/process-import','EmployersController@processImport')->name('employers.import-process');
    Route::get('employers/import-4','EmployersController@completeImport')->name('employers.import-complete');
    Route::resource('employers', 'EmployersController');

    Route::resource('employments', 'EmploymentsController');
    Route::get('employment-records/{user}','EmploymentsController@index')->name('employments.index');
    Route::get('employment-records/create/{user}','EmploymentsController@create')->name('employments.create');
    Route::post('employment-records/{user}','EmploymentsController@store')->name('employments.store');
    Route::get('browse-employments','EmploymentsController@browse')->name('employments.browse');

    Route::get('create-employment','EmploymentsController@createEmployment')->name('employments.create-employment');
    Route::post('store-employment','EmploymentsController@storeEmployment')->name('employments.store-employment');

    Route::resource('employment-comments', 'EmploymentCommentsController');
    Route::get('employment-comment/{employment}','EmploymentCommentsController@index')->name('employment-comments.index');
    Route::get('employment-comment/create/{employment}','EmploymentCommentsController@create')->name('employment-comments.create');
    Route::post('employment-comment/{employment}','EmploymentCommentsController@store')->name('employment-comments.store');

    Route::post('employment-comment/upload-attachment/{id}','EmploymentCommentsController@upload')->name('employment-comments.upload');
    Route::post('employment-comment/remove-upload/{id}','EmploymentCommentsController@removeUpload')->name('employment-comments.remove-upload');
    Route::get('employment-comment/download-attachment/{employmentCommentAttachment}','EmploymentCommentsController@downloadAttachment')->name('employment-comments.download-attachment');
    Route::get('employment-comment/download-attachments/{employmentComment}','EmploymentCommentsController@downloadAttachments')->name('employment-comments.download-attachments');

    Route::get('employment-comment/delete-attachment/{employmentCommentAttachment}','EmploymentCommentsController@deleteAttachment')->name('employment-comments.delete-attachment');


    Route::resource('orders', 'OrdersController');
    Route::post('create-order','OrdersController@doCreate')->name('orders.do-create');
    Route::get('orders/create/{orderForm}','OrdersController@create')->name('orders.create');
    Route::post('orders/create/{orderForm}','OrdersController@store')->name('orders.store');
    Route::get('order-remove-file/{fieldId}/{orderId}','OrdersController@removeFile')->name('order.remove-file');
    Route::post('order-create-invoice/{order}','OrdersController@createOrderInvoice')->name('order.create-invoice');

    Route::resource('order-comments', 'OrderCommentsController');
    Route::get('order-comment/{order}','OrderCommentsController@index')->name('order-comments.index');
    Route::get('order-comment/create/{order}','OrderCommentsController@create')->name('order-comments.create');
    Route::post('order-comment/{order}','OrderCommentsController@store')->name('order-comments.store');

    Route::post('order-comment/upload-attachment/{id}','OrderCommentsController@upload')->name('order-comments.upload');
    Route::post('order-comment/remove-upload/{id}','OrderCommentsController@removeUpload')->name('order-comments.remove-upload');
    Route::get('order-comment/download-attachment/{orderCommentAttachment}','OrderCommentsController@downloadAttachment')->name('order-comments.download-attachment');
    Route::get('order-comment/download-attachments/{orderComment}','OrderCommentsController@downloadAttachments')->name('order-comments.download-attachments');

    Route::get('order-comment/delete-attachment/{orderCommentAttachment}','OrderCommentsController@deleteAttachment')->name('order-comments.delete-attachment');

    //vacancy routes
    Route::resource('job-categories', 'JobCategoriesController');
    Route::resource('vacancies', 'VacanciesController');

    Route::resource('applications', 'ApplicationsController');
    Route::get('application-records/{vacancy}','ApplicationsController@index')->name('applications.index');
    Route::get('shortlist-application/{application}','ApplicationsController@shortlist')->name('applications.shortlist');

    Route::resource('emails', 'EmailsController');
    Route::resource('email-resources', 'EmailResourcesController');
    Route::get('email/download-attachment/{emailAttachment}','EmailsController@downloadAttachment')->name('emails.download-attachment');
    Route::get('email/download-attachments/{email}','EmailsController@downloadAttachments')->name('emails.download-attachments');
    Route::get('email/delete-attachment/{emailAttachment}','EmailsController@deleteAttachment')->name('emails.delete-attachment');
    Route::get('email/send-now/{email}','EmailsController@sendNow')->name('emails.send-now');
    Route::post('emails/delete-multiple','EmailsController@deleteMultiple')->name('email.delete-multiple');
    Route::get('email/delete/{id}','EmailsController@destroy')->name('email.delete');

    Route::resource('email-templates', 'EmailTemplatesController');
    Route::get('get-email-template/{emailTemplate}','EmailTemplatesController@getTemplate')->name('email-templates.get');


    Route::resource('sms', 'SmsController');
    Route::get('sms-msg/send-now/{sms}','SmsController@sendNow')->name('sms.send-now');
    Route::post('sms-msg/delete-multiple','SmsController@deleteMultiple')->name('sms.delete-multiple');
    Route::get('sms-msg/delete/{id}','SmsController@destroy')->name('sms.delete');
    Route::resource('sms-templates', 'SmsTemplatesController');
    Route::get('get-sms-template/{smsTemplate}','SmsTemplatesController@getTemplate')->name('sms-templates.get');

    Route::resource('interviews', 'InterviewsController');

    Route::resource('tests', 'TestsController');
    Route::resource('test-questions', 'TestQuestionsController');
    Route::get('test-question/{test}','TestQuestionsController@index')->name('test-questions.index');
    Route::get('test-question/create/{test}','TestQuestionsController@create')->name('test-questions.create');
    Route::post('test-question/{test}','TestQuestionsController@store')->name('test-questions.store');
    Route::post('test-option/store/{testQuestion}','TestQuestionsController@storeOptions')->name('test-options.store');
    Route::get('test-options/edit/{testOption}','TestQuestionsController@editOption')->name('test-options.edit');
    Route::post('test-options/update/{testOption}','TestQuestionsController@updateOption')->name('test-options.update');
    Route::get('test-options/delete/{testOption}','TestQuestionsController@deleteOption')->name('test-options.delete');
    Route::get('test-attempts/{test}','TestsController@attempts')->name('tests.attempts');
    Route::get('delete-result/{userTest}','TestsController@deleteResult')->name('tests.delete-result');
    Route::get('view-result/{userTest}','TestsController@results')->name('tests.results');

    //This group uses middleware to apply the access gate to settings related routes
    Route::group(['middleware'=>['admin-settings']],function(){

        Route::resource('api-tokens', 'ApiTokensController');

        Route::get('settings/{group}','SettingsController@settings')->name('settings');
        Route::post('save-settings','SettingsController@saveSettings')->name('save-settings');
        Route::get('settings/remove-picture/{setting}','SettingsController@removePicture')->name('remove-picture');
        Route::get('language','SettingsController@language')->name('language');
        Route::post('save-language','SettingsController@saveLanguage')->name('save-language');

        Route::get('payment-methods','PaymentMethodsController@index')->name('payment-methods');
        Route::get('payment-methods/edit/{paymentMethod}','PaymentMethodsController@edit')->name('payment-methods.edit');
        Route::post('payment-methods/update/{paymentMethod}','PaymentMethodsController@update')->name('payment-methods.update');

        Route::get('payment-methods/install/{method}','PaymentMethodsController@install')->name('payment-methods.install');
        Route::get('payment-methods/uninstall/{paymentMethod}','PaymentMethodsController@uninstall')->name('payment-methods.uninstall');

        Route::resource('admins', 'AdminsController');

        Route::get('sms-settings','SmsGatewaysController@smsGateways')->name('sms-gateways');
        Route::post('save-sms-setting','SmsGatewaysController@saveSmsSetting')->name('save-sms-setting');
        Route::get('sms-gateway/{smsGateway}','SmsGatewaysController@smsFields')->name('edit-sms-gateway');
        Route::post('save-sms-gateway/{smsGateway}','SmsGatewaysController@saveField')->name('save-sms-gateway');
        Route::get('gateway-status/{smsGateway}/{status}','SmsGatewaysController@setSmsStatus')->name('sms-status');
        Route::get('gateway-install/{gateway}','SmsGatewaysController@install')->name('sms-install');

        //routes for templates
        Route::get('templates','TemplatesController@index')->name('templates');
        Route::get('templates/install/{templateDir}','TemplatesController@install')->name('templates.install');
        Route::get('templates/settings','TemplatesController@settings')->name('templates.settings');
        Route::get('templates/colors','TemplatesController@colors')->name('templates.colors');
        Route::post('templates/save-options/{option}','TemplatesController@saveOptions')->name('templates.save-options');
        Route::post('templates/upload','TemplatesController@upload')->name('templates.upload');
        Route::post('templates/save-colors','TemplatesController@saveColors')->name('templates.save-colors');

        Route::get('menus/header','MenuController@headerMenu')->name('menus.header');
        Route::get('menus/footer','MenuController@footerMenu')->name('menus.footer');
        Route::get('menus/load-header','MenuController@loadHeaderMenu')->name('menus.load-header');
        Route::post('menus/save-header','MenuController@saveHeaderMenu')->name('menus.save-header');
        Route::post('menus/update-header/{headerMenu}','MenuController@updateHeaderMenu')->name('menus.update-header');
        Route::get('menus/delete-header/{headerMenu}','MenuController@deleteHeaderMenu')->name('menus.delete-header');

        Route::get('menus/load-footer','MenuController@loadFooterMenu')->name('menus.load-footer');
        Route::post('menus/save-footer','MenuController@saveFooterMenu')->name('menus.save-footer');
        Route::post('menus/update-footer/{footerMenu}','MenuController@updateFooterMenu')->name('menus.update-footer');
        Route::get('menus/delete-footer/{footerMenu}','MenuController@deleteFooterMenu')->name('menus.delete-footer');

        Route::resource('order-forms', 'OrderFormsController');

        Route::resource('order-field-groups', 'OrderFieldGroupsController');

        Route::get('order-form/field-groups/{orderForm}','OrderFieldGroupsController@index')->name('order-field-groups.index');
        Route::get('order-form/field-groups/create/{orderForm}','OrderFieldGroupsController@create')->name('order-field-groups.create');
        Route::post('order-form/field-groups/{orderForm}','OrderFieldGroupsController@store')->name('order-field-groups.store');


        Route::resource('order-fields', 'OrderFieldsController');
        Route::get('order-form/fields/{orderFieldGroup}','OrderFieldsController@index')->name('order-fields.index');
        Route::get('order-form/create/{orderFieldGroup}','OrderFieldsController@create')->name('order-fields.create');
        Route::post('order-form/{orderFieldGroup}','OrderFieldsController@store')->name('order-fields.store');

        Route::resource('employer-field-groups', 'EmployerFieldGroupsController');

        Route::resource('employer-fields', 'EmployerFieldsController');
        Route::get('employer-form/fields/{employerFieldGroup}','EmployerFieldsController@index')->name('employer-fields.index');
        Route::get('employer-form/create/{employerFieldGroup}','EmployerFieldsController@create')->name('employer-fields.create');
        Route::post('employer-form/{employerFieldGroup}','EmployerFieldsController@store')->name('employer-fields.store');


        Route::resource('candidate-field-groups', 'CandidateFieldGroupsController');
        Route::resource('candidate-fields', 'CandidateFieldsController');
        Route::get('candidate-form/fields/{candidateFieldGroup}','CandidateFieldsController@index')->name('candidate-fields.index');
        Route::get('candidate-form/create/{candidateFieldGroup}','CandidateFieldsController@create')->name('candidate-fields.create');
        Route::post('candidate-form/{candidateFieldGroup}','CandidateFieldsController@store')->name('candidate-fields.store');

        Route::resource('roles', 'RolesController');

    });

    Route::get('profile','SettingsController@profile')->name('profile');
    Route::post('profile','SettingsController@saveProfile')->name('save-profile');

    Route::get('frontend','SettingsController@frontend')->name('frontend');
    Route::post('frontend','SettingsController@saveFrontend')->name('save-frontend');

    //contract routes
    Route::resource('contracts', 'ContractsController');
    Route::get('contracts/get-template/{contractTemplate}','ContractsController@getTemplate')->name('contracts.get-template');
    Route::get('contracts/send/{contract}','ContractsController@sendContract')->name('contracts.send');
    Route::get('contracts/download/{contract}','ContractsController@download')->name('contracts.download');
    Route::resource('contract-templates', 'ContractTemplatesController');

});

Route::group(['middleware'=>['auth','admin'],'prefix' => 'admin', 'as' => 'admin.','namespace'=>'Admin'],function() {

    Route::get('users','UsersController@index')->name('users.index');
    Route::get('users/delete/{user}','UsersController@destroy')->name('users.delete');


});

Route::group(['middleware'=>['employer'],'prefix' => 'employer', 'as' => 'employer.','namespace'=>'Employer'],function() {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');
    Route::get('orders','OrderController@orders')->name('orders');
    Route::get('orders/{order}','OrderController@view')->name('view-order');
    Route::get('order-comments/{order}','OrderController@comments')->name('orders.comments');
    Route::post('order-comments/{order}','OrderController@addComment')->name('orders.add-comment');
    Route::get('order-comment/download-attachment/{orderCommentAttachment}','OrderController@downloadAttachment')->name('order-comments.download-attachment');
    Route::get('order-comment/download-attachments/{orderComment}','OrderController@downloadAttachments')->name('order-comments.download-attachments');

    Route::get('placements','PlacementController@placements')->name('placements');
    Route::get('placements/{employment}','PlacementController@view')->name('view-placement');
    Route::get('placement-comments/{employment}','PlacementController@comments')->name('placements.comments');
    Route::post('placement-comments/{employment}','PlacementController@addComment')->name('placements.add-comment');
    Route::get('placement-comment/download-attachment/{employmentCommentAttachment}','PlacementController@downloadAttachment')->name('placement-comments.download-attachment');
    Route::get('placement-comment/download-attachments/{employmentComment}','PlacementController@downloadAttachments')->name('placement-comments.download-attachments');
    Route::post('placement-comment/upload/{id}','PlacementController@upload')->name('placement-comments.upload');
    Route::post('placement-comment/remove-upload/{id}','PlacementController@removeUpload')->name('placement-comments.remove-upload');

    Route::get('interviews','InterviewController@interviews')->name('interviews');
    Route::get('interviews/{interview}','InterviewController@view')->name('view-interview');
    Route::post('interviews/{interview}','InterviewController@update')->name('update-interview');

    Route::get('image','HomeController@showImage')->name('image');
    Route::get('download','HomeController@download')->name('download');
    Route::get('employer-remove-file/{fieldId}/{userId}','HomeController@removeFile')->name('remove-file');

    Route::get('profile','ProfileController@profile')->name('profile');
    Route::post('update-profile','ProfileController@update')->name('save-profile');

});

Route::group(['middleware'=>['candidate'],'prefix' => 'candidate', 'as' => 'candidate.','namespace'=>'Candidate'],function() {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');
    Route::get('/vacancy-application/{vacancy}','VacanciesController@apply')->name('vacancy.apply');
    Route::post('/vacancy-application/{vacancy}','VacanciesController@submit')->name('vacancy.submit');
    Route::get('/application-complete','VacanciesController@complete')->name('vacancy.complete');
    Route::get('applications','HomeController@applications')->name('applications');
    Route::get('interviews','HomeController@interviews')->name('interviews');
    Route::get('viewInterview','HomeController@viewInterview')->name('view-interview');
    Route::get('placements','HomeController@placements')->name('placements');
    Route::get('tests','TestController@index')->name('tests');
    Route::get('test/{test}','TestController@start')->name('tests.start');
    Route::post('test/{userTest}','TestController@processTest')->name('tests.process');
    Route::get('test-result/{userTest}','TestController@result')->name('tests.result');
    Route::get('test-results/{test}','TestController@results')->name('tests.results');

    Route::get('profile','ProfileController@profile')->name('profile');
    Route::post('update-profile','ProfileController@update')->name('save-profile');

    Route::get('image','HomeController@showImage')->name('image');
    Route::get('download','HomeController@download')->name('download');
    Route::get('candidate-remove-file/{fieldId}','HomeController@removeFile')->name('remove-file');

    Route::get('candidate-remove-picture','HomeController@removePicture')->name('remove-picture');
    Route::get('candidate-remove-cv','HomeController@removeCv')->name('remove-cv');




});

//general auth routes
Route::group(['middleware'=>['auth'],'prefix' => 'account', 'as' => 'user.','namespace'=>'Account'],function(){

    Route::post('billing/create-invoice','InvoiceController@create')->name('invoice.create');
    Route::get('cart','InvoiceController@cart')->name('invoice.cart')->middleware('cart');
    Route::get('cart/cancel','InvoiceController@cancel')->name('invoice.cancel')->middleware('cart');
    Route::get('checkout','InvoiceController@checkout')->name('invoice.checkout')->middleware('cart','billingAddress');
    Route::get('checkout/change-address','InvoiceController@selectAddress')->name('invoice.change-address')->middleware('cart','billingAddress');
    Route::get('checkout/set-address/{id}','InvoiceController@setAddress')->name('invoice.set-address')->middleware('cart','billingAddress');
    //Route::any('checkout/callback','InvoiceController@callback')->name('invoice.callback')->middleware('cart');
    Route::post('cart/set-method','InvoiceController@setMethod')->name('invoice.set-method');
    Route::get('payment-complete','InvoiceController@complete')->name('invoice.payment-complete');
    Route::any('callback/{code}','CallbackController@method')->name('callback')->middleware('cart');
  /*  Route::get('paypal/setup','PaypalController@setup')->name('paypal.setup');
    Route::get('paypal/callback','PaypalController@callback')->name('paypal.callback');*/

    Route::get('billing/invoices','InvoiceController@index')->name('billing.invoices');
    Route::get('billing/invoice/{invoice}','InvoiceController@view')->name('billing.invoice')->middleware('billingAddress');
    Route::get('billing/pay-invoice/{invoice}','InvoiceController@pay')->name('billing.pay');

    Route::resource('billing-address','BillingAddressController');

    Route::get('profile','ProfileController@index')->name('profile');
    Route::get('password','ProfileController@password')->name('password');
    Route::post('save-password','ProfileController@updatePassword')->name('save-password');

    //contract routes
    Route::get('contracts','ContractController@index')->name('contract.index');
    Route::get('contracts/show/{contract}','ContractController@show')->name('contract.show');
    Route::post('contracts/update/{contract}','ContractController@update')->name('contract.save');
    Route::get('contracts/download/{contract}','ContractController@download')->name('contract.download');


});

Route::group(['namespace'=>'Account'],function(){
    Route::any('/ipn/{code}','InvoiceController@ipn')->name('cart.ipn');
    Route::any('/cart/method/{code}/{function}','InvoiceController@method')->name('cart.method');

});

//this route should point to a controller that fetches articles
Route::get('/{slug}','Site\HomeController@article')->name('article')->middleware('frontend');
