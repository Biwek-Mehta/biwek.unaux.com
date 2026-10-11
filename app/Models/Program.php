<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['name', 'status'];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
