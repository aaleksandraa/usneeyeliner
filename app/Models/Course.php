<?php

namespace App\Models;

use App\Services\VimeoUrlParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'image', 'vimeo_url', 'vimeo_thumbnail_url', 'status'];

    public function getVimeoIdAttribute(): ?string
    {
        return app(VimeoUrlParser::class)->extractId($this->vimeo_url);
    }

    public function getVimeoEmbedUrlAttribute(): ?string
    {
        return app(VimeoUrlParser::class)->embedUrl($this->vimeo_url);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }

        return $this->vimeo_thumbnail_url;
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
