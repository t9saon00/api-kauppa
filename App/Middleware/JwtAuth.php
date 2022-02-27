<?php

declare(strict_types=1);

namespace Slimapp\Middleware;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use slimapp\Sql\DbConnect as db;
use Slim\Exception\HttpForbiddenException;
use Slimapp\Classes\JwtClass;




class JwtAuth implements MiddlewareInterface
{

    public function process(Request $request, RequestHandler $handler): Response
    {
        
        $authorization_header = $request->getHeader('Authorization');
        

        if($authorization_header) {


            if (preg_match('/Bearer\s(\S+)/', $request->getHeaderLine('Authorization'), $matches)) {
                $token = $matches[1];
            }
            
            $token_auth = db::token_authorization($token);

            if($token_auth !== false){
                return $handler->handle($request);
            }
       
        }

        throw new HttpForbiddenException($request, "Wrong or expired token");  
    }
}




/*
    $auth_array = explode(" ", $authorization_header[0]);
    $un_pw = explode(":", base64_decode($auth_array[1]));
    $un = $un_pw[0];
    $secret = $un_pw[1];

    $users_array = db::user_authorization($un, $secret);
    
    if ($users_array !== false) {

        return $handler->handle($request);
    }
*/
