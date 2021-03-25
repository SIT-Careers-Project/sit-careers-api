<?php

namespace Tests\Unit;

use App\Models\AnnouncementResume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AnnouncementResumeTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_announcement_resume_by_user_id()
    {
        $id = [
            'my_user_id' => $this->fakerUser->user_id
        ];

        $this->call('GET', 'api/academic-industry/resume', $id)->assertStatus(200);
    }

    public function test_post_annoucement_resume_success_should_return_announcement_resume()
    {
        $announcement = $this->fakerAnnouncement->toArray();

        $data = $this->fakerAnnouncementResume->toArray();
        $data['announcement_id'] = $announcement['announcement_id'];

        $response = $this->postJson('/api/academic-industry/application', $data);
        $response->assertStatus(200);

        $expected = json_decode($response->content(), true);
        $this->assertDatabaseHas('announcement_resumes', [
            'announcement_resume_id' => $expected['announcement_resume_id']
        ]);
    }

    public function test_post_annoucement_resume_failed_should_return_error_message()
    {
        //announcement_id doesn't unique
        $data = $this->fakerAnnouncementResume->toArray();

        $response = $this->postJson('/api/academic-industry/application', $data);
        $expected = json_decode($response->content(), true);

        $assertion = [
            "announcement_id" => [
                "The announcement id has already been taken."
                ]
            ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expected);
    }

    public function test_update_announcement_resume_success_should_return_announcement_resume_that_has_been_updated(Type $var = null)
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
