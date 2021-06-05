<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyNetworth extends Model
{
    use HasFactory;

    protected $fillable = [ 'home_value', 'date', 'home_app', 'cash', 'other_invest', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formatDate()
    {
        return Carbon::parse($this->date)->format('d-m-Y');
    }
}
