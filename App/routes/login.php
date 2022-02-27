<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use slimapp\Sql\DbConnect as db;
use Slim\Views\PhpRenderer;
use Slimapp\Classes\Session;
use Slimapp\Middleware\CsrfProtection;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$app->get("/login", function ( $request, $response ) {
    $renderer = new PhpRenderer(ROOT . '/App/templates/');
    return $renderer->render($response, "login.php");
});

$app->post("/login", function ( $request, $response ){
    
    $body = $request->getParsedBody();

    $user = ( isset($body['name']) ) ? $body['name'] : false;
    $pass = ( isset($body['password']) ) ? $body['password'] : false;
    $secret_input = ( isset($body['secret']) ) ? $body['secret'] : false;

    $valid_user = false;
    $valid_secret = false;
    
    $users_array = db::user_authorization($user, $pass);

    if ($users_array !== false) {
        $valid_user = true; 
    } 

    if($valid_user && !empty($valid_user)){
        $g = new GoogleAuthenticator();
        $secret = db::get_secret($user);
        $code = $g->getCode($secret);
        $qr_url = GoogleQrUrl::generate($user, $secret, 'SlimApp', 200);
        echo '<a class="google-qr-link" target="_blank" href="' . $qr_url . '">Secret link</a>';
    }

    if($valid_user && !empty($valid_user) ){
        Session::set_user($user);
        $renderer = new PhpRenderer(ROOT . '/App/templates/');
        return $renderer->render($response, "secret.php"); 
    } else {
        $renderer = new PhpRenderer(ROOT . '/App/templates/');
        return $renderer->render($response, "login.php"); 
    } 

})->add(new CsrfProtection("login_form"));