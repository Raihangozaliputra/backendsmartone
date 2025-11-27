<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class AttendanceRecognitionLog extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'attendance_id',
        'confidence_score',
        'status',
        'raw_response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'confidence_score' => 'float',
        'raw_response' => 'array',
    ];

    /**
     * Get the user that owns the recognition log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance record associated with this log.
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}