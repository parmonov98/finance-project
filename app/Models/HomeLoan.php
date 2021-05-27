<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeLoan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pmt_no', 'pay_date', 'beg_balance', 'sch_payment', 'ext_payment', 'tot_payment', 'principal', 'interest', 'end_balance', 'cum_interest'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
