<?php 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class JwtHelper
{
    public static function generate($payload)
    {
        $key = getenv('JWT_SECRET');
        $payload['iat'] = time();
        $payload['exp'] = time() + (getenv('JWT_EXPIRY') ?? 3600);
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function verify($token)
    {
        try {
            $key = getenv('JWT_SECRET');
            return (array) JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getBearerToken()
    {
        $headers = getallheaders();
        if (!empty($headers['Authorization'])) {
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
?>