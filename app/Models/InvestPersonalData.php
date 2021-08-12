<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestPersonalData extends Model
{
    use HasFactory;

    protected $table = 'invest_personals_datas';

    protected $fillable = [ 'min', 'max', 'inflation', 'fees', 'start_date', 'user_id', 'monthly_invest' ];


    public function InvestPersonal()
    {
        $this->belongsTo(InvestPersonal::class);
    }
}
