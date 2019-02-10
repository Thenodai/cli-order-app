#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Command\DeleteOrder;
use App\Command\FetchOrders;
use App\Command\PlaceOrder;
use App\Command\UpdateOrder;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->load(__DIR__ . '/.env');

$app = new Application();
$app->add(new PlaceOrder());
$app->add(new UpdateOrder());
$app->add(new DeleteOrder());
$app->add(new FetchOrders());
$app->run();
