<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;
use Slimapp\Middleware\BasicAuth;
use Slimapp\Middleware\CsrfProtection;
use slimapp\Sql\DbConnect as db;

$app->post("/add-product", function ( $request, $response ){

    $body = $request->getParsedBody();
    $title = ( isset($body['title']) ) ? $body['title'] : false;
    $desc = ( isset($body['description']) ) ? $body['description'] : false;
    $cat = ( isset($body['category']) ) ? $body['category'] : false;
    $location = ( isset($body['location']) ) ? $body['location'] : false;
    $price = ( isset($body['price']) ) ? $body['price'] : false;
    
    if($title && $desc && $cat && $location && $price ){  
        $args = $request->getParsedBody();
        $insert = db::add_product($args);   
        $response->getBody()->write(json_encode($insert));
        $renderer = new PhpRenderer(ROOT . '/App/templates/template-parts/');
        return $renderer->render($response, "add-product.php");  
    } 
    $renderer = new PhpRenderer(ROOT . '/App/templates/template-parts/');
    return $renderer->render($response, "add-product.php"); 
    throw new HttpForbiddenException($request, "Failed to add product"); 

})->add(new CsrfProtection("add_product_form")); 

