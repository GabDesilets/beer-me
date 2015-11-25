<?php

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

use \Dotenv\Dotenv;

$dotEnv = new Dotenv(__DIR__ . '/../');
$dotEnv->load();
