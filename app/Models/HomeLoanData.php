<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeLoanData extends Model
{
    use HasFactory;
    protected $table = 'home_loans_datas';

    protected $fillable = [ 'loan_amount', 'int_rate', 'loan_period', 'no_payments', 'start_date', 'opt_payment', 'date', 'user_id', 'sch_payment'];

    public function homeloan()
    {
        $this->belongsTo(HomeLoan::class);
    }
}
