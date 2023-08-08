<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $table = 'results';
    protected $primaryKey = 'id';
    protected $guarded =[];

    public function classrooms( ) {
        return $this->belongsTo(Classroom::class);
    }
    public function tasks() {
        return $this->belongsTo(Task::class);
    }
}
