<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\Address;
use App\Models\Announcement;
use App\Models\History;
use App\Models\JobType;
use App\Models\Role;
use App\Models\User;
use App\Models\DataOwner;
use Carbon\Carbon;

class AnnouncementRepository implements AnnouncementRepositoryInterface
{
    public function getAnnouncementById($id)
    {
        $announcement = Announcement::join('companies', 'companies.company_id', '=', 'announcements.company_id')
                        ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
                        ->join('addresses', 'addresses.address_id', '=', 'announcements.address_id')
                        ->where('announcements.announcement_id', $id)
                        ->get();

        $announcement_mapping = $announcement->map(function ($announcement) {
            $jobType = JobType::where('announcement_id', $announcement['announcement_id']);
            $announcement['job_type'] = $jobType->pluck('job_type');
        });

        return $announcement;
    }

    public function getAllAnnouncements()
    {
        $announcements = Announcement::join('addresses', 'addresses.address_id', '=', 'announcements.address_id')
                        ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
                        ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
                        ->where('addresses.address_type', 'announcement')
                        ->select(
                            'announcements.*',
                            'companies.company_id',
                            'companies.company_type',
                            'companies.company_name_en',
                            'companies.company_name_th',
                            'companies.logo',
                            'job_positions.job_position',
                            'job_positions.job_position_id',
                            'addresses.address_id',
                            'addresses.address_one',
                            'addresses.address_two',
                            'addresses.lane',
                            'addresses.road',
                            'addresses.sub_district',
                            'addresses.district',
                            'addresses.province',
                            'addresses.address_type',
                            'addresses.postal_code'
                        )->get();

        $grouped = $announcements->map(function ($announcement) {
            $jobType = JobType::where('announcement_id', $announcement['announcement_id']);
            $announcement['job_type'] = $jobType->pluck('job_type');
            return $announcement;
        });

        return $announcements;
    }

    public function getAnnouncementByCompanyId($company_id)
    {
        $announcements = Announcement::join('companies', 'companies.company_id', '=', 'announcements.company_id')
                        ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
                        ->where('announcements.company_id', $company_id)
                        ->select(
                            'announcements.announcement_title',
                            'announcements.announcement_id',
                            'announcements.start_date',
                            'announcements.end_date',
                            'companies.company_name_th',
                            'companies.company_name_en',
                            'companies.logo',
                            'job_positions.job_position'
                        )->get();

        $announcement_mapping = $announcements->map(function ($announcement) {
            $jobType = JobType::where('announcement_id', $announcement['announcement_id']);
            $announcement['job_type'] = $jobType->pluck('job_type');
        });

        return $announcements;
    }

    public function getAnnouncementByUserId($data)
    {
        $dataOwner = DataOwner::where('user_id', $data['my_user_id'])->get();
        $announcements = [];
        for ($i=0; $i < count($dataOwner); $i++) {
            $announcements[$i] = Announcement::join('companies', 'companies.company_id', '=', 'announcements.company_id')
                            ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
                            ->where('announcements.company_id', $dataOwner[$i]->company_id)
                            ->select(
                                'announcements.announcement_title',
                                'announcements.announcement_id',
                                'announcements.start_date',
                                'announcements.end_date',
                                'companies.company_name_th',
                                'companies.company_name_en',
                                'companies.logo',
                                'job_positions.job_position'
                            )
                            ->get();

            $announcement_mapping = $announcements[$i]->map(function ($announcement) {
                $jobType = JobType::where('announcement_id', $announcement['announcement_id']);
                $announcement['job_type'] = $jobType->pluck('job_type');
            });
        }


        return collect($announcements)->flatten(1);
    }

