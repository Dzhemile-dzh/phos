<?php
if (isset($_GET['action'])) {
    $request = $_GET['action'];

    $routes = [
        'rates' => "RateController@allRatesAction",
        'addRate' => "RateController@addRateAction",
        'editRate' => "RateController@editRateAction",
        'deleteRate' => "RateController@deleteRateAction",
        'historicalRates' => "RateController@historicalRatesAction",
        'exportReport' => "RatesController@exportReportAction"
    ];

    $route = $routes[$request] ?? null;
}
