<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_at',
        'priority',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function getStatusAttribute()
    {
        if ($this->is_completed) {
            return 'Completed';
        }

        if ($this->due_at && $this->due_at->isPast()) {
            return 'Late';
        }

        return 'Pending';
    }
}
