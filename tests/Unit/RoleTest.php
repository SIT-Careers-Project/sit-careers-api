<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_roles_should_return_status_200()
    {
        $this->get('api/roles')->assertStatus(200);
    }

    public function test_get_all_role_permissions_should_return_status_200()
    {
        $this->get('api/role-permissions')->assertStatus(200);
    }

}
