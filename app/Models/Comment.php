<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'feedback_id', 'content'];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDisplayed($query)
    {
        return $query->where('is_display', true);
    }

    public function scopeNotDisplayed($query)
    {
        return $query->where('is_display', false);
    }
}
