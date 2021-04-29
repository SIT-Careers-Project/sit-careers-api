<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class NotificationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_notificaiton_by_user_id_should_return_status_200()
    {
        $data['my_user_id'] = $this->fakerUser->user_id;
        $this->json('GET', 'api/notifications', $data)->assertStatus(200);
    }

    public function test_update_notification_by_user_id_should_return_that_updated_data()
    {
        $data = $this->fakerNotification->toArray();
        $data['read_at'] = '2021-04-29 12:00:00';
        $data['my_user_id'] = $data['user_id'];

        $response = $this->putJson('api/notification', $data);
        $response->assertStatus(200);

        $expected = json_decode($response->content(), true);
        $this->assertDatabaseHas('notifications', [
            'notification_id' => $expected['notification_id'],
            'read_at' => $expected['read_at'],
            'user_id' => $expected['user_id']
        ]);
    }

    public function test_update_notification_by_user_id_fail_should_return_error_message()
    {
        $data = $this->fakerNotification->toArray();
        $data['notification_id'] = null;
        $data['read_at'] = '2021-04-29 12:00:00';
        $data['my_user_id'] = $data['user_id'];

        $response = $this->putJson('api/notification', $data);
        $response_expected = json_decode($response->content(), true);

        $assertion = [
            "notification_id" => [
                "The notification id field is required."
                ]
            ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $response_expected);
    }
}
