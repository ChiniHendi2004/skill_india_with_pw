<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VacancyService
{
    // Fetch vacancies based on tenant_id
    public function getVacanciesByTenant($tenantId)
    {
        return DB::table('vacancies')
            ->where('tenant_id', $tenantId)
            ->where('status', 1)
            ->whereDate('expiration_date', '>=', now())
            ->get();
    }

    // Store job application
    public function submitApplication($data)
    {
        return DB::table('vacancies_applications')->insert($data);
    }
}
