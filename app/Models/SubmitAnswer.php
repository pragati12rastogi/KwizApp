<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitAnswer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'submit_answer';
}
