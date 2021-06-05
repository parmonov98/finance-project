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
         
        
        $record = MonthlyNetworth::where('home_value', null)->first();

        if($record->passed == false)
        {
            if($record->id == 1)
            {

                $data = $this->validate([
                    'home_value' => 'required|numeric|min:0',
                    'cash' => 'required|numeric|min:0',
                    'home_app' => 'numeric|min:0',
                    'other_invest' => 'numeric|min:0',
                ]);

                $record->home_value = $data['home_value'];
                $record->home_app = $data['home_app'];
                $record->cash = $data['cash'];
                $record->other_invest = $data['other_invest'];
                $record->passed = true;
                $record->save();
            }
            else
            {

                $data = $this->validate([
                    'home_value' => 'numeric|min:0',
                    'cash' => 'numeric|min:0',
                    'home_app' => 'required|numeric|min:0',
                    'other_invest' => 'required|numeric|min:0',
                ]);


                $last_record = MonthlyNetworth::where('id', $record->id-1)->first();
                $record->home_value = $last_record->home_value*$data['home_app'];
                $record->home_app = $data['home_app'];
                $record->cash = $data['cash'];
                $record->other_invest = $data['other_invest'];
                $record->passed = true;
                $record->save();
            }
        }
    }

    public function ModifyData()
    {
        $data = $this->validate([
            'date_mod' => 'required|date',
        ]);

        $record = MonthlyNetworth::where('date', $data['date_mod'])->first();

        if(!is_null($record))
        {
            $data = $this->validate([
                'home_value_mod' => 'numeric|min:0',
                'cash_mod' => 'numeric|min:0',
                'home_app_mod' => '|numeric|min:0',
                'other_invest_mod' => 'numeric|min:0'
            ]);


            $record->home_value = $data['home_value_mod'];
            $record->cash = $data['cash_mod'];
            $record->home_app = $data['home_app_mod'];
            $record->other_invest = $data['other_invest_mod'];

            $record->save();
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
