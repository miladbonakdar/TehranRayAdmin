<?php

require_once '../dbHandler.php';
require_once '../classes.php';
require_once '../sessionHandler.php';
require_once '../passwordHash.php';
require_once '../functions.php';
require '../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->db = new DbHandler(null ,true);

require_once 'user_service.php';
require_once '../generic_service.php';

$app->run();
?>