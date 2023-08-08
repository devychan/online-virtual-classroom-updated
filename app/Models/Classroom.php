<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classrooms';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function users() {
        return $this->belongsTo(User::class);
    }
    public function enrolls() {
        return $this->hasMany(Enrolls::class);
    }
    public function tasks() {
        return $this->hasMany(Task::class);
    }
    public function results() {
        return $this->hasMany(Result::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }

}
