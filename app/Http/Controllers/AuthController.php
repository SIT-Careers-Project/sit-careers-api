<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\RolePermissionRepositoryInterface;

use \Firebase\JWT\JWT;

class AuthController extends Controller
{
    private $auth;
    private $role_permission;

    public function __construct(AuthRepositoryInterface $auth, RolePermissionRepositoryInterface $permission_repo)
    {
        $this->auth = $auth;
        $this->role_permission = $permission_repo;
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $user = $this->auth->getUser($data);
        $un_auth = response()->json(
            [
                'message' => 'Invalid username or password',
            ],
            401
        );
        if ($user) {
            if (Hash::check($data['password'], $user->password)) {
                $token = $this->encode($user, env('JWT_KEY'));
                unset($user->password);
                unset($user->created_at);
                unset($user->updated_at);
                $permissions = $this->role_permission->getRolePermissionsByUserId($user['user_id']);
                return response()->json(
                    [
                        'message' => 'login success',
                        'user' => $user,
                        'token' => $token,
                        'permission' => $permissions
                    ],
                    200
                );
            } else {
                return $un_auth;
            }
        } else {
            return $un_auth;
        }
    }

    public function me(Request $request)
    {
        $data = $request->all();
        $data['username'] = $data['my_username'];
        $user = $this->auth->getUser($data);
        if ($user) {
            unset($user->password);
            unset($user->created_at);
            unset($user->updated_at);
            $permissions = $this->role_permission->getRolePermissionsByUserId($user['user_id']);
            return response()->json(
                [
                    'message' => 'login success',
                    'user' => $user,
                    'permission' => $permissions
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'message' => 'User not Found',
                ],
                401
            );
        }
    }

    public function encode($user, $sub_type)
    {
        $payload = array([
            'sub'   => $sub_type,
            'my_username' => $user->username,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id
        ]);

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return $token;
    }
}