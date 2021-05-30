<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Models\Role;
use App\Models\User;

class ResumeTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_resume_success_should_return_status_200()
    {
        $this->get('api/academic-industry/resumes')->assertStatus(200);
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
            'resume_date' => '2021-02-04',
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
        $response = $this->postJson('api/academic-industry/resume', $mockData);
        $response->assertStatus(200);

        $expect = json_decode($response->content(), true);
        $this->assertDatabaseHas('resumes', [
            'resume_id' => $expect['resume_id'],
            'email' => $expect['email']
        ]);
    }

    public function test_post_duplicate_user_id_should_return_error_message()
    {
        $roleStd = Role::where('role_name', 'student')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $mockData = [
            'my_user_id' => $user->user_id,
            'resume_date' => '2021-02-04',
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

        $response = $this->postJson('api/academic-industry/resume', $mockData);
        $response_dup = $this->postJson('api/academic-industry/resume', $mockData);

        $expect = json_decode($response_dup->content(), true);

        $assertion = [
            "my_user_id" => [
                "The my user id has already been taken."
                ]
            ];

        $response_dup->assertStatus(400);
        $this->assertEquals($assertion, $expect);
    }

    public function test_get_resume_by_id_should_return_data_on_db()
    {
        $response = $this->get('api/academic-industry/resume/'.$this->fakerResume->resume_id);
        $response->assertStatus(200);

        $this->assertEquals($response['resume_id'], $this->fakerResume->resume_id);
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
            'resume_date' => '2021-02-04',
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

        $response = $this->postJson('api/academic-industry/resume', $mockData);
        $response->assertStatus(200);

        $expect = json_decode($response->content(), true);
        $this->assertDatabaseHas('resumes', [
            'resume_id' => $expect['resume_id'],
            'email' => $expect['email'],
            'first_name' => 'โอเลี้ยง'
        ]);
    }

    public function test_delete_resume_by_id_should_not_return_data_on_db_that_is_deleted()
    {
        $resume = $this->fakerResume;
        $response = $this->deleteJson('api/academic-industry/resume/'.$resume['resume_id']);
        $response = json_decode($response->content(), true);
        $this->assertEquals($response['message'], 'Resume has been deleted.');
        $this->assertSoftDeleted('resumes', [
            'resume_id' => $resume['resume_id']
            ]);
        }
}
