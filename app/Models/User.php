<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * @method static create(array $array)
 * @method static find(mixed $user_id)
 * @method static where(string $string, mixed $email)
 * @method static findOrFail(mixed $id)
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role'];

    const ROLE_PATIENT = 'patient';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_HOSPITAL = 'hospital';
    const ROLE_ADMIN = 'admin';

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'doctor_specializations', 'doctor_id', 'specialization_id');
    }
}