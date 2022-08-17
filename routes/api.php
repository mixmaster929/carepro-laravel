<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace'=>'Api','prefix'=>'v1','middleware'=>'api.token'],function(){


    Route::group(['namespace'=>'Site','prefix'=>'site'],function(){
        Route::get('/settings','IndexController@settings');
        Route::get('/categories','IndexController@categories');
        Route::get('/candidates','ProfilesController@profiles');
        Route::get('/candidates/{candidate}','ProfilesController@profile');
        Route::get('/candidate-fields','ProfilesController@candidateFields');
    });

    Route::get('/site/vacancies','Candidate\VacanciesController@index');
    Route::get('/site/vacancies/{vacancy}','Candidate\VacanciesController@view');



    Route::group(['namespace'=>'Auth','prefix'=>'auth'],function(){
        Route::get('/candidate-fields','AuthController@candidateFields');
        Route::get('/employer-fields','AuthController@employerFields');
        Route::post('/register-employer','AuthController@registerEmployer');
        Route::post('/register-candidate','AuthController@registerCandidate');
        Route::post('/login','AuthController@login');
        Route::get('/user','AuthController@getUser')->middleware('auth:api');
        Route::post('/reset-password','AuthController@resetPassword');
    });



    Route::group(['middleware'=>['auth:api']],function(){

        //create account routes
        Route::group(['namespace'=>'Account','prefix'=>'account'],function (){

            Route::get('/invoices','InvoiceController@index');
            Route::get('/invoices/{invoice}','InvoiceController@view');

            Route::get('/countries','BillingAddressController@countries');

            Route::get('/billing-addresses','BillingAddressController@index');
            Route::post('/billing-addresses','BillingAddressController@store');
            Route::get('/billing-addresses/{billingAddress}','BillingAddressController@edit');
            Route::put('/billing-addresses/{billingAddress}','BillingAddressController@update');
            Route::delete('/billing-addresses/{billingAddress}','BillingAddressController@destroy');

            //contract routes
            Route::get('/contracts','ContractController@index');
            Route::get('/contracts/{contract}','ContractController@show');
            Route::put('/contracts/{contract}','ContractController@update');
            Route::get('/contract-pdfs/{contract}','ContractController@download');

            Route::post('/account-passwords','ProfileController@updatePassword');

        });

        Route::group(['middleware'=>'api.candidate','namespace'=>'Candidate','prefix'=>'candidate'],function(){

            Route::get('/stats','HomeController@index');
            Route::get('/image','HomeController@showImage');
            Route::get('/download','HomeController@download');
            Route::delete('/file/{fieldId}','HomeController@removeFile');
            Route::delete('/picture','HomeController@removePicture');
            Route::delete('/cv','HomeController@removeCv');
            Route::get('/applications','HomeController@applications');
            Route::get('/employments','HomeController@placements');
            //profile routes
            Route::get('/profile','ProfileController@profile');
            Route::put('/profile','ProfileController@update');
            Route::get('/candidate-fields','ProfileController@candidateFields');

            //test routes
            Route::get('/tests','TestController@index');
            Route::post('/user-tests','TestController@start');
            Route::put('/user-tests/{userTest}','TestController@processTest');
            Route::get('/user-tests/{userTest}','TestController@result');
            Route::get('/tests/{test}/results','TestController@results');

            Route::post('/vacancies/{vacancy}/applications','VacanciesController@submit');
            Route::get('/vacancies/{vacancy}/application','VacanciesController@getVacancyApplication');

        });

        Route::group(['middleware'=>'api.employer','namespace'=>'Employer','prefix'=>'employer'],function(){

                Route::get('/stats','HomeController@index');
                Route::get('/image','HomeController@showImage');
                Route::get('/download','HomeController@download');
                Route::delete('/file/{fieldId}','HomeController@removeFile');

                //interview routes
                Route::get('/interviews','InterviewController@interviews');
                Route::get('/interviews/{interview}','InterviewController@view');
                Route::put('/interviews/{interview}','InterviewController@update');

                //order routes
                Route::get('/order-forms','OrderController@forms');
                Route::get('/order-forms/{orderForm}','OrderController@form');

                //create a new order
                Route::post('/orders','OrderController@save');
                Route::get('/orders','OrderController@orders');
                Route::get('/orders/{order}','OrderController@view');
                Route::get('/orders/{order}/comments','OrderController@comments');
                Route::post('/orders/{order}/comments','OrderController@addComment');
                Route::get('/order-comment-attachment/{orderCommentAttachment}','OrderController@downloadAttachment');
                Route::get('/order-comments/{orderComment}/attachments','OrderController@downloadAttachments');

                //placement routes
                Route::get('/employments','PlacementController@placements');
                Route::get('/employments/{employment}','PlacementController@view');
                Route::get('/employments/{employment}/comments','PlacementController@comments');
                Route::post('/employments/{employment}/comments','PlacementController@addComment');
                Route::get('/employment-comment-attachments/{employmentCommentAttachment}','PlacementController@downloadAttachment');
                Route::get('/employment-comments/{employmentComment}/attachments','PlacementController@downloadAttachments');
                Route::post('/employment-comment-upload','PlacementController@upload');
                Route::delete('/employment-comment-upload/{id}','PlacementController@removeUpload');

                //profile routes
                Route::get('/profile','ProfileController@profile');
                Route::put('/profile','ProfileController@update');
                Route::get('/employer-fields','ProfileController@employerFields');

        });


    });

});
