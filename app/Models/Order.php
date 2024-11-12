<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Floor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Order extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function user()
    {
	        return $this->belongsTo(User::class);
    }
    protected $table = 'designs';

	protected $casts = [
        'pvPart[]' => 'JSON', // or 'object' if you prefer
        'mvParts[]' => 'JSON' // or 'object' if you prefer
    ];
    public function registerMediaConversions(Media $media = null): void
    {
    }

public function registerMediaCollections(): void
{
    $this->addMediaCollection('main')->singleFile();
}
}
