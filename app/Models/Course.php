<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'status'];

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }

        if ($this->relationLoaded('lessons')) {
            return $this->lessons->first(fn (CourseLesson $lesson) => filled($lesson->vimeo_thumbnail_url))?->vimeo_thumbnail_url
                ?? $this->vimeo_thumbnail_url;
        }

        return $this->lessons()->whereNotNull('vimeo_thumbnail_url')->value('vimeo_thumbnail_url') ?? $this->vimeo_thumbnail_url;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order');
    }
}
