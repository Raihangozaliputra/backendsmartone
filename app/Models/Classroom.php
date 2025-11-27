<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Classroom extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'location',
    ];

    /**
     * Get the schedules for this classroom.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the students in this classroom.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_user', 'classroom_id', 'user_id')
                    ->whereHas('roles', function($query) {
                        $query->where('name', 'student');
                    });
    }

    /**
     * Get the teachers assigned to this classroom.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'classroom_user', 'classroom_id', 'user_id')
                    ->whereHas('roles', function($query) {
                        $query->whereIn('name', ['teacher', 'staff']);
                    });
    }
}