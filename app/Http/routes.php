<?php


Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'PagesController@index');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/dashboard', ['as'=>'dashboard','uses'=>'DashboardController@index']);
    Route::get('/getStockNames', 'AjaxQueryController@getStockNames');
    Route::post('/addStocksToPortfolio',['as' => 'addStocks', 'uses' => 'PortfolioController@addStocks']);

});
