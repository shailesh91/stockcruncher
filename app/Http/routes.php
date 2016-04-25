<?php


Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'PagesController@index');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/dashboard', ['as'=>'dashboard','uses'=>'DashboardController@index']);
    Route::post('/addStocksToPortfolio',['as' => 'addStocks', 'uses' => 'PortfolioController@addStocks']);
    Route::get('/stock/{id}/{stocksymbol}', ['as'=>'stock','uses'=>'DashboardController@getStock']);
    Route::get('/getStockNames', 'AjaxQueryController@getStockNames');
    Route::get('/getRealTimeData', 'AjaxQueryController@getRealTimeData');
    Route::get('/getHistoricalData', 'AjaxQueryController@getHistoricalData');
    

});
