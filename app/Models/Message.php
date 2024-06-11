<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $id)
 * @method static whereHas(string $string, \Closure $param)
 * @method static find(mixed $message_id)
 */
class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'consultation_id', 'sender_id', 'message', 'is_read', 'media',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
