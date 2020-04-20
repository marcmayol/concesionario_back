<?php

namespace App\Http\Controllers;

use App\helpers\jwtAuth;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $status = 200;
        if (!is_null($request->input('email')) && !is_null($request->input('password')) && !is_null($request->input('name')) && !is_null($request->input('surname'))) {
            $user = new User();
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->role = 'ROLE_USER';
            $user->password = hash('sha256', $request->input('password'));
            $isset_user = User::where('email', $request->input('email'))->first();
            if (is_null($isset_user)) {
                $user->save();
                $data = [
                    'status' => 'succes',
                    'code' => 200,
                    'message' => 'usuario registrado correctamente'
                ];
            } else {
                $status = 400;
                $data = [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'El Usuario ya existe'
                ];
            }

        } else {
            $status = 400;
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario no crado'
            ];

        }
        return response()->json($data, $status);
    }

    public function login(Request $request)
    {
        $jwAuth = new jwtAuth();
        $token = $request->input('gettoken');
        if (!is_null($request->input('email')) && !is_null($request->input('password'))) {
            $getToken = (isset($token)) ? $token : false;
            $pwd = hash('sha256', $request->input('password'));
            if ($getToken === "true") {
                return response()->json($jwAuth->signup($request->input('email'), $pwd, $getToken), 200);
            } else {
                return response()->json($jwAuth->signup($request->input('email'), $pwd), 200);
            }
        }

        return response()->json(
            ["status" => "error",
                "message" => "missing params"
            ], 400.6);
    }

    public function index()
    {
        echo 'Index de carController';
        die();
    }
}
