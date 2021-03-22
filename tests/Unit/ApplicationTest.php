<?php

namespace Tests\Unit;

use Tests\TestCase;

use Faker\Provider\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Models\Address;
use App\Models\Announcement;
use App\Models\Application;
use App\Models\Role;
use App\Models\User;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_application_success_should_return_status_200()
    {
        $this->get('api/academic-industry/announcements')->assertStatus(200);
    }

    public function test_post_should_return_data_on_db()
    {
        $roleStd = Role::where('role_name', 'student')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);
    
        $mockData = [
            'my_user_id' => $user->user_id,
            'application_date' => '2021-02-04',
            'status' => 'OPEN',
            'note' => '-',
            'name_title' => 'นาย',
            'first_name' => 'ชาเขียว',
            'last_name' => 'มัทฉะ',
            'curriculum' => 'IT',
            'year' => '4',
            'tel_no' => '0956787294',
            'email' => 'mildHello@gmail.com',
            'resume_link' => '-',
            'path_file' => '-'
        ];
        $response = $this->postJson('api/academic-industry/application', $mockData);
        $response->assertStatus(200);

        $expect = json_decode($response->content(), true);
        $this->assertDatabaseHas('applications', [
            'application_id' => $expect['application_id'],
            'email' => $expect['email']
        ]);
    }

    public function test_get_application_by_id_should_return_data_on_db()
    {
        $response = $this->get('api/academic-industry/application/'.$this->fakerApp->application_id);
        $response->assertStatus(200);

        $this->assertEquals($response['application_id'], $this->fakerApp->application_id);
    }

    public function test_update_should_return_data_on_db_and_equal_mockData()
    {
        $roleStd = Role::where('role_name', 'student')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sitcoll@gmail.com'
        ]);

        $mockData = [
            'my_user_id' => $user->user_id,
            'application_date' => '2021-02-04',
            'status' => '-',
            'note' => '-',
            'name_title' => 'นาย',
            'first_name' => 'โอเลี้ยง',
            'last_name' => 'มัทฉะ',
            'curriculum' => 'IT',
            'year' => '4',
            'tel_no' => '0956787294',
            'email' => 'test@gmail.com',
            'resume_link' => '-',
            'path_file' => '-'
        ];

        $response = $this->postJson('api/academic-industry/application', $mockData);
        $response->assertStatus(200);

        $expect = json_decode($response->content(), true);
        $this->assertDatabaseHas('applications', [
            'application_id' => $expect['application_id'],
            'email' => $expect['email'],
            'first_name' => 'โอเลี้ยง'
        ]);
    }

    public function test_delete_application_by_id_should_not_return_data_on_db_that_is_deleted()
    {
        $response = $this->deleteJson('api/academic-industry/application/'.$this->fakerApp->application_id);
        $response = json_decode($response->content(), true);

        $this->assertEquals($response['message'], 'Application has been deleted.');
        $this->assertSoftDeleted('applications', [
            'application_id' => $this->fakerApp->application_id
        ]);
    }
}
