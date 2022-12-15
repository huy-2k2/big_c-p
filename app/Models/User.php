<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'address_id',
        'account_accepted_at',
        'access_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notification')->withPivot('readed_at');
    }

    public function not_readed_notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notification')->wherePivotNull('readed_at')->orderby('created_at', 'desc');
    }

    public function readed_notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notification')->withPivot('readed_at')->wherePivotNotNull('readed_at')->orderByPivot('readed_at', 'desc');
    }

    public static function get_users_with_role($role)
    {
        return Self::where('account_accepted_at', '!=', null)->whereHas('role', function (Builder $query) use ($role) {
            $query->where('name', $role);
        })->get();
    }
}
