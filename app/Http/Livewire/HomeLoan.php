<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HomeLoan extends Component
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
        'date' => 'required|numeric',
        'int_rate' => 'required|numeric' ,
        'nb_pay' => 'required|numeric' , 
        'ext_pay' => 'numeric'
    ];

    protected $messages = [ 
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number'
    ];

    public function render()
    {
        return view('livewire.home-loan');
    }

    public function calculate()
    {
        $this->validate();

        $interest_rate =  $this->int_rate/100;
        $nb_payments = $this->nb_pay; //months
        $period = $this->period; // years
        $loan_amount = $this->loan;
        
        $test = $this->pmt($interest_rate, $loan_amount, $nb_payments, $period);

        dd($test);
        
    }

    public function resetIputs()
    {
        $this->reset(['loan', 'int_rate', 'period', 'nb_pay', 'date', 'ext_pay']);
    }

    public function pmt($interest_rate, $loan_amount, $nb_payments, $period )
    {

        $up = $interest_rate*$loan_amount;
        $pow = pow(1+($interest_rate/$nb_payments), -$nb_payments*$period );

        $sch_payment = $up / ($nb_payments*(1-$pow));

        return $sch_payment;
    }
}
