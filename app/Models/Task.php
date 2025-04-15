<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'importance',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'importance' => 'integer',
    ];

    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline instanceof Carbon && $this->deadline->isPast();
    }

    public function getPriorityScoreAttribute(): float
    {
        if ($this->status === 'COMPLETED') {
            return 0.0;
        }

        $now = now();
        $deadline = $this->deadline;

        $daysUntilDeadline = $now->diffInDays($deadline, false); 

        if ($daysUntilDeadline <= 0) {
            return $this->importance * 1000; 
        }

        return $this->importance * (1 / $daysUntilDeadline);
    }
}
