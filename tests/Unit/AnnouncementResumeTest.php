<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Announcement;
use App\Models\JobType;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AnnouncementResumeTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_announcement_resumes()
    {
        $this->getJson('api/academic-industry/admin/applications')->assertStatus(200);
    }

    public function test_get_announcement_resume_by_user_id()
    {
        $id = [
            'my_user_id' => $this->fakerUser->user_id
        ];

        $this->call('GET', 'api/academic-industry/student/applications', $id)->assertStatus(200);
    }

    public function test_get_announcement_resume_by_company_id()
    {
        $response = $this->json('GET', 'api/academic-industry/company/applications', ['my_user_id' => $this->fakerDataOwner->user_id]);
        $response->assertStatus(200);
    }

    public function test_get_announcement_resume_id()
    {
        $id = $this->fakerAnnouncementResume->announcement_resume_id;
        $this->getJson('api/academic-industry/admin/application/'.$id)->assertStatus(200);
    }

    public function test_get_announcement_resume_id_by_company_id()
    {
        $announcement = [
            'announcement_id' => $this->fakerAnnouncement->announcement_id
        ];

        $data = $this->fakerAnnouncementResume;
        $data['announcement_id'] = $announcement['announcement_id'];

        $user_company = [
            'my_user_id' => $this->fakerDataOwner->user_id,
        ];

        $response = $this->json('GET', 'api/academic-industry/company/application/'.$data['announcement_resume_id'], $user_company);
        $response->assertStatus(200);
    }

    public function test_get_announcement_resume_id_by_user_id()
    {
        $id = $this->fakerAnnouncementResume->announcement_resume_id;
        $user = [
            'my_user_id' => $this->fakerUser->user_id
        ];

        $response = $this->json('GET', 'api/academic-industry/student/application/'.$id, $user);
        $response->assertStatus(200);
    }

    public function test_post_announcement_resume_success_should_return_announcement_resume()
    {
        $company = $this->faker->toArray();

        $address = factory(Address::class)->create([
            'company_id' => $company['company_id'],
            'address_type' => 'announcement'
        ]);

        $announcement = factory(Announcement::class)->create([
            'company_id' => $company['company_id'],
        ]);

        $jobType = factory(JobType::class)->create([
            'announcement_id' => $announcement['announcement_id'],
            'job_id' => Uuid::uuid()
        ]);

        $data = $this->fakerAnnouncementResume->toArray();
        $data['resume_id'] = $this->fakerResume->resume_id;
        $data['announcement_id'] = $announcement['announcement_id'];

        $response = $this->postJson('/api/academic-industry/application', $data);
        $response->assertStatus(200);

        $expected = json_decode($response->content(), true);
        $this->assertDatabaseHas('announcement_resumes', [
            'announcement_resume_id' => $expected['announcement_resume_id']
        ]);
    }

    public function test_post_exist_resume_on_announcement_resume_failed_should_return_error_message()
    {
        $company = $this->faker->toArray();

        $address = factory(Address::class)->create([
            'company_id' => $company['company_id'],
            'address_type' => 'announcement'
        ]);

        $announcement = factory(Announcement::class)->create([
            'company_id' => $company['company_id'],
        ]);

        $jobType = factory(JobType::class)->create([
            'announcement_id' => $announcement['announcement_id'],
            'job_id' => Uuid::uuid()
        ]);

        $data = $this->fakerAnnouncementResume->toArray();

        $response = $this->postJson('/api/academic-industry/application', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "message" => "Resume id has already exist"
            ];

        $response->assertStatus(409);
        $this->assertEquals($assertion, $expected);
    }

    public function test_post_annoucement_resume_due_date_of_announcement_should_return_error_message()
    {
        $company = $this->faker->toArray();

        $address = factory(Address::class)->create([
            'company_id' => $company['company_id'],
            'address_type' => 'announcement'
        ]);

        $announcement = factory(Announcement::class)->create([
            'company_id' => $company['company_id'],
            'start_date' => '2021-01-10 13:00:00',
            'end_date' => Carbon::now()->subDay()
        ]);

        $jobType = factory(JobType::class)->create([
            'announcement_id' => $announcement['announcement_id'],
            'job_id' => Uuid::uuid()
        ]);

        $data = $this->fakerAnnouncementResume->toArray();
        $data['announcement_id'] = $announcement['announcement_id'];

        $response = $this->postJson('/api/academic-industry/application', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "message" => "Can not application, because It has expired for application."
        ];

        $response->assertStatus(202);
        $this->assertEquals($assertion, $expected);
    }

    public function test_update_announcement_resume_success_should_return_announcement_resume_that_has_been_updated()
    {
        $announcement = $this->fakerAnnouncement->toArray();

        $data = $this->fakerAnnouncementResume->toArray();
        $data['note'] = 'update note';
        $data['announcement_id'] = $announcement['announcement_id'];

        $response = $this->putJson('/api/academic-industry/application', $data);
        $response->assertStatus(200);

        $expected = json_decode($response->content(), true);
        $this->assertDatabaseHas('announcement_resumes', [
            'announcement_resume_id' => $expected['announcement_resume_id'],
            'note' => $expected['note']
        ]);
    }

    public function test_update_announcement_resume_failed_should_return_error_message()
    {
        $announcement = $this->fakerAnnouncement->toArray();

        $data = $this->fakerAnnouncementResume->toArray();
        $data = array_diff($data, array($data['announcement_resume_id']));
        $data['announcement_id'] = $announcement['announcement_id'];

        $response = $this->putJson('/api/academic-industry/application', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "announcement_resume_id" => [
                "The announcement resume id field is required."
                ]
            ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }
}
