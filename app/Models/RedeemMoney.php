<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedeemMoney extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'redeem_money';
}
