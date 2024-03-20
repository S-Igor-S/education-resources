<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['telegram_user_id', 'url', 'chat_id', 'status', 'title'];

    /**
     * @return BelongsTo
     */
    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class);
    }

    /**
     * @param  Builder  $query
     * @param $url
     * @param $chatId
     * @return bool
     */
    public function scopeResourceExist(Builder $query, $url, $chatId): bool
    {
        return $query->where('url', $url)->where('chat_id', $chatId)->exists();
    }
}
