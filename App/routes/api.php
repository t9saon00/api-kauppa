<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;
use Slimapp\Classes\JwtClass;
use slimapp\Sql\DbConnect as db;
use Slimapp\Middleware\BasicAuth;
use Slimapp\Middleware\JwtAuth;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$app->group('/api/v1/', function (RouteCollectorProxy  $group) use ($views) {
    $group->post('post_example', function ($request, $response, $path_args) {
        $body = $request->getParsedBody();
        $response->getBody()->write(json_encode($body));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('get_example', function ($request, $response, $path_args) {
        $params = $request->getQueryParams();
        $response->getBody()->write(json_encode($params));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('get_products', function ($request, $response) { 
        $products = db::get_products(); 
        $response->getBody()->write(json_encode($products));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->post('add_product', function ($request, $response, $path_args) {  
        
        $body = $request->getParsedBody();
        $insert = db::add_product($body);
        $response->getBody()->write(json_encode($body));
        return $response->withHeader('Content-Type', 'application/json');
    });


})->add(new JwtAuth());

$app->get('/api/v1/get_token', function ($request, $response, $path_args) {
    $authorization_header = $request->getHeader('Authorization');
        
    if ( $authorization_header ) {
        $auth_array = explode(" ", $authorization_header[0]);
        $un_pw = explode(":", base64_decode($auth_array[1]));
        $un = $un_pw[0];
        $secret = $un_pw[1];

        $users_array = db::user_authorization($un, $secret);
        
        if ($users_array !== false) {
 
            $jwt_token = JwtClass::generate_jwt(array("consumer_key" => $un), $secret);
            db::add_token($un, $jwt_token['token']);  
            $response->getBody()->write(json_encode($jwt_token['token']));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }  

    throw new HttpForbiddenException($request, $un); 
});

	
$app->post('/api/v1/add_user', function ($request, $response, $path_args) {  
	
	$body = $request->getParsedBody();
	
	$user = ( isset($body['name']) ) ? $body['name'] : false;
    
    $users_check = db::user_check($user);
    
    if($users_check === true){  
        $args = $request->getParsedBody();
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $args['secret'] = $secret;
		$insert = db::add_user($args);
		$response->getBody()->write(json_encode($insert));
	}
	return $response->withHeader('Content-Type', 'application/json'); 
});


 