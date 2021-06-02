<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperData extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'super_data';

    protected $fillable = [ 'min', 'max', 'inflation', 'fees', 'start_date', 'user_id', 'monthly_invest' ];


    public function InvestPersonal()
    {
        $this->belongsTo(InvestPersonal::class);
    }
}
