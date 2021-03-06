<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Program5YRNetworth extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'house_loan',
        'home_worth',
        'invest_super',
        'cash',
        'invest_personal',
        'long_term_invest',
        'total_debt',
        'total_assets',
        'difference_minus_super',
        'difference',
        'other_invest'
    ];

    public function formatDate()
    {
        return Carbon::parse($this->date)->format('d-m-Y');
    }

}
