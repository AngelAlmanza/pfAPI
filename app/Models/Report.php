<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'is_resolved',
        'resolution',
        'resolved_at',
        'reported_at',
    ];
}
