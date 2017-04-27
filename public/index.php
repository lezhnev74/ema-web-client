<?php
use Slim\App;

require(__DIR__ . "/../bootstrap.php");


$app = container()->get(App::class);
$app->run();