<?php

namespace App\Http\Livewire;

use App\Models\SuperData;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    public $show = 1;
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
//            $dates = HomeLoan::select('pay_date')->orderBy('id')->get();
            $homeLoans = HomeLoan::select(
                '*',
                DB::raw('CEIL(MONTH(pay_date) / 6) AS monthVALUE'),
                DB::raw('CEIL(YEAR(pay_date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
//            dd($homeLoans);
            $monthlyWorths = MonthlyNetworth::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 6) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
            $superInvests = ProgramSuper::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 6) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
            $personalInvests = InvestPersonal::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 6) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
            $longTermInvests = LongTermInvestment::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 6) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
//            dd($monthlyWorths, $homeLoans, $superInvests, $personalInvests, $longTermInvests);

                foreach($homeLoans as $key => $date)
                {
                    Program5YRNetworth::create([
                        "date"   => $homeLoans->get($key)->pay_date,
                        "user_id" => Auth::user()->id,
                        "house_loan"   => $homeLoans->get($key)->end_balance,
                        "home_worth" => $monthlyWorths->get($key)->home_value,
                        "cash" => $monthlyWorths->get($key)->cash,
                        "invest_super" => $superInvests->get($key)->total_invested,
                        "invest_personal" => $personalInvests->get($key)->total_invested,
                        "long_term_invest" => $longTermInvests->get($key)->total_invested,
                    ]);
                }


//            if(!is_null($dates) && $dates->count() > 0)
//            {
//                $first = $dates[0];
//                $i = 0 ;
//                $tab[] = null;
//
//                while(true)
//                {
//                    $monthsToAdd = $i*6;
//
//                    $time = date('Y-m-d', strtotime($first->pay_date . '+' . $monthsToAdd .  ' months'));
//                    $data = HomeLoan::select('pay_date')->where('pay_date', $time)->first();
////                    $data = MonthlyNetworth::select('home_value')->where('pay_date', $time)->first();
//
//                    $i++;
//
//                    if(!is_null($data))
//                    {
//                        Program5YRNetworth::create([
//                            "date"   => $data->pay_date,
//                            "user_id" => Auth::user()->id,
//                            "home_worth" => $monthlyWorths[$key]->home_value
//                        ]);
//                    }
//                    else
//                        break;
//
//
//                }
//
//            }

        }
    }

    public function render()
    {
        $this->InitializeTable();

        $start_date = HomeLoan::select('pay_date')->first();
        if(!is_null($start_date))
        {
            if($this->show < 1)
                $end_date = date('Y-m-d', strtotime($start_date->pay_date . " + " . 6 . "  months"));
            else if($this->show >= 1 )
                $end_date = date('Y-m-d', strtotime($start_date->pay_date . " + " . $this->show . "  years"));
            else if ($this->show = "x")
                $end_date = date('Y-m-d', strtotime($start_date->pay_date . " + " . $this->show . "  years"));
        }else
            $end_date = null;

        $from = date($start_date ? $start_date->pay_date : null);
        $to = date($end_date ? $end_date : null);

        $programVYear = Program5YRNetworth::whereBetween('date', [$from, $to])->get();

        $home_loans = array();
        $monthlyNetworths = array();
        $investPersonals = array();
        $longTermInvests = array();
        $investSupers = array();

        foreach($programVYear as $date)
        {
            array_push($home_loans, HomeLoan::where('pay_date', $date->date)->first());
            array_push($monthlyNetworths, MonthlyNetworth::where('date', $date->date)->first());
            array_push($investPersonals, InvestPersonal::where('date', $date->date)->first());
            array_push($longTermInvests, LongTermInvestment::where('date', $date->date)->first()) ;
            array_push($investSupers, ProgramSuper::where('date', $date->date)->first());
        }

        return view('livewire.v-year-networth', [
            "home_loans" => $home_loans ,
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


    public function ResetTables()
    {
        Program5YRNetworth::truncate();
    }

}
