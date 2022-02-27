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
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$app->post("/add-user", function ( $request, $response ){

    $body = $request->getParsedBody(); 
    print_r($body);
    $user = ( isset($body['name']) ) ? $body['name'] : false;
    $pass = ( isset($body['password']) ) ? $body['password'] : false;
    
    $users_check = db::user_check($user);
    
    if($users_check === true){  
        $args = $request->getParsedBody();

        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $args['secret'] = $secret;

        $insert = db::add_user($args);  
        $response->getBody()->write(json_encode($insert));
        //return $response->withHeader('Content-Type', 'application/json');
        $renderer = new PhpRenderer(ROOT . '/App/templates/');
        return $renderer->render($response, "login.php"); 
        print_r('User created succesfully, login');
    } 
    $g = new GoogleAuthenticator();
    $renderer = new PhpRenderer(ROOT . '/App/templates/');
    return $renderer->render($response, "login.php"); 
    //print_r('Username already taken');

    throw new HttpForbiddenException($request, "Username already taken");

})->add(new CsrfProtection("add_user_form")); 

