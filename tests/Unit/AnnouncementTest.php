<?php

namespace Tests\Unit;

use Tests\TestCase;

use Faker\Provider\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Models\Announcement;
use App\Models\JobType;
use App\Models\Address;
use App\Models\Role;
use App\Models\User;
use App\Models\DataOwner;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_announcements_success_should_return_status_200()
    {
        $this->get('api/academic-industry/announcements')->assertStatus(200);
    }

    public function test_post_announcement_success_should_return_announcement()
    {
        $data = $this->fakerAnnouncement->toArray();
        $user = $this->fakerUser->toArray();
        $company = $this->faker->toArray();

        $address = factory(Address::class)->create([
            'company_id' => $company['company_id'],
            'address_type' => 'announcement'
        ])->toArray();

        $jobType = factory(JobType::class)->create([
            'job_id' => Uuid::uuid(),
            'announcement_id' => $data['announcement_id']
            ]);

        $data['my_user_id'] = $user['user_id'];
        $data['job_type'] = $jobType['job_type'];
        $data['priority'] = '-';
        $data['end_date'] = '2021-03-31 17:00:00';
        $data = array_merge($data, $address);

        $response = $this->postJson('api/academic-industry/announcement', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $announcement = Announcement::find($response_arr['announcement_id']);
        $announcement_arr = $announcement->toArray()['announcement_id'];

        $this->assertEquals($announcement_arr, $response_arr['announcement_id']);
    }

    public function test_get_announcement_by_company_id_should_return_data_from_db()
    {
        $role = Role::where('role_name', 'manager')->first();
        $user = factory(User::class)->create([
            'role_id' => $role->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $data = $this->fakerAnnouncement->toArray();
        $company = $this->faker->toArray();

        $address = factory(Address::class)->create([
            'company_id' => $company['company_id'],
            'address_type' => 'announcement'
        ])->toArray();

        $dataOwner = factory(DataOwner::class)->create([
            'company_id' => $company['company_id'],
            'user_id' => $user['user_id'],
        ]);

        $jobType = factory(JobType::class)->create([
            'job_id' => Uuid::uuid(),
            'announcement_id' => $data['announcement_id']
        ]);

        $data['my_user_id'] = $user['user_id'];
        $data['job_type'] = $jobType['job_type'];
        $data['priority'] = '-';
        $data['end_date'] = '2021-03-31 17:00:00';
        $data = array_merge($data, $address);

        $response = $this->postJson('api/academic-industry/announcement', $data);
        $response->assertStatus(200);

        $response = $this->json('GET', 'api/academic-industry/company/announcements', ['my_user_id' => $user['user_id']]);
        $response->assertStatus(200);
        
        $responseGetByID = json_decode($response->content(), true);
        $this->assertDatabaseHas('announcements', [
            'announcement_id' => $responseGetByID[0]['announcement_id']
        ]);
    }

    public function test_post_announcement_fail_should_return_error_message()
    {
        //Field announcement_title and property are missing
        $data = [
            'company_id' => $this->faker->company_id,
            'job_description' => 'เป็นซอฟ์ตแวร์เอน เอนแบบเอนเตอร์เทน',
            'job_position_id' => $this->fakerJobPosition->job_position_id,
            'file_picture' => '',
            'picture' => '',
            'priority' => '-',
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => '2021-03-31 17:00:00',
            'salary' => '30,000',
            'welfare' => 'เงินดี ไม่ต้องแย่งลงทะเบียน',
            'status' => 'เปิดรับสมัคร',
            'job_type' => 'WiL',
            'address_one' => '138/2 พรีวิวหอพัก',
            'address_two' => '-',
            'lane' => '9',
            'road' => 'วิภาวดีรังสิต',
            'sub_district' => 'ดินแดง',
            'district' => 'ดินแดง',
            'province' => 'กรุงเทพ',
            'postal_code' => '10400',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '09:00',
            'end_business_time' => '18:00',
        ];

        $response = $this->postJson('api/academic-industry/announcement', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "announcement_title" => [
                "The announcement title field is required."
            ],
            "property" => [
                "The property field is required."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }

    public function test_update_announcement_success_should_return_announcement_that_has_been_updated()
    {
        $data = $this->fakerAnnouncement->toArray();
        $user = $this->fakerUser->toArray();
        $company = $this->faker->toArray();

        $address = factory(Address::class)->create([
            'company_id' => $company['company_id'],
            'address_type' => 'announcement'
        ])->toArray();

        $jobType = factory(JobType::class)->create([
            'job_id' => Uuid::uuid(),
            'announcement_id' => $data['announcement_id']
        ]);

        $data['my_user_id'] = $user['user_id'];
        $data['announcement_title'] = 'รับสมัครงานตำแหน่ง UX/UI';
        $data['job_description'] = 'ต้องการ UX/UI';
        $data['job_type'] = $jobType['job_type'];
        $data['priority'] = '-';
        $data['end_date'] = '2021-03-31 17:00:00';
        $data = array_merge($data, $address);

        $response = $this->putJson('api/academic-industry/announcement', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $announcement = Announcement::find($response_arr['announcement_id']);
        $announcement_arr = $announcement->toArray();

        $this->assertEquals($announcement_arr['announcement_id'], $response_arr['announcement_id']);
        $this->assertEquals($announcement_arr['job_description'], $response_arr['job_description']);
    }

    public function test_update_announcement_fail_should_return_error_message()
    {
        $jobType = factory(JobType::class)->create([
            "announcement_id" => $this->fakerAnnouncement->announcement_id,
            "job_id" => Uuid::uuid()
        ]);

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
        ]);

        //Filed announcement_id (pk), property and postal_code are missing
        $data = [
            'announcement_title' => 'รับสมัครงานตำแหน่ง UX/UI',
            'job_description' => 'ต้องการ UX/UI',
            'job_position_id' => $this->fakerJobPosition->job_position_id,
            'file_picture' => '',
            'picture' => '',
            'priority' => '-',
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => '2021-03-31 17:00:00',
            'salary' => '30,000',
            'welfare' => 'เงินดี ไม่ต้องแย่งลงทะเบียน',
            'status' => 'เปิดรับสมัคร',
            'job_type' => 'WiL',
            'address_one' => '138/2 พรีวิวหอพัก',
            'address_two' => '-',
            'lane' => '9',
            'road' => 'วิภาวดีรังสิต',
            'sub_district' => 'ดินแดง',
            'district' => 'ดินแดง',
            'province' => 'กรุงเทพ',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '09:00',
            'end_business_time' => '18:00',
        ];

        $response = $this->putJson('api/academic-industry/announcement', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "announcement_id" => [
                "The announcement id field is required."
            ],
            "address_id" => [
                "The address id field is required."
            ],
            "property" => [
                "The property field is required."
            ],
            "postal_code" => [
                "The postal code field is required."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }

    public function test_delete_announcement_by_id_success_should_return_true()
    {
        $data = $this->fakerAnnouncement;

        $jobType = factory(JobType::class)->create([
            "announcement_id" => $data['announcement_id'],
            "job_id" => Uuid::uuid()
        ]);

        $address = factory(Address::class)->create([
            'company_id' => $this->faker->company_id,
            'address_type' => 'announcement'
        ]);

        $get_announcement_id = [
            'announcement_id' => $data['announcement_id']
        ];

        $expected_announcement = true;

        $response = $this->deleteJson('api/academic-industry/announcement', $get_announcement_id);
        $response_arr = json_decode($response->content(), true);
        $this->assertEquals($response_arr, $expected_announcement);
    }

    public function test_delete_announcement_by_id_fail_should_return_fail_message()
    {
        $id = [
            'announcement_id' => $this->fakerAnnouncement->announcement_id
        ];

        $expected_announcement = 'Find not found announcement or job type or address or business day.';

        $response = $this->deleteJson('api/academic-industry/announcement', $id);
        $response_arr = json_decode($response->content(), true);
        $this->assertEquals($response_arr, $expected_announcement);
    }
}
