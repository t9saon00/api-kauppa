<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use slimapp\Sql\DbConnect as db;
use Slim\Views\PhpRenderer;
use Slimapp\Classes\JwtClass;
use Slimapp\Classes\Session;
use Slimapp\Middleware\CsrfProtection;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$app->get("/secret", function ( $request, $response ) {
    $renderer = new PhpRenderer(ROOT . '/App/templates/');
    return $renderer->render($response, "secret.php");
});

$app->post("/secret", function ( $request, $response ){ 
    
    $body = $request->getParsedBody();

    $user = $_SESSION['user'];
    $secret_input = ( isset($body['secret']) ) ? $body['secret'] : false;

    $valid_secret = false;

    $g = new GoogleAuthenticator();
        
    $secret = db::get_secret($user);
    
    if ($g->checkCode($secret, $secret_input)) {
        $valid_secret = true;
    } else {
        $valid_secret = false;
    } 
    
    if($valid_secret && !empty($valid_secret) ){
        $user_data = db::get_user_data($user);

        Session::log_in_user($user_data);
        return $response 
            ->withHeader('Location', APP_BASEPATH . '/' )
            ->withStatus(301);
    } else {
        $renderer = new PhpRenderer(ROOT . '/App/templates/');
        return $renderer->render($response, "login.php"); 
    } 

})->add(new CsrfProtection("secret_form"));