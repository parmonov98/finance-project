<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\MonthlyNetworth;
use Illuminate\Validation\ValidationException;
use App\Models\InvestPersonal;
use App\Models\LongTermInvestment;
use App\Models\ProgramSuper;
use App\Models\HomeLoanData;

class HomeLoanUpdateModal extends Component
{
    protected $listeners = ['edit', 'saved' => '$refresh', 'tablesTruncated' => 'render', 'updated'];
    // protected $listeners = ['saved' => 'render', 'updated'];
    public $is_open = false;
    public $end_balance = 0;
    public $home_loan = null;

    public $loan;
    public $int_rate;
    public $period;
    public $nb_pay;
    public $date;
    public $ext_pay;
    public $change;

    public function edit(HomeLoan $home_loan)
    {
        // $firstLoan =  HomeLoan::orderBy('id', 'ASC')->get()->first();
        // dd($loans);
        // $homeLoanData = HomeLoanData::firstWhere('start_date', $firstLoan->pay_date);
        // // dd($homeLoanData, $home_loan);
        $this->home_loan = $home_loan;
        $this->end_balance = $home_loan->end_balance;
        // $this->int_rate = $home_loan->interest;
        // $this->date = $home_loan->pay_date;
        // dd($this->date);
        $this->date_mod = $home_loan->pay_date;
        $this->is_open = true;
    }

    public function close()
    {
        $this->is_open = false;
        // $this->emitSelf('saved');
        $this->dispatchBrowserEvent('closeModalOfHomeLoan');
    }


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


    public function openUpdateHomeLoanModal($homeLoan)
    {
        $this->emitTo('home-loan-update-modal', 'edit', $homeLoan);
    }

