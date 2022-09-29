<?php
namespace App\Services;

use App\Models\Company;
use App\Models\CompanyUserRel;
use App\Models\User;
use Exception;

class CompanyService
{

    public function getCompanies(User $user)
    {
        return CompanyUserRel::query()
            ->select([
                'company' => 'company.title',
                'phone' => 'company.phone',
                'description' => 'company.description',
            ])
            ->rightJoin('company', 'company_user_rel.company_id', '=', 'company.id')
            ->where(['company_user_rel.user_id' => $user->id])
            ->get();
    }

    public function addCompanies(User $user, array $companiesData)
    {
        foreach ($companiesData as $companyData) {
            app('db')->transaction(function() use ($companyData, $user){
                $company = new Company($companyData);
                if (!$company->save()) {
                    throw new Exception('Somthing went wrong');
                }
                $companyUserRel = new CompanyUserRel([
                    'user_id' => $user->id,
                    'company_id' => $company->id
                ]);
                if (!$companyUserRel->save()) {
                    throw new Exception('Something went wrong');
                }
            });
        }
        return true;

    }
}
