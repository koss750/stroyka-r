<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects'; 

    protected $fillable = [
        'user_id',
        'ip_address',
        'payment_reference',
        'payment_amount',
        'design_id',
        'selected_configuration',
        'filepath',
        'order_type',
        'human_ref',
        'payment_provider',
        'configuration_descriptions',
        'title',
        'status',
        'foundation_id',
        'foundation_params',
        'payment_provider',
        'payment_link',
        'payment_status',
        'price_type',
        'is_example',
    ];

    protected $casts = [
        'selected_configuration' => 'array',
        'configuration_descriptions' => 'array',
        'payment_amount' => 'decimal:2',
        'foundation_params' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function executors()
    {
        return $this->belongsToMany(User::class, 'project_executor', 'project_id', 'executor_id')
            ->withPivot('status', 'price', 'comment')
            ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (!$project->payment_reference) {
                $project->payment_reference = Carbon::now()->format('YmdHis');
            }
        });
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->addHours(3)->format('Y-m-d H:i');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }
}