<?php

namespace App\helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class jwtAuth
{
    public function signup($email, $password, $getToken = false)
    {
        $user = User::where(['email' => $email, 'password' => $password])->first();
        if (!is_null($user) && is_object($user)) {
            $tokken = [
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            ];
            $jwt = JWT::encode($tokken, env('jwt_key'), 'HS256');
            $decoded = JWT::decode($jwt, env('jwt_key'), ['HS256']);
            if (!$getToken) {
                return $jwt;
            } else {
                return $decoded;
            }

        } else {
            return array('status' => 'error', 'message' => 'Login ha fallado');
        }
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $decoded = null;
        try {
            $decoded = JWT::decode($jwt, env('jwt_key'), ['HS256']);
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch (\DomainException $e) {
            return false;
        }
        if (!is_null($decoded) && is_object($decoded)) {
            if ($getIdentity) {
                return $decoded;
            }
            return true;
        }
        return false;
    }
}
