<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;
use Slimapp\Classes\Session;
use Slimapp\Middleware;

$views = get_views();
$menu = get_menu();

$app->group('', function (RouteCollectorProxy  $group) use ($views) {
    foreach ($views as $view) {
        $route = $group->get($view["path"], function ($request, $response, $path_args) use ($view) {
            $renderer = new PhpRenderer(ROOT . '/App/templates/'); 
            $args = [
                "args" => [ 
                    "path_args" => $path_args,
                    "view_args" => $view
                ]
            ]; 
            return $renderer->render($response, $view["template"], $args);
        });
    };

    $group->get('/logout', function (Request $request, Response $response, $args){
        Session::clear_all_session_data();
        return $response->withHeader('Location', APP_BASEPATH . '/login')->withStatus(301);
    });


})->add(new Middleware\SessionMiddleware())->add(new Middleware\ViewAuthenticator());

