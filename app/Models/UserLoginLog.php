<?php

namespace App\Models;

use App\Services\UserAgentPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginLog extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'device_hash'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDeviceDescriptionAttribute(): string
    {
        return app(UserAgentPresenter::class)->describe($this->user_agent);
    }
}
