<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * @method static create(array $array)
 * @method static find(mixed $user_id)
 * @method static where(string $string, mixed $email)
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role'];

    const ROLE_PATIENT = 'patient';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_HOSPITAL = 'hospital';
}