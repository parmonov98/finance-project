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
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must a date',
        '*.min:0' => 'This field must be greated than 0',
        '*.not_in:0' => 'This field must be greated than 0'
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
        $this->invest_personal = $investPersonal;

        $InvestPersonalData = InvestPersonalData::all()->first();
        $this->min = $InvestPersonalData->min;
        $this->max = $InvestPersonalData->max;
        $this->inflation = $InvestPersonalData->inflation;
        $this->fees = $InvestPersonalData->fees;
        $this->monthlyInvest = $InvestPersonalData->monthly_invest;
        $this->date = $investPersonal->next()->date;
        $this->monthlyFee = $investPersonal->monthly_account_fee;

        $this->is_open = true;
    }

    public function save()
    {
        if ($this->invest_personal !== null) {
            $this->invest_personal->total_invested = $this->total_invested;
            $this->invest_personal->save();
            $this->invest_personal->refresh();

            $this->InputData();

            $this->emitTo('monthly-networths', 'rerender');
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
        $this->InvestPersonalData($data);

        $check = InvestPersonal::select('total_invested')->orderBy('id', 'DESC')->first();

        if(!$check)
            $this->Calculate($data);
        else
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

    public function InvestPersonalData($data)
    {
        $db_data = InvestPersonalData::get()->first();

        if (!$db_data) {

            InvestPersonalData::create([
                "min" => $data['min'],
                "max" => $data['max'],
                "monthly_invest" => $data['monthlyInvest'],
                "fees" => $data['fees'],
                "start_date" => $data['date'],
                "inflation" => $data['inflation'],
                "user_id" => Auth::user()->id
            ]);
        } else if ($db_data) {
            InvestPersonalData::truncate();
            InvestPersonalData::create([
                "min" => $data['min'],
                "max" => $data['max'],
                "monthly_invest" => $data['monthlyInvest'],
                "fees" => $data['fees'],
                "start_date" => $data['date'],
                "inflation" => $data['inflation'],
                "user_id" => Auth::user()->id
            ]);
        }
    }

    public function Recalculate($data)
    {

        $from = $data['date'];
        $lastInvestPersonal = InvestPersonal::firstWhere('date', $from);
        $to = HomeLoan::select('pay_date')->orderBy('id', 'DESC')->first();
        $dates = HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->orderBy('pay_date')->get();;
        $change = InvestPersonal::whereBetween('date', [$from, $to->pay_date])->get();

        $totalInvestSum = $this->total_invested;
        $interestSum = $this->invest_personal->interest;

        foreach($dates as $key => $date)
        {
            $investPersonal = $change[$key];
            if ($investPersonal != null){
                $data['monthlyInvest'] = $data['monthlyInvest'] + $investPersonal->interest;
            }
            $monthlyInvest = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']) / 12);

            $return_on_invest = rand($data['min'], $data['max']) / 100;
            $after_fees = (($data['fees'] * $monthlyInvest) / 12) + $data['monthlyFee'];

            $interestSum += ($return_on_invest * $data['monthlyInvest']) / 12;
            $interest = $interestSum;
            $totalInvestSum += $monthlyInvest + $interest - $after_fees;
            $data['total_invested'] = $totalInvestSum;




            $change[$key]->fees = $data['fees'];
            $change[$key]->monthly_account_fee = $data['monthlyFee'];
            $change[$key]->inflation = $data['inflation'] ;
            $change[$key]->monthly_invest = $monthlyInvest;
            $change[$key]->interest = $interest;
            $change[$key]->after_fees = $after_fees;
            $change[$key]->total_invested = $totalInvestSum;
            $change[$key]->date = $date->pay_date;
            $change[$key]->return_on_invest = $return_on_invest;
            $change[$key]->save();
        }

    }

    public function ResetTables()
    {
        InvestPersonal::truncate();
        InvestPersonalData::truncate();
    }

}
