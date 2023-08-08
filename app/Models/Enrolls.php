<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolls extends Model
{
    use HasFactory;
    protected $table = 'enrolls';
    protected $primaryKey = 'id';
    protected $guarded =[];

    public function classrooms() {
        return $this->belongsTo(Classroom::class);
    }
}