    public function createAnnouncement($data)
    {
        $user = User::find($data['my_user_id']);
        $dataOwner = DataOwner::where('user_id', $user->user_id)->first();
        $roleOfUser = Role::where('role_id', $user->role_id)->first();

        if ($roleOfUser->role_name === 'admin') {
            $company_id = $data['company_id'];
        } else if ($roleOfUser->role_name === 'manager' || $roleOfUser->role_name === 'coordinator') {
            $company_id = $dataOwner->company_id;
        }

        $address = new Address();
        $address->address_one = $data['address_one'];
        $address->address_two = $data['address_two'] == "" ? "-": $data['address_two'];
        $address->lane = $data['lane'] == "" ? "-": $data['lane'];
        $address->road = $data['road'] == "" ? "-": $data['road'];
        $address->sub_district = $data['sub_district'];
        $address->district = $data['district'];
        $address->province = $data['province'];
        $address->postal_code = $data['postal_code'];
        $address->address_type = 'announcement';
        $address->company_id =  $company_id;
        $address->save();

        $announcement = new Announcement();
        $announcement->company_id = $company_id;
        $announcement->address_id = $address->address_id;
        $announcement->announcement_title = $data['announcement_title'];
        $announcement->job_description = $data['job_description'];
        $announcement->job_position_id = $data['job_position_id'];
        $announcement->property = $data['property'];
        $announcement->priority = $data['priority'] == "" ? "-": $data['priority'];
        $announcement->picture = $data['picture'] == "" ? "-": $data['picture'];
        $announcement->start_date = $data['start_date'];
        $announcement->end_date = $data['end_date'];
        $announcement->salary = $data['salary'];
        $announcement->welfare = $data['welfare'];
        $announcement->status = $data['status'];
        $announcement->start_business_day = $data['start_business_day'];
        $announcement->end_business_day = $data['end_business_day'];
        $announcement->start_business_time = $data['start_business_time'];
        $announcement->end_business_time = $data['end_business_time'];
        $announcement->save();

        $insertedJobType['job_type'] = [];
        $jsonJobType = json_decode($data['job_type']);
        for ($i=0; $i < count($jsonJobType); $i++) {
            $jobType = new JobType();
            $jobType->announcement_id = $announcement->announcement_id;
            $jobType->job_type = $jsonJobType[$i];
            $insertedJobType['job_type'][$i] = $jobType;
            $jobType->save();
        }

        $history = new History();
        $history->user_id = $data['my_user_id'];
        $history->announcement_id = $announcement->announcement_id;
        $history->status = 'created';
        $history->save();

        return array_merge($announcement->toArray(), $insertedJobType, $address->toArray());
    }

    public function updateAnnouncement($data)
    {
        $announcement = Announcement::find($data['announcement_id']);

        $announcement->announcement_title = $data['announcement_title'];
        $announcement->job_description = $data['job_description'];
        $announcement->job_position_id = $data['job_position_id'];
        $announcement->property = $data['property'];
        $announcement->priority = $data['priority'] == "" ? "-": $data['priority'];
        $announcement->picture = $data['picture'] == "" ? "-": $data['picture'];
        $announcement->start_date = $data['start_date'];
        $announcement->end_date = $data['end_date'];
        $announcement->salary = $data['salary'];
        $announcement->welfare = $data['welfare'];
        $announcement->status = $data['status'];
        $announcement->start_business_day = $data['start_business_day'];
        $announcement->end_business_day = $data['end_business_day'];
        $announcement->start_business_time = $data['start_business_time'];
        $announcement->end_business_time = $data['end_business_time'];
        $announcement->updated_at = Carbon::now();
        $announcement->save();

        $updatedJobType['job_type'] = [];
        $jobType = JobType::where('announcement_id', $data['announcement_id'])->get();
        if ($jobType) {
            for ($i=0; $i < count($jobType); $i++) {
                $deleted_jobType = $jobType[$i]->forceDelete();
            }
        }

        $jsonJobType = json_decode($data['job_type']);
        for ($i=0; $i < count($jsonJobType); $i++) {
            $jobType = new JobType();
            $jobType->announcement_id = $announcement->announcement_id;
            $jobType->job_type = $jsonJobType[$i];
            $updatedJobType['job_type'][$i] = $jobType;
            $jobType->save();
        }

        $address = Address::where('address_type', 'announcement')
                ->where('address_id', $data['address_id'])->first();
        $address->address_one = $data['address_one'];
        $address->address_two = $data['address_two'] == "" ? "-": $data['address_two'];
        $address->lane = $data['lane'] == "" ? "-": $data['lane'];
        $address->road = $data['road'] == "" ? "-": $data['road'];
        $address->sub_district = $data['sub_district'];
        $address->district = $data['district'];
        $address->province = $data['province'];
        $address->postal_code = $data['postal_code'];
        $address->save();

        $history = new History();
        $history->user_id = $data['my_user_id'];
        $history->announcement_id = $announcement->announcement_id;
        $history->status = 'updated';
        $history->save();

        return array_merge($announcement->toArray(), $updatedJobType, $address->toArray());
    }

    public function deleteAnnouncementById($id)
    {
        $announcement = Announcement::find($id)->first();

        $jobType = JobType::where('announcement_id', $id)->get();
        $address = Address::where('address_id', $announcement->address_id)->first();

        if($announcement&& $jobType->isNotEmpty() && $address){
            for ($i=0; $i < count($jobType); $i++) {
                $deleted_jobType = $jobType[$i]->delete();
            }
            $deleted_address = $address->delete();
            $deleted_announcement = $announcement->delete();
            return $deleted_announcement && $deleted_jobType && $deleted_address;
        }

        return "Find not found announcement or job type or address";
    }
}
