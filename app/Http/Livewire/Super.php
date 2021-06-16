<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\SuperData;
use App\Models\ProgramSuper;
use Illuminate\Support\Facades\Auth;

class Super extends Component
{
    public $min;
    public $max;
    public $inflation;
    public $fees;
    public $monthlyInvest;
    public $date;
    public $monthlyFee;

    protected $rules = [
        "min" => 'required|numeric',
        'max' => 'required|numeric',
        'inflation' => 'required|numeric|min:0|not_in:0',
        'fees' => 'required|numeric',
        'date' => 'required|date',
        'monthlyInvest' => 'required|numeric|min:0|not_in:0',
        'monthlyFee' => 'required|numeric|min:0|not_in:0'
    ];

    protected $messages = [
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must a date',
        '*.min:0' => 'This field must be greated than 0',
        '*.not_in:0' => 'This field must be greated than 0'
    ];

    protected $validationAttributes = [
        'min' => 'min return on invest',
        'max' => 'max return on invest',
        'monthlyInvest' => 'monthly investment',
    ];

    public function render()
    {
        $datas = ProgramSuper::orderBy('date')->get();

        return view('livewire.super', [
            "datas" => $datas,
        ]);
    }

    public function InputData()
    {
        $data = $this->FormatVariable();
        $this->InvestPersonalData($data);

        $check = ProgramSuper::select('total_invested')->orderBy('id', 'DESC')->first();

        if(!$check)
            $this->Calculate($data);
        else
            $this->Recalculate($data);   

    }

    public function FormatVariable()
    {
        $this->validate();
        $data['min'] = $this->min;
        $data['max'] =  $this->max;
        $data['inflation'] = $this->inflation / 100; // inflation
        $data['fees'] = $this->fees / 100; // fees percentages
        $data['monthlyInvest'] = $this->monthlyInvest; // monthly_invest
        $data['monthlyFee'] = $this->monthlyFee;
        $data['date'] = $this->date;

        return $data;
    }

    public function InvestPersonalData($data)
    {
        $db_data = SuperData::get()->first();

        if (!$db_data) {

            SuperData::create([
                "min" => $data['min'],
                "max" => $data['max'],
                "monthly_invest" => $data['monthlyInvest'],
                "fees" => $data['fees'],
                "start_date" => $data['date'],
                "inflation" => $data['inflation'],
                "user_id" => Auth::user()->id
            ]);
        } else if ($db_data) {

            SuperData::truncate();
            SuperData::create([
                "min" => $data['min'],
                "max" => $data['max'],
                "monthly_invest" => $data['monthlyInvest'],
                "fees" => $data['fees'],
                "start_date" => $data['date'],
                "inflation" => $data['inflation'],
                "user_id" => Auth::user()->id
            ]);
        }
    }

    public function Calculate($data)
    {
        $dates = HomeLoan::select('pay_date')->orderBy('pay_date')->get();

        foreach($dates as $date)
        {
            $data['monthlyInvest'] = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']) / 12);

            $data['return_on_invest'] = rand($data['min'], $data['max']) / 100;

            $data['interest'] = ($data['return_on_invest'] * $data['monthlyInvest']) / 12;

            $data['after_fees'] = (($data['fees'] * $data['monthlyInvest']) / 12) + $data['monthlyFee'];

            $data['total_invested'] = $data['monthlyInvest'] + $data['interest'] - $data['after_fees'];

            ProgramSuper::create([
                "user_id" => Auth::user()->id,
                "fees" => $data['fees'],
                "monthly_account_fee" => $data['monthlyFee'],
                "inflation" => $data['inflation'],
                "monthly_invest" => $data['monthlyInvest'], 
                "interest" => $data['interest'],
                "after_fees" => $data['after_fees'],
                "total_invested" => $data['total_invested'],
                "date" => $date->pay_date,
                "return_on_invest" => $data['return_on_invest']
            ]);
        }
    }

    public function Recalculate($data)
    {
        $from = $data['date'];
        $to = HomeLoan::select('pay_date')->orderBy('id', 'DESC')->first();
        $dates = HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->orderBy('pay_date')->get();;
        $change = ProgramSuper::whereBetween('date', [$from, $to->pay_date])->get();

        foreach($dates as $key => $date)
        {
            $monthlyInvest = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']) / 12);
            $return_on_invest = rand($data['min'], $data['max']) / 100;
            $interest = ($return_on_invest * $data['monthlyInvest']) / 12;
            $after_fees = (($data['fees'] * $monthlyInvest) / 12) + $data['monthlyFee'];
            $total_invested = $monthlyInvest + $interest - $after_fees;

            $change[$key]->fees = $data['fees']; 
            $change[$key]->monthly_account_fee = $data['monthlyFee']; 
            $change[$key]->inflation = $data['inflation'];
            $change[$key]->monthly_invest = $monthlyInvest;
            $change[$key]->interest = $interest;
            $change[$key]->after_fees = $after_fees; 
            $change[$key]->total_invested = $total_invested;            
            $change[$key]->date = $date->pay_date;
            $change[$key]->return_on_invest = $return_on_invest;
            $change[$key]->save();
        }
    }

    public function ResetTables()
    {
        ProgramSuper::truncate();
        SuperData::truncate();
    }
}
