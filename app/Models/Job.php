<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = "job_posts";

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'salary_range',
        'required_skills',
    ];

    /**
     * Get the employer who posted the job.
     */
    public function employer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the applications for the job.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
