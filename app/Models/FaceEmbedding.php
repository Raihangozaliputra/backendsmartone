<?php

namespace App\Models;

use App\Models\BaseModel;

class FaceEmbedding extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'vector_data',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vector_data' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the face embedding.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}