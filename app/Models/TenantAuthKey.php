<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class TenantAuthKey extends Model implements JWTSubject
{
    protected $fillable = ['auth_key', 'description', 'tenant_id', 'client_slug', 'website_url', 'employee_id', 'academic_session', 'expiration_date', 'status'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // Implementing JWTSubject methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
