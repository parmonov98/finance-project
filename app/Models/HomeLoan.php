<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeLoan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pmt_no', 'pay_date', 'beg_balance', 'sch_payment', 'ext_payment', 'tot_payment', 'principal', 'interest', 'end_balance', 'cum_interest'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formatDate()
    {
        return Carbon::parse($this->pay_date)->format('d-m-Y');
    }

    public function formatNumber($number)
    {
        return number_format($number, 2, '.', ',');
    }

    // in your model file
    public function next(){
        // get next user
        return $this->where('id', '>', $this->id)->orderBy('id','asc')->first();

    }
    public function prev(){
        // get prev user
        return $this->where('id', '<', $this->id)->orderBy('id','desc')->first();

    }

}
