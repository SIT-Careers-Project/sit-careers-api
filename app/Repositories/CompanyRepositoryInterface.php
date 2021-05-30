<?php

namespace App\Repositories;

interface CompanyRepositoryInterface
{
    public function getAllCompanies();
    public function getCompanyById($id);
    public function getCompaniesByUserId($user_id);
    public function createCompany($data);
    public function updateCompanyById($data);
    public function requestDelete($data);
    public function deleteCompanyById($id);
}