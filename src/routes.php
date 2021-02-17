<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/v2'], function () {
    Route::resource('/dalili-analytics', 'Bonfix\DaliliAnalytics\AnalyticsSessionController');
});
