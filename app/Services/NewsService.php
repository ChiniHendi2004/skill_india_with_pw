<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NewsService
{
    public function getNewsByTenant($tenantId)
    {
        return DB::table('scrolling_news')
            ->where('tenant_id', $tenantId)
            ->get();
    }
}

