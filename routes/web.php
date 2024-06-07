<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // login
    $router->post('auth/login', 'AuthController@Login');
    $router->post('auth/loginWeb', 'AuthController@LoginWeb');

    

    // categories
    $router->get('categories/getData', 'FaqCategoriesController@getData');
    $router->get('categories/getCat/{role}', 'FaqCategoriesController@getCat');
    $router->get('categories/getSubCat/{id}', 'FaqCategoriesController@getSubCat');
    $router->get('categories/getAnswer/{id}', 'FaqCategoriesController@getAnswer');
    $router->get('categories/getData/{id}', 'FaqCategoriesController@getDataById');
    $router->post('categories/newCat', 'FaqCategoriesController@newCat');
    $router->post('categories/uploadExcel', 'FaqCategoriesController@uploadExcel');
    $router->post('categories/postData', 'FaqCategoriesController@postData');
    $router->delete('categories/deleteData/{id}', 'FaqCategoriesController@deleteData');
    $router->delete('categories/deleteCat/{id}', 'FaqCategoriesController@deleteCat');
    $router->put('categories/editData/{id}', 'FaqCategoriesController@editData');
    $router->put('categories/editCat/{id}', 'FaqCategoriesController@editCat');

    // pic
    $router->get('Pic/getPic', 'PicController@getPic');
    $router->put('Pic/editPic/{id}', 'PicController@editPic');

    //subcategories 
    $router->get('subcategories/getData', 'FaqSubcategoriesController@getData');
    $router->get('subcategories/getData/{id}', 'FaqSubcategoriesController@getDataById');
    $router->post('subcategories/postData', 'FaqSubcategoriesController@postData');
    $router->delete('subcategories/deleteData/{id}', 'FaqSubcategoriesController@deleteData');
    $router->put('subcategories/editData/{id}', 'FaqSubcategoriesController@editData');

    //answers 
    $router->get('answers/getData', 'AnswerController@getData');
    $router->get('answers/getData/{id}', 'AnswerController@getDataById');
    $router->post('answers/postData', 'AnswerController@postData');
    $router->delete('answers/deleteData/{id}', 'AnswerController@deleteData');
    $router->put('answers/editData/{id}', 'AnswerController@editData');

    //MasterRole 
    $router->get('MasterRole/getData', 'MasterRoleController@getData');
    $router->get('MasterRole/getData/{id}', 'MasterRoleController@getDataById');
    $router->post('MasterRole/postData', 'MasterRoleController@postData');
    $router->delete('MasterRole/deleteData/{id}', 'MasterRoleController@deleteData');
    $router->put('MasterRole/editData/{id}', 'MasterRoleController@editData');

    //UserRole 
    $router->get('UserRole/getData', 'UserRoleController@getData');
    $router->get('UserRole/getData/{id}', 'UserRoleController@getDataById');
    $router->post('UserRole/postData', 'UserRoleController@postData');
    $router->delete('UserRole/deleteData/{id}', 'UserRoleController@deleteData');
    $router->put('UserRole/editData/{id}', 'UserRoleController@editData');

    //Users
    $router->get('Users/getData', 'UsersController@getData');
    $router->get('Users/getData/{id}', 'UsersController@getDataById');
    $router->post('Users/postData', 'UsersController@postData');
    $router->delete('Users/deleteData/{id}', 'UsersController@deleteData');
    $router->put('Users/editData/{id}', 'UsersController@editData');

    //Ratings
    $router->get('Ratings/getRating', 'RatingsController@getRating');
    $router->get('Ratings/getData/{id}', 'RatingsController@getDataById');
    $router->post('Ratings/postData', 'RatingsController@postData');
    $router->delete('Ratings/deleteData/{id}', 'RatingsController@deleteData');
    $router->put('Ratings/editData/{id}', 'RatingsController@editData');

    //News
    $router->get('News/getData', 'NewsController@getData');
    $router->get('News/getData/{id}', 'NewsController@getDataById');
    $router->post('News/postData', 'NewsController@postData');
    $router->delete('News/deleteData/{id}', 'NewsController@deleteData');
    $router->put('News/editData/{id}', 'NewsController@editData');

    //HeaderMessages
    $router->get('HeaderMessages/getData', 'HeaderMessagesController@getData');
    $router->get('HeaderMessages/getData/{id}', 'HeaderMessagesController@getDataById');
    $router->post('HeaderMessages/postData', 'HeaderMessagesController@postData');
    $router->delete('HeaderMessages/deleteData/{id}', 'HeaderMessagesController@deleteData');
    $router->put('HeaderMessages/editData/{id}', 'HeaderMessagesController@editData');

    //Messages
    $router->get('Messages/getData', 'MessagesController@getData');
    $router->get('Messages/getData/{id}', 'MessagesController@getDataById');
    $router->post('Messages/postData', 'MessagesController@postData');
    $router->delete('Messages/deleteData/{id}', 'MessagesController@deleteData');
    $router->put('Messages/editData/{id}', 'MessagesController@editData');
});
