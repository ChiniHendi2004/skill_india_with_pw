<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TestimonialService
{
    // Fetch all testimonials for a specific tenant
    public function getTestimonials($tenant_id)
    {
        return DB::table('manage_testimonial')
            ->where('tenant_id', $tenant_id)
            ->get();
    }


    
}
