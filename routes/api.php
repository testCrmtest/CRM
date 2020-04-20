<?php

// Route::resource('articles', 'Admin\ApiController');
// Route::post('getinfo', 'Api\V2\BaseController@getInfo');

Route::group(['prefix' => 'v2', 'as' => 'api.', 'namespace' => 'Api\V2'], function () {
    Route::post('getinfo', 'BaseController@getInfo');
    Route::get('collection', 'BaseController@index');
    Route::post('addnewuser', 'BaseController@addNewUser');
    Route::post('getvmcontract', 'BaseController@getVmContract');
    Route::post('getcontract', 'ContractController@getContract');
    Route::post('getcontracts', 'ContractController@getContracts');
    Route::post('savecontract', 'ContractController@saveContractAndEditBaseFields');
});



Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Lessons
    Route::apiResource('lessons', 'LessonsApiController');

    // School Classes
    Route::apiResource('school-classes', 'SchoolClassesApiController');
});
