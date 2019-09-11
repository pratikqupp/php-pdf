<?php
require '../vendor/autoload.php';
require '../config/container.php';

$container->get(\App\Application\Http\Application::class)->run();
