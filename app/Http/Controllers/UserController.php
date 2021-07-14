<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\UserRepositoryInterface;
use App\Http\RulesValidation\UserRules;
use App\Traits\Utils;
use Throwable;

class UserController extends Controller
{
    use UserRules;
    use Utils;
    private $user;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->user = $userRepo;
    }

    public function get(Request $request)
    {
        $user = $this->user->getUsers();
        return response()->json($user, 200);
    }

    public function getUserById(Request $request, $user_id)
    {
        $user = $this->user->getUserById($user_id);
        return response()->json($user, 200);
    }

    public function getUserByManager(Request $request)
    {
        $user = $this->user->getUserByManager($request);
        return response()->json($user, 200);
    }

    public function create(Request $request)
    {
        try {
            $check_viewer_role = $this->CheckRoleViewer($request->role_id);
            if($check_viewer_role == 'viewer'){
                $validated = Validator::make($request->all(), $this->rulesCreationViewerUser);
                if ($validated->fails()) {
                    return response()->json($validated->messages(), 400);
                }
                $created = $this->user->createViewerUser($request);
                return response()->json([
                    "message" => $created
                ], 200);
            }else{
                $validated = Validator::make($request->all(), $this->rulesCreationUser);
                if ($validated->fails()) {
                    return response()->json($validated->messages(), 400);
                }
                $created = $this->user->createUser($request);
                return response()->json([
                    "message" => $created
                ], 200);
            }
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function createUserByManger(Request $request)
    {
        try {
            $created = $this->user->createUserByManger($request);
            return response()->json([
                "message" => $created
            ], 200);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), $this->rulesUpdateUser);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }
            $updated = $this->user->updateUser($request);
            return response()->json([
                "message" => "Update user successful."
            ], 200);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function updateFirstTime(Request $request)
    {
        try {
            $updated = $this->user->updateUserFirstTime($request);
            return response()->json([
                "message" => "Update user successful."
            ], 200);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->all();
        try {
            $deleted = $this->user->deleteUserByUserId($data);
            $message = $deleted;
            if ($deleted) {
                $message = 'User has been deleted.';
            }
            return response()->json([ "message" => $message ], 200);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
    }

}
