<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getUsers();
    public function getUserById($user_id);
    public function getUserByEmail($email);
    public function getUserByManager($data);
    public function createUserStudentByEmail($data, $role);
    public function createUser($data);
    public function createViewerUser($data);
    public function createUserByManger($data);
    public function updateUser($data);
    public function updateStaff($data);
    public function updateUserFirstTime($data);
    public function deleteUserByUserId($data);
    public function updateUserStudent($data, $role);
}
