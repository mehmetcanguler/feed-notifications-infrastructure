<?php

namespace App\Models;

use App\Enums\PlatformType;
use App\Enums\UserAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'user_action',
        'platform_type',
        'target_type',
        'target_id',
        'metadata',
    ];
    protected $casts = [
        'user_action' => UserAction::class,
        'platform_type' => PlatformType::class,
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
