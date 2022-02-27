<?php

namespace Slimapp\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class LoginMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {

        return $handler->handle($request);
    }
} 
  