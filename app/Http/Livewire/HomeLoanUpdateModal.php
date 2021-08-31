<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
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
    protected $listeners = ['edit', 'saved' => '$refresh', 'tablesTruncated' => 'render', 'updated', 'close'];

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

    protected $rules = [
        'loan' => 'required|numeric|min:0|not_in:0',
        'period' => 'required|numeric|min:0|not_in:0',
        'date' => 'required|date',
        'int_rate' => 'required|numeric|min:0|not_in:0',
        'nb_pay' => 'required|numeric|min:0|not_in:0',
        'ext_pay' => 'required|numeric|min:0',
        'date' => 'required|date',
    ];

    protected $messages = [
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must a date',
        '*.min:0' => 'This field must be greated than 0',
        '*.not_in:0' => 'This field must be greated than 0'
    ];

    protected $validationAttributes = [
        'loan' => 'loan amount',
        'int_rate' => 'interest rate',
        'nb_pay' => 'number of payments',
        'ext_pay' => 'extra payments',
    ];

    public function edit(HomeLoan $home_loan)
    {
//        dd($home_loan);
        $homeLoanData = HomeLoanData::all()->first();
        $this->home_loan = $home_loan;
        $this->end_balance = $home_loan->end_balance;
        $this->int_rate = $home_loan->interest;
        $this->date = $home_loan->pay_date;
//        $this->nb_pay = $homeLoanData->nb_pay;
        $this->date_mod = $home_loan->pay_date;
        $this->is_open = true;
    }

    public function save()
    {
        if ($this->home_loan !== null) {
            $this->home_loan->end_balance = $this->end_balance;
            $this->home_loan->save();
            $this->home_loan->refresh();

            $this->update($this->home_loan, $this->home_loan->next()->pay_date);
            $this->emitTo('monthly-networths', 'rerender');
//            $this->emitTo('home-loans', 'rerender');
            $this->close();
        }
    }


    public function update(HomeLoan $home_loan, $start_date)
    {
        $db_data = HomeLoanData::where('user_id', Auth::id())->first();
        $this->loan = $home_loan->end_balance;

        $paidRecords = HomeLoan::where('pay_date', '<', $home_loan->pay_date)->where('user_id', auth()->id())->get();

        $this->date = $start_date;
        $this->int_rate = $db_data->int_rate * 100;
        $this->period = round(round($db_data->loan_period * 12 - $paidRecords->count(), 1) / 12, 1);
//         dd($this->period);
        $this->ext_pay = $db_data->opt_payment;
        $this->nb_pay = $db_data->no_payments;
        $this->Modifydata();
    }

    public function FormatVariable()
    {
        // throw ValidationException::withMessages(['date' => 'This value is incorrect']);
        $this->validate();
        // $data['date'] = date('d-m-Y', strtotime($this->date)); // convert to d-m-Y format;
        $data['date'] = $this->date;
        $data['interest_rate'] =  $this->int_rate / 100;
        $data['nb_payments'] = $this->nb_pay; //months
        $data['loan_period'] = $this->period; // years
        // dd($this->loan);
        $data['loan_amount'] = $this->loan; // loan amount
        $data['ext_payment'] = $this->ext_pay;

        return $data;
    }

    public function scheduled_payment($data)
    {
//        $up = $data['interest_rate'] * $data['loan_amount'];
        // dd($data['interest_rate'], $data['nb_payments']);
//        $pow = pow(1 + ($data['interest_rate'] / $data['nb_payments']), -$data['nb_payments'] * $data['loan_period']);
        $homeLoanData = HomeLoanData::where('user_id', Auth::id())->first();
        $data['sch_payment'] = $homeLoanData->sch_payment;

        return $data;
    }

    public function home_loan_data($data)
    {
        $db_data = HomeLoanData::get()->first();

        if (!$db_data) {

            HomeLoanData::create([
                "loan_amount" => $data['loan_amount'],
                "int_rate" => $data['interest_rate'],
                "loan_period" => $data['loan_period'],
                "no_payments" => $data['nb_payments'],
                "start_date" => $data['date'],
                "sch_payment" => $data['sch_payment'],
                "opt_payment" => $data['ext_payment'],
                "user_id" => Auth::user()->id
            ]);

        } else if ($db_data) {

            if ($data['date'] != $db_data->start_date) {
                $db_data->start_date = $data['date'];
                $db_data->save();
            }

            if ($data['loan_amount'] != $db_data->loan_amount) {
                HomeLoanData::truncate();
                HomeLoanData::create([
                    "loan_amount" => $data['loan_amount'],
                    "int_rate" => $data['interest_rate'],
                    "loan_period" => $data['loan_period'],
                    "no_payments" => $data['nb_payments'],
                    "start_date" => $data['date'],
                    "sch_payment" => $data['sch_payment'],
                    "opt_payment" => $data['ext_payment'],
                    "user_id" => Auth::user()->id
                ]);
            }
        }
    }


    public function Recalculate($data)
    {

        $last_record = HomeLoan::select('end_balance', 'principal', 'interest')->orderBy('id', 'DESC')->first();

        $stop = null;
        do {
            $last_record = HomeLoan::select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest')->orderBy('id', 'DESC')->first();
            $data['beg_balance'] = $last_record->end_balance;

            $daystosum = 1;
            $date = date('Y-m-d', strtotime($last_record->pay_date . ' + ' . $daystosum . ' months')); // add days to d-m-Y format

            $data['pmt_no'] = $last_record->pmt_no + 1;

            $data['interest'] = round($data['beg_balance'], 2) * (round($data['interest_rate'], 2) / 12); // Interest
            $data['total_payment'] = $data['sch_payment'] + $data['ext_payment'];
            $data['principal'] = $data['total_payment'] - $data['interest'];
            $data['end_balance'] = $data['beg_balance'] - $data['principal'];
            $data['cum_interest'] = $last_record->cum_interest + $data['interest'];

            if ($data['end_balance'] > 50) {
                HomeLoan::create([
                    "user_id" => Auth::user()->id,
                    "beg_balance" => $data['beg_balance'],
                    "pay_date" => $date,
                    "sch_payment" => $data['sch_payment'],
                    "ext_payment" => $data['ext_payment'],
                    "tot_payment" => $data['total_payment'],
                    "principal" => $data['principal'],
                    "interest" => $data['interest'],
                    "pmt_no" => $data['pmt_no'],
                    "end_balance" => $data['end_balance'],
                    "cum_interest" => $data['cum_interest'],
                ]);

                $stop = 0;
            } else {
                // dd($data['beg_balance'], $data['principal']);
                $stop = 1;
            }
        } while ($stop == 0);

    }


    public function RecalculateSavings($data)
    {

        $last_record = DB::table('home_loans_savings')->select('end_balance', 'principal', 'interest')->orderBy('id', 'DESC')->first();

        $stop = null;
        do {
            $last_record = DB::table('home_loans_savings')->select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest')->orderBy('id', 'DESC')->first();
            // dd($last_record, $data);
            $data['beg_balance'] = $last_record->end_balance;

            $daystosum = 1;
            $date = date('Y-m-d', strtotime($last_record->pay_date . ' + ' . $daystosum . ' months')); // add days to d-m-Y format

            $data['pmt_no'] = $last_record->pmt_no + 1;

            $data['interest'] = round($data['beg_balance'], 2) * (round($data['interest_rate'], 2) / 12); // Interest
            $data['total_payment'] = $data['sch_payment'] + $data['ext_payment'];
            $data['principal'] = $data['total_payment'] - $data['interest'];
            $data['end_balance'] = $data['beg_balance'] - $data['principal'];
            $data['cum_interest'] = $last_record->cum_interest + $data['interest'];

            if ($data['end_balance'] > 50) {
                DB::table('home_loans_savings')->insert([
                    "user_id" => Auth::user()->id,
                    "beg_balance" => $data['beg_balance'],
                    "pay_date" => $date,
                    "sch_payment" => $data['sch_payment'],
                    "ext_payment" => $data['ext_payment'],
                    "tot_payment" => $data['total_payment'],
                    "principal" => $data['principal'],
                    "interest" => $data['interest'],
                    "pmt_no" => $data['pmt_no'],
                    "end_balance" => $data['end_balance'],
                    "cum_interest" => $data['cum_interest'],
                ]);

                $stop = 0;
            } else {
                $stop = 1;
            }
        } while ($stop == 0);
    }


    public function calculate($data)
    {
        $stop = null;
        do {

            $last_record = HomeLoan::select('end_balance', 'principal', 'interest')->orderBy('id', 'DESC')->first();

            if ($last_record != null) {

                do {

                    $last_record = HomeLoan::select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest')->orderBy('id', 'DESC')->first();
                    $data['beg_balance'] = $last_record->end_balance;

                    $daystosum = 1;
                    $date = date('Y-m-d', strtotime($last_record->pay_date . ' + ' . $daystosum . ' months')); // add days to d-m-Y format

                    $data['pmt_no'] = $last_record->pmt_no + 1;

                    $data['interest'] = round($data['beg_balance'], 2) * (round($data['interest_rate'], 2) / 12); // Interest
                    $data['total_payment'] = $data['sch_payment'] + $data['ext_payment'];
                    $data['principal'] = $data['total_payment'] - $data['interest'];
                    $data['end_balance'] = $data['beg_balance'] - $data['principal'];
                    $data['cum_interest'] = $last_record->cum_interest + $data['interest'];

                    if ($data['end_balance'] > 50) {
                        HomeLoan::create([
                            "user_id" => Auth::user()->id,
                            "beg_balance" => $data['beg_balance'],
                            "pay_date" => $date,
                            "sch_payment" => $data['sch_payment'],
                            "ext_payment" => $data['ext_payment'],
                            "tot_payment" => $data['total_payment'],
                            "principal" => $data['principal'],
                            "interest" => $data['interest'],
                            "pmt_no" => $data['pmt_no'],
                            "end_balance" => $data['end_balance'],
                            "cum_interest" => $data['cum_interest'],
                        ]);

                        MonthlyNetworth::create([
                            "user_id" => Auth::user()->id,
                            "date" => $date
                        ]);

                        $stop = 0;
                    } else {
                        $stop = 1;
                    }
                } while ($stop == 0);

            } else {

                $data['beg_balance'] = $data['loan_amount'];

                $data['interest'] = round($data['beg_balance'], 2) * (round($data['interest_rate'], 2) / 12); // Interest
                $data['total_payment'] = $data['sch_payment'] + $data['ext_payment'];
                $data['principal'] = $data['total_payment'] - $data['interest'];
                $data['end_balance'] = $data['beg_balance'] - $data['principal'];

                HomeLoan::create([
                    "user_id" => Auth::user()->id,
                    "beg_balance" => $data['beg_balance'],
                    "pay_date" => $data['date'],
                    "sch_payment" => $data['sch_payment'],
                    "ext_payment" => $data['ext_payment'],
                    "tot_payment" => $data['total_payment'],
                    "principal" => $data['principal'],
                    "interest" => $data['interest'],
                    "pmt_no" => 1,
                    "end_balance" => $data['end_balance'],
                    "cum_interest" => $data['interest'],
                ]);

                MonthlyNetworth::create([
                    "user_id" => Auth::user()->id,
                    "date" => $data['date']
                ]);
            }
        } while ($stop == 0);

        $stop = null;
        do {
            $last_record = DB::table('home_loans_savings')->select('end_balance', 'principal', 'interest')->orderBy('id', 'DESC')->first();

            if ($last_record != null) {

                do {

                    $last_record = DB::table('home_loans_savings')->select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest', 'cum_interest')->orderBy('id', 'DESC')->first();
                    $data['beg_balance'] = $last_record->end_balance;

                    $daystosum = 1;
                    $date = date('Y-m-d', strtotime($last_record->pay_date . ' + ' . $daystosum . ' months')); // add days to d-m-Y format

                    $data['pmt_no'] = $last_record->pmt_no + 1;

                    $data['interest'] = round($data['beg_balance'], 2) * (round($data['interest_rate'], 2) / 12); // Interest
                    $data['total_payment'] = $data['sch_payment'];
                    $data['principal'] = $data['total_payment'] - $data['interest'];
                    $data['end_balance'] = $data['beg_balance'] - $data['principal'];
                    $data['cum_interest'] = $last_record->cum_interest + $data['interest'];

                    if ($data['end_balance'] > 50) {

                        DB::table('home_loans_savings')->insert([
                            "user_id" => Auth::user()->id,
                            "beg_balance" => $data['beg_balance'],
                            "pay_date" => $date,
                            "sch_payment" => $data['sch_payment'],
                            "ext_payment" => 0,
                            "tot_payment" => $data['total_payment'],
                            "principal" => $data['principal'],
                            "interest" => $data['interest'],
                            "pmt_no" => $data['pmt_no'],
                            "end_balance" => $data['end_balance'],
                            "cum_interest" => $data['cum_interest'],
                        ]);

                        $stop = 0;
                    } else {
                        $stop = 1;
                    }
                } while ($stop == 0);
            } else {

                $data['beg_balance'] = $data['loan_amount'];

                $data['interest'] = round($data['beg_balance'], 2) * (round($data['interest_rate'], 2) / 12); // Interest
                $data['total_payment'] = $data['sch_payment'];
                $data['principal'] = $data['total_payment'] - $data['interest'];
                $data['end_balance'] = $data['beg_balance'] - $data['principal'];


                DB::table('home_loans_savings')->insert([
                    "user_id" => Auth::user()->id,
                    "beg_balance" => $data['beg_balance'],
                    "pay_date" => $data['date'],
                    "sch_payment" => $data['sch_payment'],
                    "ext_payment" => 0,
                    "tot_payment" => $data['total_payment'],
                    "principal" => $data['principal'],
                    "interest" => $data['interest'],
                    "pmt_no" => 1,
                    "end_balance" => $data['end_balance'],
                    "cum_interest" => $data['interest'],
                ]);
            }
        } while ($stop == 0);
    }

    public function Modifydata()
    {
        $record_date = HomeLoan::where('pay_date', $this->date)->first();
        if (!is_null($record_date)) {
            $data = $this->FormatVariable();
//             dd($data);
            $data = $this->scheduled_payment($data);
            $this->home_loan_data($data);

            $from = $record_date->pay_date;
            $to = HomeLoan::select('pay_date')->orderBy('id', 'DESC')->first();
            HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->delete();

//            DB::table('home_loans_savings')->whereBetween('pay_date', [$from, $to->pay_date])->delete();

            $last_record = HomeLoan::select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest')->orderBy('id', 'DESC')->first();

            if (isset($last_record['end_balance']))
            {
                $data['beg_balance'] = $last_record->end_balance;
//                dd($data);
                $this->Recalculate($data);
                $this->RecalculateSavings($data);
            } else
            {
                $this->calculate($data);
            }
        } else {
            throw ValidationException::withMessages(['date' => 'This value doesn\'t exits in the table']);
        }
    }

    public function close()
    {
        $this->is_open = false;
        $this->dispatchBrowserEvent('closeModalOfHomeLoan');
    }


    public function render()
    {
        return view('livewire.home-loan-update-modal');
    }
}
