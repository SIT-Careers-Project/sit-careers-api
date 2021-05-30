<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Faker\Provider\Uuid;

use App\Http\Controllers\UserController;
use App\Repositories\UserRepository;

use App\Models\User;
use App\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_user_success_should_return_status_200()
    {
        $this->get('api/admin/users')->assertStatus(200);
        $this->get('api/company/users')->assertStatus(200);
    }

    public function test_post_user_by_admin_should_return_data_on_db()
    {
        $roleAdmin = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleAdmin->role_id,
            'username' => 'test23',
            'password' => '123',
            'first_name' => 'Hello',
            'last_name' => 'Noita',
            'email' => 'hellotest@mail.com'
        ]);
        $mockData = [
            'role_id' => $roleAdmin->role_id,
            'username' => 'mild',
            'password' => '123',
            'first_name' => 'Tassaneeewan',
            'last_name' => 'Noita',
            'email' => 'test@mail.com',
            'created_by' => '',
            'my_user_id' => $user->user_id
        ];
        $response = $this->postJson('api/user', $mockData);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);

        $this->assertEquals($response_arr['message'], 'Create user successful.');
    }

    public function test_post_user_by_manager_should_return_data_on_db()
    {
        $role = Role::where('role_name', 'manager')->first();
        $user = factory(User::class)->create([
            'role_id' => $role->role_id,
            'username' => 'test23',
            'password' => '123',
            'first_name' => 'Hello',
            'last_name' => 'Noita',
            'email' => 'testpostby.manager@mail.com'
        ]);
        $mockData = [
            'email' => 'testpostuserbymanager@gmail.com',
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id
        ];
        $response = $this->postJson('api/company/user', $mockData);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);

        $this->assertEquals($response_arr['message'], 'Create user successful.');
        $this->assertDatabaseHas('users', [
            'email' => $mockData['email'],
        ]);
    }


    public function test_get_user_by_id_success_should_return_data()
    {
        $roleAdmin = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleAdmin->role_id,
            'username' => 'test23',
            'password' => '123',
            'first_name' => 'Hello',
            'last_name' => 'Noita',
            'email' => 'hellotest@mail.com'
        ]);


        $responseGetByID = $this->get('api/user/'.$user->user_id);
        $responseGetByID = json_decode($responseGetByID->content(), true);
        $this->assertDatabaseHas('users', [
            'user_id' => $responseGetByID['user_id'],
        ]);
    }

    public function test_update_user_success_should_return_data()
    {
        $roleAdmin = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleAdmin->role_id,
            'username' => 'yuiyu',
            'password' => '123',
            'first_name' => 'Hello',
            'last_name' => 'Noita',
            'status' => 'deactivate',
            'email' => 'hellotest@mail.com'
        ]);

        $mockData = [
            'user_id' => $user->user_id,
            'role_id' => $roleAdmin->role_id,
            'username' => 'testUpdate123',
            'password' => '12334',
            'first_name' => 'Tassaneeewan',
            'last_name' => 'Noita',
            'status' => 'active',
            'email' => '435@mail.com',
            'created_by' => '-'
        ];

        $response = $this->putJson('api/user', $mockData);
        $response->assertStatus(200);


        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'role_id' => $roleAdmin->role_id,
            'username' => 'testUpdate123',
            'first_name' => 'Tassaneeewan'
        ]);
    }

    public function test_soft_delete_user_success_by_user_id()
    {
        $data = [
            "data" => array([
                "user_id" => $this->fakerUser->user_id
            ])
        ];
        $response = $this->deleteJson('api/users', $data);
        $response = json_decode($response->content(), true);

        $this->assertEquals($response['message'], 'User has been deleted.');
        $this->assertSoftDeleted('users', [
            'user_id' => $this->fakerUser->user_id
        ]);
    }
}
