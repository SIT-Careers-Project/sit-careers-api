<?php

namespace App\Traits;

use App\Models\Announcement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnnouncementsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = $this->request;

        $start_date = $data['start_date']." 00:00:00";
        $end_date = $data['end_date']." 23:59:59";

        $announcements = Announcement::join('addresses', 'addresses.address_id', '=', 'announcements.address_id')
            ->join('companies', 'companies.company_id', '=', 'announcements.company_id')
            ->join('job_types', 'job_types.announcement_id', '=', 'announcements.announcement_id')
            ->join('job_positions', 'job_positions.job_position_id', '=', 'announcements.job_position_id')
            ->where('addresses.address_type', 'announcement')
            ->select(
                'announcements.*',
                'companies.company_id',
                'companies.company_type',
                'companies.company_name_en',
                'companies.company_name_th',
                'companies.logo',
                'job_types.job_type',
                'job_types.job_id',
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
            )
            ->whereBetween('announcements.created_at', [$start_date, $end_date])
            ->get()
            ->makeHidden([
                'announcement_id',
                'company_id',
                'job_position_id',
                'picture',
                'logo',
                'job_id',
                'address_id',
                'address_type',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);

        return $announcements;
    }

    public function headings() : array
    {
        return array_keys($this->collection()->first()->toArray());
    }
}
