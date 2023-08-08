<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $guarded =[];
    
    public function classrooms() {
        return $this->belongsTo(Classroom::class);
    }
    public function results() {
        return $this->hasMany(Result::class);
    }
}
