<?php

namespace App\Models;

use App\Services\VimeoUrlParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'description', 'vimeo_url', 'sort_order'];

    protected function casts(): array
    {
        return ['course_id' => 'integer', 'sort_order' => 'integer'];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getVimeoIdAttribute(): ?string
    {
        return app(VimeoUrlParser::class)->extractId($this->vimeo_url);
    }

    public function getVimeoEmbedUrlAttribute(): ?string
    {
        return app(VimeoUrlParser::class)->embedUrl($this->vimeo_url);
    }
}