    public function InputData()
    {
        $data = $this->validate([
            'home_value' => 'required|numeric|min:0',
            'home_app' => 'required|numeric|min:0',
        ]);

        $dates = MonthlyNetworth::all();
        foreach ($dates as $record) {
            if ($record->passed == false) {
                if ($record->id == 1) {
                    $record->home_value = $data['home_value'];
                    $record->home_app = $data['home_app'];
                    $record->passed = true;
                    $record->save();
                } else {
                    $last_record = MonthlyNetworth::where('id', $record->id - 1)->first();
                    $record->home_value = $last_record->home_value * $data['home_app'];
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

        // dd($record);

        if (!is_null($record)) {
            $check_value = false;
            $check_app = false;


            if (($this->cash_mod == null || $this->other_invest_mod == null) && ($this->home_value_mod == null && $this->home_app_mod == null)) {
                $this->validate([
                    "cash_mod" => 'required|numeric|min:0',
                    "other_invest_mod" => 'required|numeric|min:0'
                ]);

            } else if (($this->cash_mod == null || $this->other_invest_mod == null) && ($this->home_value_mod != null || $this->home_app_mod != null)) {

                if ($this->home_value_mod != null) {
                    $var_1 = $this->validate([
                        "home_value_mod" => 'numeric|min:0',
                    ]);
                    $check_value = true;
                }

                if ($this->home_app_mod != null) {
                    $var_2 = $this->validate([
                        "home_app_mod" => 'numeric|min:0',
                    ]);
                    $check_app = true;
                }

                if ($check_value != true || $check_app != true) {
                    throw ValidationException::withMessages(['home_value_mod' => 'Both of the inputs must be added']);
                    throw ValidationException::withMessages(['home_app_mod' => 'Both of the inputs must be added']);
                } else {
                    $selectedRecord = MonthlyNetworth::where('date', $date['date_mod'])->first();

                    $selectedRecord->home_value = $var_1['home_value_mod'];
                    $selectedRecord->home_app = $var_2['home_app_mod'];
                    $selectedRecord->save();

                    $from = date($date['date_mod'] ? $date['date_mod'] : null);
                    $to = MonthlyNetworth::select('date')->orderBy('id', 'DESC')->first();

                    $dates = MonthlyNetworth::whereBetween('date', [$from, $to->date])->get();


                    $first_loop = false;

                    foreach ($dates as $record) {

                        if ($record->id == 1) {
                            $record->home_value =  $var_1['home_value_mod'];
                            $record->home_app = $var_2['home_app_mod'];
                            $record->passed = true;
                            $record->save();
                        } else {
                            $last_record = MonthlyNetworth::where('id', $record->id - 1)->first();
                            if ($first_loop == false) {
                                $record->home_value = $var_1['home_value_mod'] * $var_2['home_app_mod'];
                                $first_loop = true;
                            } else {
                                $record->home_value = $last_record->home_value * $var_2['home_app_mod'];
                            }

                            $record->passed = true;
                            $record->save();
                        }
                    }
                }
            } else if (($this->cash_mod != null || $this->other_invest_mod != null) && ($this->home_value_mod != null || $this->home_app_mod != null)) {

                if ($this->home_value_mod != null) {
                    $var_1 = $this->validate([
                        "home_value_mod" => 'numeric|min:0',
                    ]);
                    $check_value = true;
                }

                if ($this->home_app_mod != null) {
                    $var_2 = $this->validate([
                        "home_app_mod" => 'numeric|min:0',
                    ]);
                    $check_app = true;
                }

                if ($check_value != true || $check_app != true) {
                    throw ValidationException::withMessages(['home_value_mod' => 'Both of the inputs must be added']);
                    throw ValidationException::withMessages(['home_app_mod' => 'Both of the inputs must be added']);
                } else {
                    $data = $this->validate([
                        "cash_mod" => 'numeric|min:0',
                        "other_invest_mod" => 'numeric|min:0',
                    ]);

                    $from = date($date['date_mod'] ? $date['date_mod'] : null);
                    $to = MonthlyNetworth::select('date')->orderBy('id', 'DESC')->first();

                    $dates = MonthlyNetworth::whereBetween('date', [$from, $to->date])->get();

                    foreach ($dates as $record) {
                        if ($record->id == 1) {
                            $record->home_value = $var_1['home_value_mod'] * $var_2['home_app_mod'];
                            $record->home_app = $var_2['home_app_mod'];
                            $record->cash = $data['cash_mod'];
                            $record->other_invest = $data['other_invest_mod'];
                            $record->passed = true;
                            $record->save();
                        } else {
                            $last_record = MonthlyNetworth::where('id', $record->id - 1)->first();
                            $record->home_value = $last_record->home_value * $var_2['home_app_mod'];
                            $record->home_app = $var_2['home_app_mod'];
                            $record->cash = $data['cash_mod'];
                            $record->other_invest = $data['other_invest_mod'];
                            $record->passed = true;
                            $record->save();
                        }
                    }
                }
            } else if (($this->cash_mod != null || $this->other_invest_mod != null) && ($this->home_value_mod == null || $this->home_app_mod == null)) {

                $data = $this->validate([
                    "cash_mod" => 'numeric|min:0',
                    "other_invest_mod" => 'numeric|min:0',
                ]);

                $from = date($date['date_mod'] ? $date['date_mod'] : null);
                $to = MonthlyNetworth::select('date')->orderBy('id', 'DESC')->first();

                $dates = MonthlyNetworth::whereBetween('date', [$from, $to->date])->get();

                foreach ($dates as $record) {
                    if ($record->id == 1) {
                        $record->cash = $data['cash_mod'];
                        $record->other_invest = $data['other_invest_mod'];
                        $record->passed = true;
                        $record->save();
                    } else {
                        $last_record = MonthlyNetworth::where('id', $record->id - 1)->first();
                        $record->cash = $data['cash_mod'];
                        $record->other_invest = $data['other_invest_mod'];
                        $record->passed = true;
                        $record->save();
                    }
                }
            }
        } else if (is_null($record)) {
            throw ValidationException::withMessages(['date_mod' => 'This value doesn\'t exits in the table']);
        }
    }

    public function ResetTables()
    {
        return null;
    }

    public function save()
    {
        // dd($this->home_loan);
        // $data = $this->FormatVariable();

        if ($this->home_loan !== null) {


            $this->home_loan->end_balance = $this->end_balance;
            $this->home_loan->save();
            // $data = $this->ModifyData();
            // dd($data);
            // $pay_date = Carbon::parse($this->home_loan->pay_date);
            // $nextHomeLoan = HomeLoan::firstWhere(DB::raw('MONTH(pay_date)'), $pay_date->month + 1);
            // $nextHomeLoan->beg_balance = $this->end_balance;
            // $nextHomeLoan->save();
            $this->emitTo('home-loans', 'saved', $this->home_loan->id, $this->home_loan->next()->pay_date);
            // $this->emitTo('monthly-networths', 'saved', $this->home_loan->id);
            $this->close();
            return true;
        }
    }

    public function render()
    {
        return view('livewire.home-loan-update-modal');
    }
}
