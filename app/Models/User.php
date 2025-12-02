<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\V1\Authorization;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Authorization, CanResetPassword, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions');
    }

    public function assignRoles($roles): void
    {
        if (empty($roles)) {
            return;
        }

        $roles = is_iterable($roles) ? $roles : [$roles];
        $this->roles()->sync($roles);
    }

    public function hasRole($roleName): bool
    {
        $roleNames = is_array($roleName) ? $roleName : [$roleName];

        $roleCount = $this->roles()->whereIn('name', $roleNames)->count();

        return $roleCount === count($roleNames);
    }

    public function can($abilities, $arguments = []): bool
    {
        if (is_iterable($abilities)) {
            foreach ($abilities as $ability) {
                if ($this->checkUserAbility($ability)) {
                    return true;
                }
            }

            return false;
        }

        return $this->checkUserAbility($abilities);
    }

    private function checkUserAbility($ability)
    {
        if ($this->checkAbility($ability)) {
            return true;
        }

        return $this->roles->contains(function ($role) use ($ability) {
            return $role->hasPermission($ability);
        });
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(\App\Models\Task::class, 'assigned_user_id');
    }
}
