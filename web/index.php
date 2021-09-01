<?php

require_once "../vendor/autoload.php";
require '../MiniBlogApplication.php';

$app = new MiniBlogApplication(true);
$app->run();