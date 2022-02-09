<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth
{
    public function validateJWTFromRequest(string $encodedToken)
    {
        $key = getenv('JWT_SECRET_KEY');
        $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
        return $decodedToken;
    }

    public function getSignedJWTForUser(string $email)
    {
        $issuedAtTime = time();
        $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
        $tokenExpiration = $issuedAtTime + $tokenTimeToLive;

        $payload = [
            "iss" => base_url(),
            "aud" => base_url(),
            'email' => $email,
            'iat' => $issuedAtTime,
            'exp' => $tokenExpiration,
        ];

        $jwt = JWT::encode($payload, getenv('JWT_SECRET_KEY'), 'HS256');
        return $jwt;
    }
}
