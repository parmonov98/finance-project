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
                DB::raw('MONTH(pay_date) AS monthNUMBER'),
                DB::raw('CEIL(MONTH(pay_date) / 5) AS monthVALUE'),
                DB::raw('CEIL(YEAR(pay_date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
//            dd($homeLoans);
            $monthlyNetworths = MonthlyNetworth::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
            $superInvests = ProgramSuper::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
            $personalInvests = InvestPersonal::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
            $longTermInvests = LongTermInvestment::select(
                '*',
                DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
                DB::raw('CEIL(YEAR(date)) AS yearVALUE')
            )->groupBy('monthVALUE')
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
//            dd($monthlyWorths, $homeLoans, $superInvests, $personalInvests, $longTermInvests);



            foreach($homeLoans as $key => $date)
                {
                    if ($date->monthVALUE == 3){
                        if ($homeLoans->last() !== $date){
                            $homeLoans[$key] = $homeLoans[$key]->next();
                            $monthlyNetworths[$key] = $monthlyNetworths[$key]->next();
                            $superInvests[$key] = $superInvests[$key]->next();
                            $personalInvests[$key] = $personalInvests[$key]->next();
                            $longTermInvests[$key] = $longTermInvests[$key]->next();
                            $total_assets = $monthlyNetworths[$key]->home_value + $longTermInvests[$key]->total_invested;
                            $difference = $total_assets - $date->beg_balance;
                            $difference_super = $total_assets - $date->beg_balance - $superInvests[$key]->total_invested;
                        }
                    }else{
                        $total_assets = $monthlyNetworths[$key]->home_value + $longTermInvests[$key]->total_invested;
                        $difference = $total_assets - $date->beg_balance;
                        $difference_super = $total_assets - $date->beg_balance - $superInvests[$key]->total_invested;

                    }


                    Program5YRNetworth::create([
                        "date"   => $homeLoans->get($key)->pay_date,
                        "user_id" => Auth::user()->id,
                        "house_loan"   => $homeLoans->get($key)->end_balance,
                        "home_worth" => $monthlyNetworths->get($key)->home_value,
                        "cash" => $monthlyNetworths->get($key)->cash,
                        "other_invest" => $monthlyNetworths->get($key)->other_invest,
                        "invest_super" => $superInvests->get($key)->total_invested,
                        "invest_personal" => $personalInvests->get($key)->total_invested,
                        "long_term_invest" => $longTermInvests->get($key)->total_invested,
                        "total_debt" => $homeLoans->get($key)->end_balance,
                        "total_assets" => $total_assets,
                        "difference" => $difference,
                        "difference_minus_super" => $difference_super,
                    ]);
                }

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
//        dd($programVYear);

//        dd($from, $to);

        $dates = MonthlyNetworth::select('date')->whereBetween('date', [$from, $to])->get();

        // ASSETS
        $monthlyNetworths = MonthlyNetworth::select(
            '*',
            DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
            DB::raw('CEIL(YEAR(date)) AS yearVALUE')
        )->groupBy('monthVALUE')
            ->whereBetween('date', [$from, $to])
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
        $longTermInvests = LongTermInvestment::select(
            '*',
            DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
            DB::raw('CEIL(YEAR(date)) AS yearVALUE')
        )->groupBy('monthVALUE')
            ->whereBetween('date', [$from, $to])
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
        $investPersonals = InvestPersonal::select(
            '*',
            DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
            DB::raw('CEIL(YEAR(date)) AS yearVALUE')
        )->groupBy('monthVALUE')
            ->whereBetween('date', [$from, $to])
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
        $investSupers = ProgramSuper::select(
            '*',
            DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
            DB::raw('CEIL(YEAR(date)) AS yearVALUE')
        )->groupBy('monthVALUE')
            ->whereBetween('date', [$from, $to])
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();
        $home_values = MonthlyNetworth::select(
            '*',
            DB::raw('CEIL(MONTH(date) / 5) AS monthVALUE'),
            DB::raw('CEIL(YEAR(date)) AS yearVALUE')
        )->groupBy('monthVALUE')
            ->whereBetween('date', [$from, $to])
            ->groupBy('yearVALUE')
            ->orderBy('id')->get();


//        dd($programVYear);

        $home_loans = [];
        $monthlyNetworths = [];
        $investPersonals = [];
        $longTermInvests = [];
        $investSupers = [];


        foreach($programVYear as $date)
        {
            if (Carbon::today() > Carbon::parse($date->date)){
                $currentHomeLoan = HomeLoan::where('pay_date', $date->date)->first();
                array_push($home_loans, $currentHomeLoan);
            }else{
                array_push($home_loans, HomeLoan::select('pay_date')->where('pay_date', $date->date)->first());
            }

            if (Carbon::today() > Carbon::parse($date->date)){
                $currentPersonalInvest =  InvestPersonal::where('date', $date->date)->first();
                array_push($investPersonals, $currentPersonalInvest);
            }else{
                array_push($investPersonals, InvestPersonal::select('date')->where('date', $date->date)->first());
            }
            if (Carbon::today() > Carbon::parse($date->date)){
                $currentLongTermInvest =  LongTermInvestment::where('date', $date->date)->first();
                array_push($longTermInvests, $currentLongTermInvest) ;
            }else{
                array_push($longTermInvests, LongTermInvestment::select('date')->where('date', $date->date)->first()) ;
            }

            if (Carbon::today() > Carbon::parse($date->date)){
                $currentSuperInvest =  ProgramSuper::where('date', $date->date)->first();
                array_push($investSupers, $currentSuperInvest);
            }else{
                array_push($investSupers, ProgramSuper::select('date')->where('date', $date->date)->first());
            }


            if (Carbon::today() > Carbon::parse($date->date)){
                $currentMonthlyNetworth =  MonthlyNetworth::where('date', $date->date)->first();
                $currentMonthlyNetworth->assets = $currentMonthlyNetworth->home_value + $currentMonthlyNetworth->cash
                    + $currentMonthlyNetworth->other_invest + $currentPersonalInvest->total_invested + $currentLongTermInvest->total_invested
                    + $currentSuperInvest->total_invested;
                $currentMonthlyNetworth->difference = $currentMonthlyNetworth->assets - $currentHomeLoan->beg_balance;
                $currentMonthlyNetworth->difference_super = $currentMonthlyNetworth->assets - $currentHomeLoan->beg_balance - $currentSuperInvest->total_invested;
                array_push($monthlyNetworths, $currentMonthlyNetworth);
            }else{
                array_push($monthlyNetworths, MonthlyNetworth::select('date')->where('date', $date->date)->first());
            }
        }

//        dd($monthlyNetworths, $investPersonals, $longTermInvests, $investSupers);

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
