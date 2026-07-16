<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $latest = static::withTrashed()->latest('id')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $task->task_number = '#TGS-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
        'task_number',
        'title',
        'description',
        'user_id',
        'created_by',
        'status',
        'priority',
        'start_date',
        'deadline',
        'progress_percentage',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => Priority::class,
        'start_date' => 'date',
        'deadline' => 'date',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(TaskTimeline::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
