<?php

namespace Slimapp\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpForbiddenException;
use Slimapp\Classes\Session;

class CsrfProtection
{   
    private $action = false;

    public function __construct($action){
        $this->action = $action;
    }
    public function __invoke(Request $request, RequestHandler $handler)
    {   
        $body = $request->getParsedBody();
        $nonce = isset($body['_nonce']) ? $body['_nonce'] : false;
        if( !$this->action || !$nonce || !Session::validate_nonce($this->action, $nonce) ){
            throw new HttpForbiddenException($request, "Failed");
        } 
        return $handler->handle($request);
    }
} 