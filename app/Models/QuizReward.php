<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizReward extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'quiz_reward';
}
