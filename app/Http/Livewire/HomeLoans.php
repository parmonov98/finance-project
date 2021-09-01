<?php

namespace App\Http\Livewire;

use App\Models\Program5YRNetworth;
use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\HomeLoanData;
use App\Models\MonthlyNetworth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HomeLoans extends Component
{
    public $loan;
    public $int_rate;
    public $period;
    public $nb_pay;
    public $date;
    public $ext_pay;
    public $change;

    public $end_balance = 0;

    protected $listeners = ['tablesTruncated' => 'render', 'saved', 'rerender' => '$refresh'];

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

    public function openUpdateHomeLoanModal(HomeLoan $homeLoan)
    {
        $this->emitTo('home-loan-update-modal', 'edit', $homeLoan);
    }

    public function saved(HomeLoan $home_loan, $start_date)
    {
        $db_data = HomeLoanData::get()->first();
        $this->loan = $home_loan->end_balance;
        $paidRecords = HomeLoan::where('pay_date', '<', $home_loan->pay_date)->where('user_id', auth()->id())->get();

        $this->date = $start_date;
        $this->int_rate = $db_data->int_rate * 100;
        $this->period = round(round($db_data->loan_period * 12 - $paidRecords->count(), 1) / 12, 1);

        $this->ext_pay = $db_data->opt_payment;
        $this->nb_pay = $db_data->no_payments;
        $this->emitTo('home-loan-update-modal', 'close');
        $this->Modifydata();

        $this->render();
    }

    public function render()
    {
        $datas = HomeLoan::orderBy('pay_date', 'ASC')->get();
        $cum_interest = HomeLoan::select('cum_interest')->orderBy('id', 'DESC')->first();
        $additional_data = HomeLoan::select('sch_payment')->first();
        $sch_no_pay = HomeLoan::select('pmt_no')->orderBy('id', 'DESC')->first();
        $savings_no_pay = DB::table('home_loans_savings')->select('pmt_no')->orderBy('id', 'DESC')->first();
        $actual_no_pay = null;
        if(!is_null($sch_no_pay) && !is_null($savings_no_pay))
            $actual_no_pay = $savings_no_pay->pmt_no - $sch_no_pay->pmt_no;

//        dd($actual_no_pay);

        $start_date = HomeLoan::select('pay_date')->first();

        $from = date($start_date ? $start_date->pay_date : null);
        $to = today()->format('Y-m-d');

        $total_early_pay = HomeLoan::select('tot_payment')->whereBetween('pay_date', [$from, $to])->sum('tot_payment');


        $cum_interest_ext = DB::table('home_loans_savings')->select('cum_interest')->orderBy('id', 'DESC')->first();

        if (!is_null($cum_interest_ext) && !is_null($cum_interest))
            $savings = $cum_interest_ext->cum_interest  -  $cum_interest->cum_interest;
        else
            $savings = null;

        return view('livewire.home-loan', [
            "datas" => $datas,
            "cum_interest" => $cum_interest ? $cum_interest->cum_interest : 'No Data',
            "sch_payment" => $additional_data ? $additional_data->sch_payment : "No Data",
            "check" => $savings,
            "sch_no_pay" => $sch_no_pay ? $sch_no_pay->pmt_no : "No Data",
            "actual_no_pay" => $actual_no_pay ? $actual_no_pay : "No Data",
            "savings" => $savings ? $savings : "No Data",
            "total_early_pay" => $total_early_pay ? $total_early_pay : "No Data",
        ]);
    }


    public function FormatVariable()
    {

        $this->validate();
        // $data['date'] = date('d-m-Y', strtotime($this->date)); // convert to d-m-Y format;
        $data['date'] = $this->date;
        $data['interest_rate'] =  $this->int_rate / 100;
        $data['nb_payments'] = $this->nb_pay; //months
        $data['loan_period'] = $this->period; // years
        $data['loan_amount'] = $this->loan; // loan amount
        $data['ext_payment'] = $this->ext_pay;

        return $data;
    }

    public function scheduled_payment($data)
    {
        $up = $data['interest_rate'] * $data['loan_amount'];
        $pow = pow(1 + ($data['interest_rate'] / $data['nb_payments']), -$data['nb_payments'] * $data['loan_period']);
        $data['sch_payment'] = $up / ($data['nb_payments'] * (1 - $pow));

        return $data;
    }

    public function Inputdata()
    {
        $data = $this->FormatVariable();
        // dd($data);
        $data = $this->scheduled_payment($data);
        // dd($data);
        $this->home_loan_data($data);
        $this->calculate($data);
    }


    public function home_loan_data($data)
    {
        $db_data = HomeLoanData::where('user_id', Auth::id())->first();

        // dd($data);

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

    public function calculate($data)
    {
        $stop = null;
        do {

            $last_record = HomeLoan::select('end_balance', 'principal', 'interest')->orderBy('id', 'DESC')->first();

            if ($last_record != null) {
                do {
                    $last_record = HomeLoan::select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest')
                                            ->where('user_id', Auth::id())->orderBy('id', 'DESC')->first();
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
            $last_record = DB::table('home_loans_savings')->select('end_balance', 'principal', 'interest')
                                ->orderBy('id', 'DESC')->first();

            if ($last_record != null) {

                do {
                    $last_record = DB::table('home_loans_savings')
                        ->select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest', 'cum_interest')
                        ->where('user_id', Auth::id())->orderBy('id', 'DESC')->first();

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

    public function ResetTables()
    {
        $this->reset(['loan', 'int_rate', 'period', 'nb_pay', 'date', 'ext_pay', 'date']);
        HomeLoan::where('user_id', Auth::id())->delete();
        HomeLoanData::where('user_id', Auth::id())->delete();
        MonthlyNetworth::where('user_id', Auth::id())->delete();
        Program5YRNetworth::where('user_id', Auth::id())->delete();
        DB::table('home_loans_savings')->where('user_id', Auth::id())->delete();
    }

    // this is run when recalculation form submitted
    public function Modifydata()
    {
        $record_date = HomeLoan::where('pay_date', $this->date)->first();
        if (!is_null($record_date)) {
            $data = $this->FormatVariable();
            $data = $this->scheduled_payment($data);
            $this->home_loan_data($data);

            $from = $record_date->pay_date;
            $to = HomeLoan::select('pay_date')->where('user_id', Auth::id())->orderBy('id', 'DESC')->first();

            // DB::table('home_loans_savings')->where('user_id', Auth::id())->whereBetween('pay_date', [$from, $to->pay_date])->delete();
            HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->where('user_id', Auth::id())->delete();
            // MonthlyNetworth::whereBetween('date', [$from, $to->pay_date])->where('user_id', Auth::id())->delete();

            $last_record = HomeLoan::select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest')->orderBy('id', 'DESC')->first();

            if ($last_record != null && isset($last_record['end_balance']))
            {
                $data['beg_balance'] = $last_record->end_balance;
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
}
