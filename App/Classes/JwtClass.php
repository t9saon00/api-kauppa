<?php

declare(strict_types=1);


namespace Slimapp\Classes;

use DateTimeImmutable;
use Firebase\JWT\JWT; 
 
class JwtClass {

    public static function generate_jwt($data, $secret){
        $tokenId    = base64_encode(random_bytes(16));
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+30 minutes')->getTimestamp(); 
        $serverName = "http://slimapp:8888";
    
        // Create the token as an array
        $data = [
            'iat'  => $issuedAt->getTimestamp(), 
            'jti'  => $tokenId, 
            'iss'  => $serverName,  
            'nbf'  => $issuedAt->getTimestamp(),
            'exp'  => $expire,  
            'data' => $data
        ];
    
        // Encode the array to a JWT string.
        $token = JWT::encode( 
            $data, 
            $secret, 
            'HS512' 
        );

        return array("token"=> $token, "expiration" => $expire);
    }
}
