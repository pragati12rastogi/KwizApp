<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionRights extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'section_rights';
}
