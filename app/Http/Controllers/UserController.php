<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Repositories\UserRepositoryInterface;
use App\Http\RulesValidation\UserRules;


class UserController extends Controller 
{
    use UserRules;
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

    public function create(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), $this->rulesCreationUser);
            if ($validated->fails()) {
                return response()->json($validated->messages(), 400);
            }
            $created = $this->user->createUser($request);
            return response()->json([
                "message" => $created
            ], 200);
        } catch (\Throwable $th) {
            return "Something Wrong: " . $th;
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
        } catch (\Throwable $th) {
            return "Something Wrong: " . $th;
        }
    }

    public function destroy(Request $request, $user_id)
    {
        try {
            $deleted = $this->user->deleteUserByUserId($user_id);
            $message = $deleted;
            if ($deleted) {
                $message = 'User has been deleted.';
            }
            return response()->json([ "message" => $message ], 200);
        } catch (\Throwable $th) {
            return "Something Wrong: " . $th;
        }
    }

}