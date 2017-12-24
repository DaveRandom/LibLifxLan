<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/helpers/functions.php';

/** @noinspection PhpIncludeInspection */
require \is_file(__DIR__ . '/config.php')
    ? __DIR__ . '/config.php'
    : __DIR__ . '/helpers/config.sample.php';
