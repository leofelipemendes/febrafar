<?php

namespace App\Models;

use App\Enum\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;

class Task extends Model
{
    use HasFactory;

    private int $user_id;
    private string $task_name;
    private string $task_description;
    private Date $start_date;
    private Date $deadline_date;
    private Date $completition_date;
    private int $status;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'task_name',
        'task_description',
        'start_date',
        'deadline_date',
        'completition_date',
        'status'
    ];

    protected $casts = [
        'status' => TaskStatus::class
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
