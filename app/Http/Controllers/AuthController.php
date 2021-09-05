<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\RolePermissionRepositoryInterface;
use App\Repositories\UserRepositoryInterface;

use \Firebase\JWT\JWT;

class AuthController extends Controller
{
    private $auth;
    private $role_permission;

    public function __construct(AuthRepositoryInterface $auth, RolePermissionRepositoryInterface $permission_repo, UserRepositoryInterface $user_repo)
    {
        $this->auth = $auth;
        $this->role_permission = $permission_repo;
        $this->user = $user_repo;
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
                        'permissions' => $permissions
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

    public function SITLogin(Request $request)
    {
        $data = $request->all();
        $url = config('app.SIT_SSO_URL').'/oauth/token?client_secret='.config('app.SIT_SSO_SECRET').'&client_id='.config('app.SIT_SSO_CLIENT_ID').'&code='.$data['code'].'&redirect_uri='.config('app.SIT_SSO_REDIRECT');
        $response = Http::get($url);

        if ($response->successful() && $data['state'] == config('app.SIT_SSO_STATE')) {
            $body = json_decode($response->body());
            $user = $this->user->getUserByEmail($body->email);
            if (is_null($user)) {
                if ($body->user_type === 'st_group') {
                    $user = $this->user->createUserStudentByEmail($body, 'student');
                } else {
                    return response()->json(
                        [
                            'message' => 'Unauthorized',
                        ],
                        401
                    );
                }
            } else {
                $this->user->updateUserStudent($body, 'student');
            }
            $token = $this->encode($user, env('JWT_KEY'));
            $permissions = $this->role_permission->getRolePermissionsByUserId($user['user_id']);
            return response()->json(
                [
                    'message' => 'login success',
                    'user' => $user,
                    'token' => $token,
                    'permissions' => $permissions
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'message' => 'Unauthorized',
                ],
                401
            );
        }
    }

    public function me(Request $request)
    {
        $data = $request->all();
        $data['username'] = $data['my_username'];
        $user = $this->auth->getUser($data);
        if ($user) {
            if ($user->token) {
                $url = 'https://gatewayservice.sit.kmutt.ac.th/api/me';
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$user->token
                ])->get($url);
                $body = json_decode($response->body());
                if ($response->failed()) {
                    return response()->json(
                        [
                            'message' => $body->message
                        ],
                        401
                    );
                }
            }
            $permissions = $this->role_permission->getRolePermissionsByUserId($user['user_id']);
            return response()->json(
                [
                    'message' => 'login success',
                    'user' => $user,
                    'permissions' => $permissions
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

    public function verify(Request $request)
    {
        $user = $this->user->getUserById($request->user_id);
        if ($user->status == "active") {
            return response()->json(['message'=>'This account are verified.'], 204);
        }
        if (!is_null($user) && $request->hasValidSignature()){
            $user->password = null;
            $this->user->updateUser($user);
            $token = $this->encode($user, env('JWT_KEY'));
            return response([
                'message'=>'Successfully verified',
                'token' => $token
            ], 200);
        }
        return response()->json(['message'=>'This link has been expired. Please contact Admin.'], 400);
    }
}
