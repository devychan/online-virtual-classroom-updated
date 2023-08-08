<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded =[];
    protected $hidden = [
        'password',
        'updated_at',
        'created_at',
        'remember_token'
    ];

    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }

    public function enrolls(){
        return $this->hasManyThrough(
            Enrolls::class,
            Classroom::class,
            'user_id',
            'classroom_id',
        );
    }
    public function tasks() {
        return $this->hasManyThrough(
            Classroom::class,
            Enrolls::class,
            Tasks::class,
            'user_id',
            'classroom_id',
            'classroom_id'
        );
    }
}
