<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeLoanData extends Model
{
    use HasFactory;

    protected $fillable = [ 'loan_amount', 'int_rate', 'loan_period', 'no_payments', 'start_date', 'opt_payments' ];

    public function homeloan()
    {
        $this->belongsTo(HomeLoan::class);
    }
}
