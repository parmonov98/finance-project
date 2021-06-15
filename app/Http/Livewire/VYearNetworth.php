<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\ProgramSuper;
use App\Models\InvestPersonal;
use App\Models\MonthlyNetworth;
use App\Models\LongTermInvestment;
use App\Models\Program5YRNetworth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VYearNetworth extends Component
{
    public $houseLoan;
    public $homeWorth;
    public $investSuper;
    public $cash;
    public $investPersonal;
    public $longTermInvest;
    public $showData;
    public $date;

    protected $rules = [
        "houseLoan" => 'required|numeric|min:0|not_in:0',
        'homeWorth' => 'required|numeric|min:0|not_in:0',
        'investSuper' => 'required|numeric|min:0|not_in:0',
        'cash' => 'required|numeric',
        'investPersonal' => 'required|numeric|min:0|not_in:0',
        'longTermInvest' => 'required|numeric|min:0|not_in:0',
    ];

    protected $messages = [
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must a date',
        '*.min:0' => 'This field must be greated than 0',
    ];

    protected $validationAttributes = [
        "houseLoan" => "house loan",
        "homeWorth" => "home worth",
        "investSuper" => "invest super",
        "cash" => "cash", 
        "investPersonal" => "invest personal",
        "longTermInvest" => "long term invest"
    ];

    public function InitializeTable()
    {
        $data = Program5YRNetworth::all()->count();

        if($data == 0)
        {   
            $dates = HomeLoan::select('pay_date')->get();

            foreach($dates as $date)
            {
                Program5YRNetworth::create([
                    "date"   => $date->pay_date,
                    "user_id" => Auth::user()->id
                ]);
            } 

        }
    }

    public function render()
    {
        $this->InitializeTable();
        $this->showData = 5;

        $start_date = HomeLoan::select('pay_date')->first();
        if(!is_null($start_date))
            $end_date = date('Y-m-d', strtotime($start_date->pay_date . " + " . $this->showData . "  years"));
        else
            $end_date = null;

        $from = date($start_date ? $start_date->pay_date : null);
        $to = date($end_date ? $end_date : null);

        $home_loans = HomeLoan::whereBetween('pay_date', [$from, $to])->get();
        $programVYear = Program5YRNetworth::whereBetween('date', [$from, $to])->get();

        // ASSETS
        $monthlyNetworths = MonthlyNetworth::whereBetween('date', [$from, $to])->get();
        $investPersonals = InvestPersonal::whereBetween('date', [$from, $to])->get();
        $longTermInvests = LongTermInvestment::whereBetween('date', [$from, $to])->get();
        $investSupers = ProgramSuper::whereBetween('date', [$from, $to])->get();

        return view('livewire.v-year-networth', [
            "home_loans" => $home_loans ? $home_loans : null,
            "programVYear" => $programVYear ? $programVYear : null,
            "investPersonals" => $investPersonals ? $investPersonals : null, 
            "longTermInvests" => $longTermInvests ? $longTermInvests : null, 
            "investSupers" => $investSupers ? $investSupers : null, 
            "monthlyNetworths" => $monthlyNetworths ? $monthlyNetworths : null
        ]);
    }


    public function InputData()
    {
        $date = $this->validate([
            'date' => 'required|date'
        ]);

        $found = MonthlyNetworth::where('date', $date['date'])->first();
        if(!is_null($found))
        {
            $this->validate();

            $record = Program5YRNetworth::where('date', $date)->first();
            
            $record->house_loan = $this->houseLoan;
            $record->home_worth = $this->homeWorth;
            $record->invest_super = $this->investSuper;
            $record->cash = $this->cash;
            $record->invest_personal = $this->investPersonal;
            $record->long_term_invest = $this->longTermInvest;

            $record->save();

        }
        else if (is_null($found))
        {
            throw ValidationException::withMessages(['date_mod' => 'This value doesn\'t exits in the table']);
        }

    }
    
}
