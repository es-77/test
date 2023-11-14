<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = "feedbacks";

    protected $fillable = ['title', 'description', 'category'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeDisplayed($query)
    {
        return $query->where('is_display', true);
    }

    public function scopeNotDisplayed($query)
    {
        return $query->where('is_display', false);
    }

    public function usersThroughComments()
    {
        return $this->hasManyThrough(
            User::class,
            Comment::class,
            'feedback_id', // Foreign key on comments table
            'id',          // Local key on feedbacks table
            'id',          // Local key on users table
            'user_id'      // Foreign key on comments table
        );
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
