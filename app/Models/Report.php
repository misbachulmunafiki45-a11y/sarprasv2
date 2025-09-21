<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'resident_id',
        'report_category_id',
        'title',
        'description',
        'image',
        'address',
        'user_last_seen_status_id',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function reportCategory()
    {
        return $this->belongsTo(ReportCategory::class);
    }

    public function reportStatuses()
    {
        return $this->hasMany(ReportStatus::class);
    }
}
