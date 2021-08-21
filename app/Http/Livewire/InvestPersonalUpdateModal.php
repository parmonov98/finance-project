<?php

namespace App\Http\Livewire;

use App\Models\HomeLoan;
use App\Models\HomeLoanData;
use App\Models\InvestPersonal;
use App\Models\InvestPersonalData;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InvestPersonalUpdateModal extends Component
{

    protected $listeners = ['edit', 'saved' => '$refresh', 'tablesTruncated' => 'render', 'updated', 'close'];

    protected $rules = [
        "min" => 'required|numeric',
        'max' => 'required|numeric',
        'inflation' => 'required|numeric|min:0|not_in:0',
        'fees' => 'required|numeric',
        'date' => 'required|date',
        'monthlyInvest' => 'required|numeric|min:0|not_in:0',
        'monthlyFee' => 'required|numeric|min:0|not_in:0'
    ];

    protected $messages = [
        '*.required' => 'This :attribute field is required',
        '*.numeric' => 'This :attribute field must be a number',
        '*.date' => 'This :attribute field must a date',
        '*.min:0' => 'This :attribute field must be greated than 0',
        '*.not_in:0' => 'This :attribute field must be greated than 0'
    ];

    protected $validationAttributes = [
        'min' => 'min return on invest',
        'max' => 'max return on invest',
        'monthlyInvest' => 'monthly investment',
    ];

    public $is_open = false;
    public $min;
    public $max;
    public $inflation;
    public $fees;
    public $monthlyInvest;
    public $date;
    public $monthlyFee;

    public $total_invested = 0;

    public $invest_personal;

    public function edit(InvestPersonal $investPersonal)
    {
        $this->total_invested = $investPersonal->total_invested;

        $InvestPersonalData = InvestPersonalData::where('user_id', Auth::id())->first();
//        dd($InvestPersonalData);
        $this->min = $InvestPersonalData->min;
        $this->max = $InvestPersonalData->max;
        $this->inflation = $InvestPersonalData->inflation;
        $this->fees = $InvestPersonalData->fees;
        $this->monthlyInvest = $InvestPersonalData->monthly_invest;
        $this->monthlyFee = $investPersonal->monthly_account_fee;

        $this->invest_personal = $investPersonal;
        $this->date = $investPersonal->next()->date;
        $this->is_open = true;
    }


    public function save()
    {
        if ($this->invest_personal !== null) {
            $this->invest_personal->total_invested = $this->total_invested;
//            $this->invest_personal->total_interest = ( $this->invest_personal->return_on_invest / 12) * $this->total_invested;
            $this->invest_personal->save();
            $this->invest_personal->refresh();

            $this->InputData();

//            $this->emitUp('rerender');
            $this->close();
            $this->emitTo('invest-personals', 'rerender');

        }
    }

    public function close()
    {
        $this->is_open = false;
        $this->dispatchBrowserEvent('closeModalOfInvestPersonal');
    }

    public function render()
    {
        return view('livewire.invest-personal-update-modal');
    }


    public function InputData()
    {

        $data = $this->FormatVariable();
//
//        $check = InvestPersonal::select('total_invested')->orderBy('id', 'DESC')->first();
//
//        if(!$check)
//            $this->Calculate($data);
//        else
        $this->Recalculate($data);
    }

    public function FormatVariable()
    {
        $this->validate();
        $data['min'] = $this->min;
        $data['max'] =  $this->max;
        $data['inflation'] = $this->inflation; // inflation
        $data['fees'] = $this->fees; // fees percentages
        $data['monthlyInvest'] = $this->monthlyInvest; // monthly_invest
        $data['monthlyFee'] = $this->monthlyFee;
        $data['date'] = $this->date;

        return $data;
    }

    public function Recalculate($data)
    {

        $from = $data['date'];
//        dd($from);
        $to = HomeLoan::select('pay_date')->orderBy('id', 'DESC')->first();
        $dates = HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->orderBy('pay_date')->get();;
        $change = InvestPersonal::whereBetween('date', [$from, $to->pay_date])->get();

        $totalInvestSum = $this->total_invested;
        $interestSum = 0;
//        dd($this->invest_personal->total_interest);
//
//        $data['inflation'] = ($data['inflation'] / 100) / 12;
//        $data['fees'] = ($data['fees'] / 12);
        $investPersonalData = InvestPersonalData::where('user_id', Auth::id())->first();

        $index = 0;
        foreach($dates as $key => $date)
        {
//            $data['return_on_invest'] = $change[$key]->return_on_invest / 12;
////            dd($data['return_on_invest']);
//
//            $data['after_fees'] = ($data['monthlyInvest'] * ($data['fees'] /100)) + $data['monthlyFee'];
//
//            $data['interest'] = ($data['return_on_invest'] * $data['monthlyInvest']);
//
//            $interestSum += $data['interest'];
//
//            $totalInvestSum += $data['monthlyInvest'] + $interestSum - $data['after_fees'];
//            $data['total_invested'] = $totalInvestSum;

//            $data['monthlyInvest'] = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']));

            if ($change[$key]){
//                $change[$key]->monthly_invest = ($change[$key]->inflation / 100 / 12) * $change[$key]->prev()->monthly_invest + $change[$key]->prev()->monthly_invest;
                dd($change[$key]->return_on_invest / 12);
                $change[$key]->interest = ($change[$key]->return_on_invest / 12) * ($change[$key]->monthly_invest + $change[$key]->prev()->total_invested);
                $change[$key]->total_interest = $change[$key]->prev()->total_interest + $change[$key]->interest;
                $change[$key]->total_invested = ($change[$key]->monthly_invest + $change[$key]->interest + $change[$key]->after_fees) + $change[$key]->prev()->total_invested;
            }else{
                $change[$key]->monthly_invest = ($investPersonalData->inflation / 100 / 12) * $investPersonalData->monthly_invest + $investPersonalData->monthly_invest;
                $change[$key]->interest = ($change[$key]->return_on_invest / 12) * $change[$key]->total_invested;


            }

//            $change[$key]->fees = $data['fees'];
//            $change[$key]->interest = $data['interest'];
//            $change[$key]->after_fees = $data['after_fees'];
//            $change[$key]->total_invested = $totalInvestSum;

            $change[$key]->save();
            $change[$key]->refresh();
//            $change[$key]->interest = $data['interest'];
//            $change[$key]->total_interest = ( $this->invest_personal->return_on_invest / 12) * $change[$key]->total_invested;
//            $change[$key]->save();
//            $change[$key]->refresh();
        }

    }

    public function ResetTables()
    {
        InvestPersonal::truncate();
        InvestPersonalData::truncate();
    }

}
