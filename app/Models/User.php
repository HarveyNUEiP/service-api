<?php

namespace App\Models;

use App\Traits\Sortable;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        Sortable,
        SoftDeletes,
        CreatedUpdatedBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrm_id',
        'user_no',
        'chinese_name',
        'english_name',
        'email',
        'shared_secret',
        'last_tfa_verification_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_valid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'shared_secret',
        'last_auth_time',
        'last_tfa_verification_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array<int, string>
     */
    protected $sortable = [
        'id',
        'chinese_name',
        'email',
        'created_at',
        'updated_at',
    ];

    /**
     * Override the method to use the "hrm_id" column as the Identifier.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'hrm_id';
    }
}
