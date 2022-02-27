<?php

namespace Slimapp\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        ini_set('session.gc_maxlifetime', '28800');
        session_name('slimapp');
        session_set_cookie_params(['lifetime' => 28800, 'samesite' => 'Strict', 'secure' => boolval(PRODUCTION_MODE) ]);
        session_start();

        return $handler->handle($request);
    }
} 
  