<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getUsers();
    public function getUserById($user_id);
    public function getUserByEmail($email);
    public function createUserฺStudentByEmail($data, $role);
    public function createUser($data);
    public function updateUser($data);
    public function deleteUserByUserId($user_id);
}
