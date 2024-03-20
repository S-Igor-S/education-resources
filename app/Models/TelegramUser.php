<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'first_name', 'last_name', 'username', 'chat_ids'];

    /**
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
