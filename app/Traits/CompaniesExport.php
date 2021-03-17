<?php

namespace App\Traits;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompaniesExport implements FromCollection, WithHeadings
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

        $companies = Company::join('mou', 'mou.company_id', '=', 'companies.company_id')
            ->join('addresses', 'addresses.company_id', '=', 'companies.company_id')
            ->where('addresses.address_type', '=', 'company')
            ->whereBetween('companies.created_at', [$start_date, $end_date])
            ->get()
            ->makeHidden(['company_id', 'address_id', 'mou_id', 'address_type', 'created_at', 'updated_at', 'deleted_at']);

        return $companies;
    }

    public function headings() : array
    {
        return array_keys($this->collection()->first()->toArray());
    }
}
