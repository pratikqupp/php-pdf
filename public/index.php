<?php
require '../vendor/autoload.php';
require '../config/container.php';

$app = $container->get(\App\Application\Http\Application::class);
$app->run();
