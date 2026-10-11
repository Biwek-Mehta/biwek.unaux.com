<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'email',
        'otp',
        'created_at'
    ];
}
