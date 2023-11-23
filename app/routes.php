<?php

declare(strict_types=1);

use Slim\App;

return function (App $app) {
    // Tabel pabrik
    $pabrikRoutes = require __DIR__.'/API/pabrik.php';
    $pabrikRoutes($app);

    // Tabel briket
    $briketRoutes = require __DIR__.'/API/briket.php';
    $briketRoutes($app);

    // Tabel pembeli
    $pembeliRoutes = require __DIR__.'/API/pembeli.php';
    $pembeliRoutes($app);

    // Tabel pembeli
    $pembeliRoutes = require __DIR__.'/API/transaksi.php';
    $pembeliRoutes($app);

    // Tabel Transaksi
    $transaksiRoutes = require __DIR__.'/API/detail_transaksi.php';
    $transaksiRoutes($app);
};
