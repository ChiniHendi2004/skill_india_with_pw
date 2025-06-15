<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'email', 'password', 'client_slug', 'website_url', 'employee_id', 'academic_session', 'expiration_date', 'status'];

    public function authKeys()
    {
        return $this->hasMany(TenantAuthKey::class, 'tenant_id');
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
