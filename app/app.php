<?php

use Silex\Application;

// Initialisation de l'application Silex
$app          = new Application();
// Mode debug ON
$app['debug'] = true;
require 'routes.php';
// Démarrage de l'application
$app->run();
