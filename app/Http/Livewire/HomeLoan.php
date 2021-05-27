<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\HomeLoanData;
use Illuminate\Support\Facades\Auth;

class HomeLoans extends Component
{

    public $loan; 
    public $int_rate; 
    public $period; 
    public $nb_pay;
    public $date;
    public $ext_pay;

    protected $rules = [
        'loan' => 'required|numeric',
        'period' => 'required|numeric',
        'date' => 'required|date',
        'int_rate' => 'required|numeric' ,
        'nb_pay' => 'required|numeric' , 
        'ext_pay' => 'required|numeric'
    ];

    protected $messages = [ 
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must be a date' 
    ];

    public function render()
    {
        return view('livewire.home-loan');
    }

    public function formatVariables(){

        $this->validate();

        $data['date'] = date('d-m-Y', strtotime($this->date)); //start of payments;

        $data['date'] = $this->date;
        $data['interest_rate'] =  $this->int_rate/100;
        $data['nb_payments'] = $this->nb_pay; //months
        $data['loan_period'] = $this->period; // years
        $data['loan_amount'] = $this->loan; // loan amount
        $data['ext_payment'] = $this->ext_pay;

        return $data;
    }

    public function submit()
    {
        // $daystosum = 1;
        // $start_date = date('d-m-Y', strtotime($this->date.' + '.$daystosum.' days')); // convert to d-m-Y format
        $data = $this->scheduled_payment();
        $this->home_loan_data($data);
        $this->calculate($data);

    }

    public function scheduled_payment()
    {
        $data = $this->formatVariables();
        $up = $data['interest_rate']*$data['loan_amount'];
        $pow = pow(1+($data['interest_rate']/$data['nb_payments']), -$data['nb_payments']*$data['loan_period'] );
        $data['sch_payment'] = $up / ($data['nb_payments']*(1-$pow));
        
        return $data;    
    }

    public function home_loan_data($data)
    {
        $db_data = HomeLoanData::get();

        if(!count($db_data)){

            HomeLoanData::create([
                "loan_amount" => $data['loan_amount'],
                "int_rate" => $data['interest_rate'], 
                "loan_period" => $data['loan_period'], 
                "no_payments" => $data['nb_payments'], 
                "start_date" => $data['date'], 
                "sch_payment" => $data['sch_payment'],
                "user_id" => Auth::user()->id
            ]);
        }else if (count($db_data)){

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

    public function calculate($data)
    {

        $data['beg_balance'] = $data['loan_amount'];
        $data['interest'] = round($data['loan_amount'], 2)*(round($data['interest_rate'], 2)/12); // Interest
        $data['total_payment'] = $data['sch_payment']+$data['ext_payment'];
        $data['principal'] = $data['total_payment'] - $data['interest'];
        $data['end_balance'] = $data['loan_amount'] - $data['principal'];

        HomeLoan::create([
            "user_id" => Auth::user()->id,
            "beg_balance" => $data['loan_amount'],
            "pay_date" => now(),
            "sch_payment" => $data['sch_payment'],
            "ext_payment" => $data['ext_payment'],
            "tot_payment" => $data['total_payment'],
            "principal" => $data['principal'],
            "interest" => $data['interest'],
            "end_balance" => $data['end_balance']
        ]);


    }

}
