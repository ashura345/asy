<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/chart-data', function () {
    return response()->json([
        'labels' => ['Januari', 'Februari', 'Maret', 'April'],
        'values' => [1500000, 1200000, 1800000, 2000000],
    ]);
});

Route::get('/line-chart-data', function () {
    return response()->json([
        'months' => [1, 2, 3, 4],
        'totals' => [1500000, 1200000, 1800000, 2000000],
    ]);
});
