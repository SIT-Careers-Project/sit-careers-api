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

        $data = [
            'company_id' => $this->faker->company_id,
            'announcement_title' => 'รับสมัครงานตำแหน่ง Software Engineer',
            'job_description' => 'เป็นซอฟ์ตแวร์เอน เอนแบบเอนเตอร์เทน',
            'job_position_id' => $this->fakerJobPosition->job_position_id,
            'property' => 'ขยันเป็นพอ',
            'priority' => '-',
            'file_picture' => '',
            'picture' => '',
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => '2049-03-31 17:00:00',
            'salary' => '30,000',
            'welfare' => 'เงินดี ไม่ต้องแย่งลงทะเบียน',
            'status' => 'CLOSE',
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
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $announcement = Announcement::find($response_arr['announcement_id']);
        $announcement_arr = $announcement->toArray()['announcement_id'];

        $mockData = [
            'announcement_id' => $announcement_arr,
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
            'announcement_id' => $expect['announcement_id'],
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

        
        $data = [
            'company_id' => $this->faker->company_id,
            'announcement_title' => 'รับสมัครงานตำแหน่ง Software Engineer',
            'job_description' => 'เป็นซอฟ์ตแวร์เอน เอนแบบเอนเตอร์เทน',
            'job_position_id' => $this->fakerJobPosition->job_position_id,
            'property' => 'ขยันเป็นพอ',
            'priority' => '-',
            'file_picture' => '',
            'picture' => '',
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => '2049-03-31 17:00:00',
            'salary' => '30,000',
            'welfare' => 'เงินดี ไม่ต้องแย่งลงทะเบียน',
            'status' => 'CLOSE',
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
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $announcement = Announcement::find($response_arr['announcement_id']);
        $announcement_arr = $announcement->toArray()['announcement_id'];

        $mockData = [
            'announcement_id' => $announcement_arr,
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
            'announcement_id' => $expect['announcement_id'],
            'email' => $expect['email'],
            'first_name' => 'โอเลี้ยง'
        ]);
    }


    public function test_create_expire_date_of_application_should_not_application_and_return_202()
    {
        $roleStd = Role::where('role_name', 'student')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sitcoll@gmail.com'
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'announcement_title' => 'รับสมัครงานตำแหน่ง Software Engineer',
            'job_description' => 'เป็นซอฟ์ตแวร์เอน เอนแบบเอนเตอร์เทน',
            'job_position_id' => $this->fakerJobPosition->job_position_id,
            'property' => 'ขยันเป็นพอ',
            'priority' => '-',
            'file_picture' => '',
            'picture' => '',
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => '2021-02-28 17:00:00',
            'salary' => '30,000',
            'welfare' => 'เงินดี ไม่ต้องแย่งลงทะเบียน',
            'status' => 'OPEN',
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
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $announcement = Announcement::find($response_arr['announcement_id']);
        $announcement_arr = $announcement->toArray()['announcement_id'];

        $mockData = [
            'announcement_id' => $announcement_arr,
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
        $response->assertStatus(202);

        $actual = json_decode($response->content(), true);
        $this->assertDatabaseHas('announcements', [
            'announcement_id' => $announcement_arr,
            'status' => 'CLOSE'
        ]);

        $expect = 'Can not application, because It was expired for application.';
        $this->assertEquals($actual['message'], $expect);
    }

    public function test_update_expire_date_of_application_should_not_application_and_return_202()
    {
        $roleStd = Role::where('role_name', 'student')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sitcoll@gmail.com'
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'announcement_title' => 'รับสมัครงานตำแหน่ง Software Engineer',
            'job_description' => 'เป็นซอฟ์ตแวร์เอน เอนแบบเอนเตอร์เทน',
            'job_position_id' => $this->fakerJobPosition->job_position_id,
            'property' => 'ขยันเป็นพอ',
            'priority' => '-',
            'file_picture' => '',
            'picture' => '',
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => '2021-02-28 17:00:00',
            'salary' => '30,000',
            'welfare' => 'เงินดี ไม่ต้องแย่งลงทะเบียน',
            'status' => 'OPEN',
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
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $announcement = Announcement::find($response_arr['announcement_id']);
        $announcement_arr = $announcement->toArray()['announcement_id'];

        $mockData = [
            'announcement_id' => $announcement_arr,
            'student_id' => $user->user_id,
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

        $response = $this->putJson('api/academic-industry/application', $mockData);
        $response->assertStatus(202);

        $actual = json_decode($response->content(), true);
        $this->assertDatabaseHas('announcements', [
            'announcement_id' => $announcement_arr,
            'status' => 'CLOSE'
        ]);

        $expect = 'Can not update application, because It was expired for application.';
        $this->assertEquals($actual['message'], $expect);
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
