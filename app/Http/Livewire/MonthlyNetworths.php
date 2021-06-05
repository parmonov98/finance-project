<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\ProgramSuper;
use App\Models\InvestPersonal;
use App\Models\MonthlyNetworth;
use App\Models\LongTermInvestment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MonthlyNetworths extends Component
{

    public $home_value_mod;
    public $cash_mod;
    public $home_app_mod;
    public $other_invest_mod;
    public $date_mod;
    public $show_data_mod;

    public $home_value;
    public $cash;
    public $home_app;
    public $other_invest;
    public $show_data;

    protected $messages = [
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must a date',
        '*.min:0' => 'This field must be greated than 0',
    ];

    protected $validationAttributes = [
        'home_value' => 'home value',
        'cash' => 'cash',
        'home_app' => 'home app',
        'other_invest' => 'other investments',

        'home_value_mod' => 'home value',
        'cash_mod' => 'cash',
        'home_app_mod' => 'home app',
        'other_invest_mod' => 'other investments',
        'date_mod' => 'date'
    ];

    public function render()
    {
        $this->show_data = 5;
        $start_date = HomeLoan::select('pay_date')->first();
        if(!is_null($start_date))
            $end_date = date('Y-m-d', strtotime($start_date->pay_date . " + " . $this->show_data . "  years"));
        else
            $end_date = null;

        $from = date($start_date ? $start_date->pay_date : null);
        $to = date($end_date ? $end_date : null);

        $home_loan = HomeLoan::select('pay_date', 'end_balance')->whereBetween('pay_date', [$from, $to])->get();


        // ASSETS
        $home_values = MonthlyNetworth::select('home_value')->whereBetween('date', [$from, $to])->get();
        $cashs = MonthlyNetworth::select('cash')->whereBetween('date', [$from, $to])->get();
        $investPersonals = InvestPersonal::whereBetween('date', [$from, $to])->get();
        $longTermInvests = LongTermInvestment::whereBetween('date', [$from, $to])->get();
        $investSupers = ProgramSuper::whereBetween('date', [$from, $to])->get();
        $other_invests = MonthlyNetworth::select('other_invest')->whereBetween('date', [$from, $to])->get();

        return view('livewire.monthly-networths', [
            "home_loan" => $home_loan,
            "home_values" => $home_values,
            "cashs" => $cashs,
            "other_invests" => $other_invests,
            "investSupers" => $investSupers,
            "investPersonals" => $investPersonals,
            "longTermInvests" => $longTermInvests
        ]);
    }

    public function InputData()
    {
        $data = $this->validate([
            'home_value' => 'required|numeric|min:0',
            'home_app' => 'required|numeric|min:0',
        ]);
        
        $dates = MonthlyNetworth::all();
        foreach($dates as $record){
            if($record->passed == false)
            {
                if($record->id == 1)
                {
                    $record->home_value = $data['home_value'];
                    $record->home_app = $data['home_app'];
                    $record->passed = true;
                    $record->save();
                }
                else
                {
                    $last_record = MonthlyNetworth::where('id', $record->id-1)->first();
                    $record->home_value = $last_record->home_value*$data['home_app'];
                    $record->home_app = $data['home_app'];
                    $record->passed = true;
                    $record->save();
                }
            }
        }
        
    }

    public function ModifyData()
    {

        $date = $this->validate([
            'date_mod' => 'required|date'
        ]);

        $record = MonthlyNetworth::where('date', $date['date_mod'])->first();

        if(!is_null($record))
        {
            $check_value = false;
            $check_app = false;


            if(($this->cash_mod == null || $this->other_invest_mod == null) && ($this->home_value_mod == null && $this->home_app_mod == null ) )
            {
                $data = $this->validate([
                    "cash_mod" => 'required|numeric|min:0',
                    "other_invest_mod" => 'required|numeric|min:0'
                ]);
            }
            else if(($this->cash_mod == null || $this->other_invest_mod == null) && ($this->home_value_mod != null || $this->home_app_mod != null ))
            {

                if($this->home_value_mod != null)
                {
                    $array['home_value_mod'] = $this->validate([
                        "home_value_mod" => 'numeric|min:0',
                    ]);
                    $check_value = true;
                }
    
                if($this->home_app_mod != null)
                {
                    $array['home_app_mod'] = $this->validate([
                        "home_app_mod" => 'numeric|min:0',
                    ]); 
                    $check_app = true;
                }

                if($check_value != true || $check_app != true)
                {
                    throw ValidationException::withMessages(['home_value_mod' => 'Both of the inputs must be added']);
                    throw ValidationException::withMessages(['home_app_mod' => 'Both of the inputs must be added']);
                }
                else
                {
                    $selectedRecord = MonthlyNetworth::where('date', $date['date_mod'])->first();

                    $selectedRecord->home_value = null;
                    $selectedRecord->home_app = null;
                    $selectedRecord->save();

                    $selectedRecord->home_value = $array['home_value_mod'];
                    $selectedRecord->home_app = $array['home_app_mod'];
                    $selectedRecord->save();


                    dd("die dump");

                    $from = date($date['date_mod'] ? $date['date_mod'] : null);
                    $to = MonthlyNetworth::select('date')->orderBy('id', 'DESC')->first();

                    $dates = MonthlyNetworth::whereBetween('date', [$from, $to->date] )->get();

                    foreach($dates as $record){
                        if($record->id == 1)
                        {
                            $record->home_value =  1.00;
                            $record->home_app = 1.00;
                            $record->passed = true;
                            $record->save();
                            
                        }
                        else
                        {
                            $last_record = MonthlyNetworth::where('id', $record->id-1)->first();
                            $record->home_value = $last_record->home_value*$home_app_mod;
                            $record->passed = true;
                            $record->save();
                        }
                    }
                }
            }
            else if (($this->cash_mod != null || $this->other_invest_mod != null) && ($this->home_value_mod != null || $this->home_app_mod != null ))
            {
                dd("all not null");
                if($this->home_value_mod != "" )
                {
                    $data = $this->validate([
                        "home_value_mod" => 'numeric|min:0',
                    ]);
                    $check_value == true;
                }
    
                if($this->home_app_mod != "")
                {
                    $data = $this->validate([
                        "home_app_mod" => 'numeric|min:0',
                    ]); 
                    $check_app == true;
                }
    
                if($check_value != true && $check_app != true)
                {
                    throw ValidationException::withMessages(['home_value_mod' => 'Both of the inputs must be added']);
                    throw ValidationException::withMessages(['home_app_mod' => 'Both of the inputs must be added']);
                }
                else
                {
                    $from = date($data['date_mod'] ? $data['date_mod']->pay_date : null);
                    $to = MonthlyNetworth::select('date')->oredrBy('id', 'DESC')->first();

                    $dates = HomeLoan::select('tot_payment')->whereBetween('pay_date', [$from, $to])->sum('tot_payment');

                    foreach($dates as $record){
                        if($record->passed == false)
                        {
                            if($record->id == 1)
                            {
                                $record->home_value = $data['home_value'];
                                $record->home_app = $data['home_app'];
                                $record->passed = true;
                                $record->save();
                            }
                            else
                            {
                                $last_record = MonthlyNetworth::where('id', $record->id-1)->first();
                                $record->home_value = $last_record->home_value*$data['home_app'];
                                $record->passed = true;
                                $record->save();
                            }
                        }
                    }

                    $data = $this->validate([
                        "cash_mod" => 'required|numeric|min:0',
                        "other_invest_mod" => 'required|numeric|min:0'
                    ]);

                    $record->cash = $data['cash_mod'];
                    $record->other_invest = $data['other_invest_mod'];
                    $record->save();

                }

            }

            

            
        }
        else if (is_null($record))
        {
            throw ValidationException::withMessages(['date_mod' => 'This value doesn\'t exits in the table']);
        }
    }

    public function ResetTables()
    {
        return null;
    }
}


