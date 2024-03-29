<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Faker\Provider\Uuid;

use App\Http\Controllers\CompanyController;
use App\Repositories\CompanyRepository;
use App\Repositories\CompanyRepositoryInterface;

use App\Models\Company;
use App\Models\Address;
use App\Models\MOU;
use App\Models\Role;
use App\Models\User;
use App\Models\DataOwner;

class CompanyTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_get_all_companies_success_should_return_status_200()
    {
        $this->get('api/companies')->assertStatus(200);
    }

    public function test_post_company_success_should_return_company()
    {
        $roleStd = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'company_name_th' => 'บริษัท เทส จำกัด',
            'company_name_en' => 'Test COmpany',
            'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน',
            'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => 'Technology',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '07:00',
            'end_business_time' => '18:00',
            'e_mail_coordinator' => 'test1@gmail.com',
            'e_mail_manager' => 'company1@gmail.com',
            'tel_no' => '0988882356',
            'phone_no' => '0298987640',
            'website' => 'http://test.com',
            "address_one" => "138/2 หอพักสตรีพสิษฐ์",
            "address_two" => "-",
            "lane" => "2",
            "road" => "วิภาวดีรังสิต",
            "sub_district" => "ดินแดง",
            "district" => "ดินแดง",
            "province" => "กรุงเทพ",
            "postal_code" => "10400",
            "mou_type" => "ชนิด MOU",
            "mou_link" => "https://www.google.co.th",
            "start_date_mou" => "30 กันยายน 2563",
            "end_date_mou" => "30 กันยายน 2565"
        ];

        $response = $this->postJson('api/company', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $company = Company::find($response_arr['company_id']);
        $company_arr = $company->toArray()['company_id'];

        $this->assertEquals($company_arr, $response_arr['company_id']);
    }

    public function test_post_company_for_admin_success_should_return_company()
    {
        $roleStd = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'company_name_th' => 'บริษัท เทส จำกัด',
            'company_name_en' => 'Test COmpany',
            'description' => '',
            'about_us' => '',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => '',
            'start_business_day' => '',
            'end_business_day' => '',
            'start_business_time' => '',
            'end_business_time' => '',
            'e_mail_coordinator' => '',
            'e_mail_manager' => '',
            'tel_no' => '',
            'phone_no' => '',
            'website' => '',
            "address_one" => "",
            "address_two" => "-",
            "lane" => "",
            "road" => "",
            "sub_district" => "",
            "district" => "",
            "province" => "",
            "postal_code" => "",
            "mou_type" => "",
            "mou_link" => "",
            "start_date_mou" => "",
            "end_date_mou" => ""
        ];

        $response = $this->postJson('api/admin/company', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $company = Company::find($response_arr['company_id']);
        $company_arr = $company->toArray()['company_id'];

        $this->assertEquals($company_arr, $response_arr['company_id']);
    }

    public function test_post_company_for_admin_failed_should_return_company()
    {
        $roleStd = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'description' => '',
            'about_us' => '',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => '',
            'start_business_day' => '',
            'end_business_day' => '',
            'start_business_time' => '',
            'end_business_time' => '',
            'e_mail_coordinator' => '',
            'e_mail_manager' => '',
            'tel_no' => '',
            'phone_no' => '',
            'website' => '',
            "address_one" => "",
            "address_two" => "-",
            "lane" => "",
            "road" => "",
            "sub_district" => "",
            "district" => "",
            "province" => "",
            "postal_code" => "",
            "mou_type" => "",
            "mou_link" => "",
            "start_date_mou" => "",
            "end_date_mou" => ""
        ];

        $response = $this->postJson('api/admin/company', $data);
        $expect = json_decode($response->content(), true);

        $assertion = [
            "company_name_th" => [
                "The company name th field is required."
            ],
            "company_name_en" => [
                "The company name en field is required."
            ]
            ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expect);
    }

    public function test_get_all_companies_by_company_success_should_return_has_same_data_on_db()
    {

        $address = factory(Company::class)->create([
            "company_id" => Uuid::uuid()
        ]);

        $address = factory(DataOwner::class)->create([
            "company_id" => $this->faker->company_id,
            "user_id" => $this->fakerUser->user_id,
        ]);

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        $response = $this->json('GET', 'api/company/companies', ['my_user_id' => $this->fakerUser->user_id ]);
        $response->assertStatus(200);

        $responseGetByID = json_decode($response->content(), true);

        $this->assertDatabaseHas('data_owner', [
            'data_owner_id' => $responseGetByID[0]['data_owner_id'],
            'user_id' => $responseGetByID[0]['user_id'],
            'company_id' => $responseGetByID[0]['company_id']
        ]);
    }

    public function test_update_company_by_admin_success_should_return_company_that_had_been_updated()
    {
        $roleStd = Role::where('role_name', 'admin')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'company_name_th' => 'บริษัท ฮัลโหล จำกัด',
            'company_name_en' => 'TEST COMPANY',
            'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน',
            'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => 'Technology',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '07:00',
            'end_business_time' => '18:00',
            'e_mail_coordinator' => 'test@gmail.com',
            'e_mail_manager' => 'company@gmail.com',
            'tel_no' => '0988882356',
            'phone_no' => '0298987645',
            'website' => 'http://test.com',
            "address_one" => "138/2 พรีวิวหอพัก",
            "address_two" => "-",
            "lane" => "9",
            "road" => "วิภาวดีรังสิต",
            "sub_district" => "ดินแดง",
            "district" => "ดินแดง",
            "province" => "กรุงเทพ",
            "postal_code" => "10400",
            "mou_type" => "ชนิด MOU",
            "mou_link" => "https://www.google.co.th",
            "start_date_mou" => "30 กันยายน 2563",
            "end_date_mou" => "30 กันยายน 2565"
        ];

        $response = $this->putJson('api/company', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $company = Company::find($response_arr['company_id']);
        $company_arr = $company->toArray();

        $this->assertDatabaseHas('companies', [
            'company_id' => $response_arr['company_id'],
            'company_name_th' => $response_arr['company_name_th'],
        ]);
        $this->assertDatabaseHas('mou', [
            'start_date_mou' => $response_arr['start_date_mou'],
            'end_date_mou' => $response_arr['end_date_mou'],
        ]);
    }

    public function test_update_company_by_company_success_should_return_company_that_had_been_updated()
    {
        $roleStd = Role::where('role_name', 'manager')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'sit-coll@gmail.com'
        ]);

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'company_name_th' => 'บริษัท ฮัลโหล จำกัด',
            'company_name_en' => 'TEST COMPANY',
            'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน',
            'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => 'Technology',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '07:00',
            'end_business_time' => '18:00',
            'e_mail_coordinator' => 'test@gmail.com',
            'e_mail_manager' => 'company@gmail.com',
            'tel_no' => '0988882356',
            'phone_no' => '0298987645',
            'website' => 'http://test.com',
            "address_one" => "138/2 พรีวิวหอพัก",
            "address_two" => "-",
            "lane" => "9",
            "road" => "วิภาวดีรังสิต",
            "sub_district" => "ดินแดง",
            "district" => "ดินแดง",
            "province" => "กรุงเทพ",
            "postal_code" => "10400"
        ];

        $response = $this->putJson('api/company', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $company = Company::find($response_arr['company_id']);
        $company_arr = $company->toArray();

        $this->assertDatabaseHas('companies', [
            'company_id' => $response_arr['company_id'],
            'company_name_th' => $response_arr['company_name_th'],
        ]);
    }

    public function test_update_company_by_coordinator_success_should_return_status_200()
    {
        $roleStd = Role::where('role_name', 'coordinator')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'coordinator@sitcc.com'
        ]);

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        # field e_mail_manager should not send it.
        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'company_name_th' => 'บริษัท ฮัลโหล จำกัด',
            'company_name_en' => 'TEST COMPANY',
            'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน coordinator update',
            'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => 'Technology',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '07:00',
            'end_business_time' => '18:00',
            'e_mail_coordinator' => 'test@gmail.com',
            'tel_no' => '0988882356',
            'phone_no' => '0298987645',
            'website' => 'http://test.com',
            "address_one" => "138/2 พรีวิวหอพัก",
            "address_two" => "-",
            "lane" => "9",
            "road" => "วิภาวดีรังสิต",
            "sub_district" => "ดินแดง",
            "district" => "ดินแดง",
            "province" => "กรุงเทพ",
            "postal_code" => "10400",
            "mou_type" => "ชนิด MOU",
            "mou_link" => "https://www.google.co.th",
            "start_date_mou" => "30 กันยายน 2563",
            "end_date_mou" => "30 กันยายน 2565"
        ];

        $response = $this->putJson('api/company', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $company = Company::find($response_arr['company_id']);
        $company_arr = $company->toArray();

        $this->assertDatabaseHas('companies', [
            'company_id' => $response_arr['company_id'],
            'company_name_th' => $response_arr['company_name_th'],
        ]);
    }

    public function test_update_company_by_coordinator_success_field_is_null_should_return_status_200()
    {
        $roleStd = Role::where('role_name', 'coordinator')->first();
        $user = factory(User::class)->create([
            'role_id' => $roleStd->role_id,
            'email' => 'coordinator@sitcc.com'
        ]);

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        # field e_mail_manager is null.
        $data = [
            'company_id' => $this->faker->company_id,
            'my_user_id' => $user->user_id,
            'my_role_id' => $user->role_id,
            'company_name_th' => 'บริษัท ฮัลโหล จำกัด',
            'company_name_en' => 'TEST COMPANY',
            'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน coordinator update',
            'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => 'Technology',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '07:00',
            'end_business_time' => '18:00',
            'e_mail_coordinator' => 'test@gmail.com',
            'e_mail_manager' => '',
            'tel_no' => '0988882356',
            'phone_no' => '0298987645',
            'website' => 'http://test.com',
            "address_one" => "138/2 พรีวิวหอพัก",
            "address_two" => "-",
            "lane" => "9",
            "road" => "วิภาวดีรังสิต",
            "sub_district" => "ดินแดง",
            "district" => "ดินแดง",
            "province" => "กรุงเทพ",
            "postal_code" => "10400",
            "mou_type" => "ชนิด MOU",
            "mou_link" => "https://www.google.co.th",
            "start_date_mou" => "30 กันยายน 2563",
            "end_date_mou" => "30 กันยายน 2565"
        ];

        $response = $this->putJson('api/company', $data);
        $response->assertStatus(200);

        $response_arr = json_decode($response->content(), true);
        $company = Company::find($response_arr['company_id']);
        $company_arr = $company->toArray();

        $this->assertDatabaseHas('companies', [
            'company_id' => $response_arr['company_id'],
            'company_name_th' => $response_arr['company_name_th'],
        ]);
    }

    public function test_post_company_not_have_some_field_should_return_status_400()
    {
        $data = [
            'company_name_th' => '',
            'company_name_en' => '',
            'description' => 'เป็นบริษัทพัฒนา software บริษัทใหญ่ อยู่เยอรมัน',
            'about_us' => 'อยากเท่ อยากเจ๋ง มาเข้าบริษัทนี้',
            'company_image_logo' => '',
            'logo' => '',
            'company_type' => 'Technology',
            'start_business_day' => 'จันทร์',
            'end_business_day' => 'ศุกร์',
            'start_business_time' => '07:00',
            'end_business_time' => '18:00',
            'e_mail_coordinator' => 'test@gmail.com',
            'e_mail_manager' => 'company@gmail.com',
            'tel_no' => '0298987644',
            'phone_no' => '0298987645',
            'website' => 'http://test.com',
            "address_one" => "138/2 พรีวิวหอพัก",
            "address_two" => "-",
            "lane" => "9",
            "road" => "วิภาวดีรังสิต",
            "sub_district" => "ดินแดง",
            "district" => "ดินแดง",
            "province" => "กรุงเทพ",
            "postal_code" => "10400",
            "mou_type" => "ชนิด MOU",
            "mou_link" => "https://www.google.co.th",
            "start_date_mou" => "30 กันยายน 2563",
            "end_date_mou" => "30 กันยายน 2565"
        ];

        $response = $this->postJson('api/company', $data);
        $expect = json_decode($response->content(), true);

        $assertion = [
            "company_name_th" => [
                "The company name th field is required."
            ],
            "company_name_en" => [
                "The company name en field is required."
            ],
            "e_mail_coordinator" => [
                "The e mail coordinator has already been taken."
            ],
            "e_mail_manager" => [
                "The e mail manager has already been taken."
            ]
        ];

        $response->assertStatus(400);
        $this->assertEquals($assertion, $expect);
    }

    public function test_post_company_not_have_field_should_return_message()
    {
        $data = [];

        $response = $this->postJson('api/company', $data);
        $response->assertStatus(400);
    }

    public function test_update_company_fail_should_return_status_500()
    {
        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        $data = [
            'company_id' => $this->faker->company_id
        ];

        $response = $this->putJson('api/company', $data);
        $response->assertStatus(500);
    }

    public function test_delete_company_by_id_success_should_return_true()
    {
        $data = $this->faker;

        $address = factory(Address::class)->create([
            "company_id" => $this->faker->company_id,
            "address_id" => Uuid::uuid()
        ]);

        $mou = factory(MOU::class)->create([
            "company_id" => $this->faker->company_id,
            "mou_id" => Uuid::uuid()
        ]);

        $mou = factory(DataOwner::class)->create([
            "company_id" => $this->faker->company_id,
            "user_id" => $this->fakerUser->user_id
        ]);

        $expected_company = true;
        $response = $this->deleteJson('api/company/'.$data['company_id']);
        $response_arr = json_decode($response->content(), true);
        $this->assertEquals($response_arr, $expected_company);
    }

    public function test_delete_company_by_id_fail_should_return_fail_message()
    {
        $expected_company = 'Find not found company or mou or address.';

        $response = $this->deleteJson('api/company/'.$this->faker->company_id);
        $response_arr = json_decode($response->content(), true);
        $this->assertEquals($response_arr, $expected_company);
    }
}
