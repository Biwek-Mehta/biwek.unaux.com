<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'auth_user_id',
        'student_name',
         'status',
          'date',
    ];
}
