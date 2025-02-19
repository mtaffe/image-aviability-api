<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Testeeee';
});

require __DIR__.'/auth.php';
