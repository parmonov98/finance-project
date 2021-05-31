<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\HomeLoanData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeLoans extends Component
{
    public $loan;
    public $int_rate;
    public $period;
    public $nb_pay;
    public $date;
    public $ext_pay;
    public $change;

    protected $listeners = ['tablesTruncated' => 'render'];

    protected $rules = [
        'loan' => 'required|numeric',
        'period' => 'required|numeric',
        'date' => 'required|date',
        'int_rate' => 'required|numeric',
        'nb_pay' => 'required|numeric',
        'ext_pay' => 'required|numeric'
    ];

    protected $messages = [
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must be a date'
    ];

    public function render()
    {
        $datas = HomeLoan::all();
        $cum_interest = HomeLoan::select('cum_interest')->orderBy('id', 'DESC')->first();
        $additional_data = HomeLoan::select('sch_payment')->first();
        $sch_no_pay = HomeLoan::select('pmt_no')->orderBy('id', 'DESC')->first();
        $start_date = HomeLoan::select( 'pay_date')->first();

        $from = date( $start_date ? $start_date->pmt_date : null );
        $to = today()->format('Y-m-d');
        $actual_no_pay = HomeLoan::whereBetween('pay_date', [$from, $to])->get()->count();

        $total_early_pay = HomeLoan::select('tot_payment')->whereBetween('pay_date', [$from, $to])->sum('tot_payment');

        $cum_interest_ext = DB::table('home_loans_savings')->select('cum_interest')->orderBy('id', 'DESC')->first();
        if(!is_null($cum_interest_ext) && !is_null($cum_interest))
            $savings = $cum_interest_ext->cum_interest  -  $cum_interest->cum_interest;
        else
            $savings = null;

        return view('livewire.home-loan', [
            "datas" => $datas,
            "cum_interest" => $cum_interest ? $cum_interest->cum_interest : 'No Data' ,
            "sch_payment" => $additional_data ? $additional_data->sch_payment : "No Data",
            "sch_no_pay" => $sch_no_pay ? $sch_no_pay->pmt_no : "No Data",
            "actual_no_pay" => $actual_no_pay ? $actual_no_pay : "No Data",
            "savings" => $savings ? $savings : "No Data",
            "total_early_pay" => $total_early_pay ? $total_early_pay : "No Data",
        ]);
    }

    public function submit()
    {
        $data = $this->formatVariables();

        // $data = $this->scheduled_payment($data);
        // $this->home_loan_data($data);
        if(is_null($this->change))
        {   
            $data = $this->scheduled_payment($data);
            $this->home_loan_data($data);
            $this->calculate($data);
        }
        else
        {
            $data = $this->scheduled_payment($data);
            $this->home_loan_data($data);
            $this->reCalculate($data);
        }
    }

    public function formatVariables()
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
                "user_id" => Auth::user()->id
            ]);
        } else if ($db_data) {

            if($data['date'] != $db_data->start_date){
                $db_data->start_date = $data['date'];
                $db_data->save();
            }

            if($data['loan_amount'] != $db_data->loan_amount){
                HomeLoanData::truncate();
                HomeLoanData::create([
                    "loan_amount" => $data['loan_amount'],
                    "int_rate" => $data['interest_rate'],
                    "loan_period" => $data['loan_period'],
                    "no_payments" => $data['nb_payments'],
                    "start_date" => $data['date'],
                    "sch_payment" => $data['sch_payment'],
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
            }
        } while ($stop == 0);


        do {
            $last_record = DB::table('home_loans_savings')->select('end_balance', 'principal', 'interest')->orderBy('id', 'DESC')->first();

            if ($last_record != null) {

                do {

                    $last_record = DB::table('home_loans_savings')->select('beg_balance', 'end_balance', 'tot_payment', 'pay_date', 'pmt_no', 'cum_interest', 'cum_interest')->orderBy('id', 'DESC')->first();
                    $data['beg_balance'] = $last_record->end_balance;


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
        $this->reset(['loan', 'int_rate', 'period', 'nb_pay', 'date', 'ext_pay' ]);
        HomeLoan::truncate();
        HomeLoanData::truncate();
    }

    public function Recalculate()
    {
        $records = HomeLoan::select('pay_date')->get();
        foreach($records as $record)
        {
            if($record->pay_date == $this->date ){
                dd($record->pay_date . ' | ' . $this->date);
            }
        }

    } 


}
