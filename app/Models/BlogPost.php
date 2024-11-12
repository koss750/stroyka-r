<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogPost extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'short_description',
        'author',
        'tags',
        'are_tags_generated',
        'slug',
        'view_count',
        'is_published',
        'is_featured',
        'is_archived',
    ];

    protected $casts = [
        'tags' => 'array',
        'are_tags_generated' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);

        $this->addMediaConversion('full')
            ->width(1200)
            ->height(630)
            ->sharpen(10);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/placeholder.jpg');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blogPost) {
            $blogPost->slug = "blog-nomer-" . (random_int(1000, 9999) . '-' . random_int(1000, 9999));
        });
    }
}