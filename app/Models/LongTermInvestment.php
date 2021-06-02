<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongTermInvestment extends Model
{
    use HasFactory;

    protected $fillable = [ 'return_on_invest', 'fees', 'monthly_account_fee', 'inflation', 'monthly_invest', 'interest', 'after_fees', 'total_invested' , 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formatDate()
    {
        return Carbon::parse($this->pay_date)->format('d-m-Y');
    }
    
}
