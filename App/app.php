<?php

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slimapp\Middleware\JsonBodyParser;
use Slimapp\Middleware\SessionMiddleware;

$app = AppFactory::create();
$app->setBasePath(APP_BASEPATH);


//SQl
require_once("sql/db.php");

//Middleware

//Functions
include_once("functions/functions.php");
include_once("functions/register_views.php");
include_once("functions/register_menu.php"); 

//Routes
require_once("routes/login.php");
require_once("routes/secret.php");
require_once("routes/add-user.php");
require_once("routes/add-product.php");
require_once("routes/views.php");
require_once("routes/api.php");


$app->add(new SessionMiddleware());

//Body Parser

$app->add(new JsonBodyParser()); 

$app->addRoutingMiddleware();

$app->addErrorMiddleware(true, false, false);

$app->run(); 