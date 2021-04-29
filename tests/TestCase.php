<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\Models\Announcement;
use App\Models\Address;
use App\Models\AnnouncementResume;
use App\Models\Banner;
use App\Models\Company;
use App\Models\JobPosition;
use App\Models\Resume;
use App\Models\User;
use App\Models\DataOwner;
use App\Models\Notification;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $faker;
    protected $fakerJobPosition;
    protected $fakerAnnouncement;

    public function setUp() :void {
        parent::setUp();
        $this->artisan('db:seed');
        $this->faker = factory(Company::class)->create();
        $this->fakerJobPosition = factory(JobPosition::class)->create();
        $this->fakerAddress = factory(Address::class)->create();
        $this->fakerAnnouncement = factory(Announcement::class)->create();
        $this->fakerUser = factory(User::class)->create();
        $this->fakerDataOwner = factory(DataOwner::class)->create();
        $this->fakerResume = factory(Resume::class)->create();
        $this->fakerBanner = factory(Banner::class)->create();
        $this->fakerAnnouncementResume = factory(AnnouncementResume::class)->create();
        $this->fakerNotification = factory(Notification::class)->create();
    }
}
