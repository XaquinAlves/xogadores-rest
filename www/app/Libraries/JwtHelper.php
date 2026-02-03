<?php

declare(strict_types=1);
namespace Com\Daw2\Libraries;

use Ahc\Jwt\JWT;

class JwtHelper {

    private const ALGO = 'HS256';
    private const KEY = '#1234abc';
    private const EXPIRE = 1800;
    private const LEEWAY = 10;

    public static function decode(string $token): array
    {
        $jwt = new JWT(self::KEY, self::ALGO, self::EXPIRE, self::LEEWAY);
        return $jwt->decode($token);
    }

    public static function encode(array $payload): string
    {
        $jwt = new JWT(self::KEY, self::ALGO, self::EXPIRE, self::LEEWAY);
        return $jwt->encode($payload);
    }
}
