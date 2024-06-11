<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static where(string $string, mixed $id)
 * @method static create(string[] $array)
 */
class Specialization extends Model
{
    protected $table = 'specializations';
    protected $fillable = ['name'];

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'doctor_specializations', 'specialization_id', 'doctor_id');
    }
}