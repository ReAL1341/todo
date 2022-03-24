<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    public $table = 'todo';

    public $fillable = [
        'todo_content',
        'deadline_month',
        'deadline_date',
        'deadline_time',
        'channel',
    ];
}
