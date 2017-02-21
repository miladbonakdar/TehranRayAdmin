<?php

require_once '../dbHandler.php';
require_once '../classes.php';
require_once '../sessionHandler.php';
require_once '../passwordHash.php';
require_once '../functions.php';
require '../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->session = new Session();
$app->db = new DbHandler($app->session);

require_once 'public_service.php';
require_once '../generic_service.php';

$app->run();
?>