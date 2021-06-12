<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramSuper extends Model
{
    use HasFactory;

    protected $fillable = [ 'return_on_invest', 'fees', 'monthly_account_fee', 'inflation', 'monthly_invest', 'interest', 'after_fees', 'total_invested', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formatDate()
    {
        return Carbon::parse($this->date)->format('d-m-Y');
    }
}
