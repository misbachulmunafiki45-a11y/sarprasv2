<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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

    // Generate kode otomatis saat creating
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                // Format: SRPddmmyySMKH2 dan tambahkan 2, 3, dst jika bentrok
                $date = now();
                $base = sprintf('SRP%02d%02d%02dSMKH2', $date->day, $date->month, $date->format('y'));

                $code = $base;
                $suffix = 1;
                while (self::where('code', $code)->exists()) {
                    $suffix++;
                    $code = $base . $suffix;
                }
                $model->code = $code;
            }
        });
    }

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
