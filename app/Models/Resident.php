<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user',
        'avatar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        //satu laporan bisa memiliki banyak laporan
        return $this->hasMany(Report::class);
    }
}
