<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Musonza\Chat\Traits\Messageable;
use Carbon\Carbon;
use App\Models\Supplier;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Messageable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_seen',
        'phone',
        'roles',
        'permissions',
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
        'password' => 'hashed',
        'superadmin' => 'boolean',
        'permissions' => 'array',
        'roles' => 'array',
    ];

    public function setLastSeenAttribute($value)
    {
        $this->attributes['last_seen'] = Carbon::now();
    }

    public function getLastSeenAttribute($value)
    {
        $lastSeen = Carbon::parse($value)->format('Y-m-d H:i:s');
        if (Carbon::now()->diffInMinutes($lastSeen) < 5) {
            return 'Online';
        } else if (Carbon::now()->diffInMinutes($lastSeen) < 60) {
            return 'Был в сети ' . Carbon::now()->diffInMinutes($lastSeen) . ' минут назад';
        } else if (Carbon::now()->diffInHours($lastSeen) < 24) {
            return 'Был в сети ' . Carbon::now()->diffInHours($lastSeen) . ' часов назад';
        } else {
            return 'Был в сети ' . Carbon::now()->diffInDays($lastSeen) . ' дней назад';
        }
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function allFoundationOrders()
    {
        return $this->projects()->where('order_type', 'foundation');
    }

    public function allSmetaOrders()
    {
        return $this->projects()->where('order_type', 'smeta');
    }

    public function paidFoundationOrders()
    {
        return $this->projects()->where('order_type', 'foundation')->where('payment_status', 'success');
    }

    public function paidSmetaOrders()
    {
        return $this->projects()->where('order_type', 'smeta')->where('payment_status', 'success');
    }
    

    // Example method to check if user has access to a Nova resource
    public function hasAccessToNovaResource($resource)
    {

        return true;
        if ($this->superadmin) {
            return true;
        }

        // 'Orders' is accessible by default if no specific permissions are set
        $defaultResources = ['Orders'];

        // Get the list of resources the user has access to
        $accessibleResources = $this->permissions ?? $defaultResources;

        return in_array($resource, $accessibleResources);
    }
    
    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = json_encode(array_keys(array_filter($value)));
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    public function hasAnyRole($roles)
    {
        return !empty(array_intersect($roles, $this->getRoles()));
    }

    public function getRoles()
    {
        return $this->roles ? explode(',', $this->roles) : [];
    }

    public function addRole($role)
    {
        $roles = $this->getRoles();
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->roles = implode(',', $roles);
            $this->save();
        }
    }

    public function removeRole($role)
    {
        $roles = $this->getRoles();
        if (($key = array_search($role, $roles)) !== false) {
            unset($roles[$key]);
            $this->roles = implode(',', $roles);
            $this->save();
        }
    }

    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

}


