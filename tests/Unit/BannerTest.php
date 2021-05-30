<?php

namespace Tests\Unit;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_banners_success_should_return_status_200()
    {
        $this->get('api/banners')->assertStatus(200);
    }

    public function test_post_banner_success_should_return_banner()
    {
        $data = [
            'banner_id' => $this->fakerBanner->banner_id,
            'path_image' => $this->fakerBanner->path_image,
            'file_banner' => ''
        ];
        $response = $this->postJson('api/banner', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $banner = Banner::find($response_arr['banner_id']);
        $banner_arr = $banner->toArray()['banner_id'];

        $this->assertEquals($banner_arr, $response_arr['banner_id']);
    }

    public function test_post_banner_fail_should_return_throwable_error_message()
    {
        //Field file_banner is string.
        $data = [
            'banner_id' => $this->fakerBanner->banner_id,
            'path_image' => $this->fakerBanner->path_image,
            'file_banner' => 'test'
        ];

        $response = $this->postJson('api/banner', $data);
        $response_expected = json_decode($response->getContent(), true);

        $assertion = [
            "file_banner" => [
                "The file banner must be a file of type: jpeg, jpg, png, gif."
            ]
        ];
        $this->assertEquals($assertion, $response_expected);
    }

    public function test_delete_banner_by_id_success_should_return_true()
    {
        $data = [
            'banner_id' => (string) $this->fakerBanner->banner_id,
            'path_image' => $this->fakerBanner->path_image
        ];

        $expected_banner = true;
        $response = $this->deleteJson('api/banner', $data);
        $response_arr = json_decode($response->content(), true);
        $this->assertEquals($response_arr, $expected_banner);
    }

    public function test_delete_banner_by_id_fail_should_return_error_message()
    {
        $id = [
            'banner_id' => "1",
            'path_image' => $this->fakerBanner->path_image
        ];

        $expected_banner = 'Find not found banner';

        $response = $this->deleteJson('api/banner', $id);
        $response_arr = json_decode($response->content(), true);
        $this->assertEquals($response_arr, $expected_banner);
    }
}
