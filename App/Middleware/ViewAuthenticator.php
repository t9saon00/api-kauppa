<?php

namespace Slimapp\Middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slimapp\Classes\Session;

class ViewAuthenticator 
{
    public function __invoke(Request $request, RequestHandler $handler)
    {   
        if( Session::logged_in() ){
           
            return $handler->handle($request);
        } 

        Session::clear_all_session_data();
        
        $response = new Response(); 

        return $response->withHeader("Location", APP_BASEPATH . "/login")->withStatus(301);


    }
}