<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
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

    protected $listeners = ['saved', 'rerender' => '$refresh'];

    public function rerender()
    {
        dd(1);
        $this->render();
    }


    public function render()
    {
        $this->show_data = 5;
        $start_date = HomeLoan::select('pay_date')->first();
        if (!is_null($start_date))
            $end_date = date('Y-m-d', strtotime($start_date->pay_date . " + " . $this->show_data . "  years"));
        else
            $end_date = null;

        $from = date($start_date ? $start_date->pay_date : null);
        $to = date($end_date ? $end_date : null);

        $home_loans = HomeLoan::select('pay_date', 'end_balance', 'id')->whereBetween('pay_date', [$from, $to])->get();

        $dates = MonthlyNetworth::select('date')->whereBetween('date', [$from, $to])->get();

        // ASSETS
        $home_values = MonthlyNetworth::whereBetween('date', [$from, $to])->get();
        $cashs = MonthlyNetworth::whereBetween('date', [$from, $to])->get();
        $investPersonals = InvestPersonal::whereBetween('date', [$from, $to])->get();
        $longTermInvests = LongTermInvestment::whereBetween('date', [$from, $to])->get();
        $investSupers = ProgramSuper::whereBetween('date', [$from, $to])->get();
        $other_invests = MonthlyNetworth::whereBetween('date', [$from, $to])->get();

        $value1 = null;
        $value2 = null;
        $value3 = null;
        $value4 = null;
        $value5 = null;
        $value6 = null;

        foreach ($dates as $date) {
            $home_value = MonthlyNetworth::select('home_value')->Where('date', $date->date)->first();
            $cash = MonthlyNetworth::select('cash')->Where('date', $date->date)->first();
            $investPersonal = InvestPersonal::select('total_invested')->Where('date', $date->date)->first();
            $longTermInvest = LongTermInvestment::select('total_invested')->Where('date', $date->date)->first();
            $investSuper = ProgramSuper::select('total_invested')->Where('date', $date->date)->first();
            $other_invest = MonthlyNetworth::select('other_invest')->Where('date', $date->date)->first();


            $value1 += $home_value->home_value ? $home_value->home_value : 0;
            $value2 += $cash->cash ? $cash->cash : 0;
            $value3 += $investPersonal ? $investPersonal->total_invested : 0;
            $value4 += $longTermInvest ? $longTermInvest->total_invested : 0;
            $value5 += $investSuper ? $investSuper->total_invested : 0;
            $value6 += $other_invest->other_invest ? $other_invest->other_invest : 0;

//            dd($date->date);

            $assets[] = $value1 + $value2 + $value3 + $value4 + $value5 + $value6;
            $value1 = null;
            $value2 = null;
            $value3 = null;
            $value4 = null;
            $value5 = null;
            $value6 = null;
//            if ($date->date == '2021-02-01'){
//                dd($assets);
//            }
        }
        // End ASSETS

        // DIFFERENCE
        $home_loans_savings = DB::table('home_loans_savings')->whereBetween('pay_date', [$from, $to])->get();
//        dd($home_loans_savings);
//        dd($home_values);
        foreach ($home_values as $key => $record)
            $difference[] = $assets[$key] - $home_loans_savings[$key]->end_balance;

// End DIFFERENCE


// DIFFERENCE SUPER
        foreach ($investSupers as $key => $record)
            $differenceSuper[] = $difference[$key] - $record->total_invested;

// End DIFFERENCE SUPER

        if (isset($differenceSuper)) {
// RUNNING DIFF
            foreach ($differenceSuper as $key => $record) {
                if (!$key == 0)
                    $runningDiff[] = $differenceSuper[$key] - $differenceSuper[$key - 1];
                else
                    $runningDiff[] = 0;
            }
// End RUNNING DIFF

            foreach ($difference as $key => $record) {
                if ($key != 0)
                    $overallDiff[] = $difference[$key] - $difference[$key - 1];
                else
                    $overallDiff[] = 0;
            }
        }


//        dd($home_loans);
        $dates = MonthlyNetworth::select('date')->whereBetween('date', [$from, $to])->get();
        return view('livewire.monthly-networths', [
            'dates' => $dates,
            "home_loans" => $home_loans,
            "home_values" => $home_values,
            "cashs" => $cashs,
            "other_invests" => $other_invests,
            "investSupers" => $investSupers,
            "investPersonals" => $investPersonals,
            "longTermInvests" => $longTermInvests,
            "assets" => isset($assets) ? $assets : null,
            "difference" => isset($difference) ? $difference : null,
            "differenceSuper" => isset($differenceSuper) ? $differenceSuper : null,
            "runningDiff" => isset($runningDiff) ? $runningDiff : null,
            "overallDiff" => isset($overallDiff) ? $overallDiff : null
        ]);
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
                            $record->home_value = $var_1['home_value_mod'];
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

//                    dd($var_1, $var_2);

                    $monthlyPercent = (isset($var_1['home_app_mod']) ? $var_1['home_app_mod'] : $var_2['home_app_mod']) / 12;
//                    dd($monthlyPercent);
                    foreach ($dates as $index => $record) {
//                        dd($record);
                        if ($index == 0) {
                            $record->home_value = $var_1['home_value_mod'] + $var_1['home_value_mod'] / 100 * $monthlyPercent;
                            $record->home_app = $var_2['home_app_mod'];
                            $record->cash = $data['cash_mod'];
                            $record->other_invest = $data['other_invest_mod'];
                            $record->passed = true;
                            $record->save();
                        } else {
                            $last_record = $record->prev();
//                            dd($last_record);
                            $record->home_value = $last_record->home_value + $last_record->home_value / 100 * $monthlyPercent;
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
}
