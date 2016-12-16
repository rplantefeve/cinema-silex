<?php

use Semeformation\Mvc\Cinema_crud\Controllers\Router;

require_once __DIR__ . '/vendor/autoload.php';

// initialisation de l'application
require_once __DIR__ . '/init.php';

$routeur = new Router($logger);
$routeur->routeRequest();

