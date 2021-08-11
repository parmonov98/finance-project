<?php

namespace App\Http\Livewire;

use App\Models\HomeLoan;
use App\Models\ProgramSuper;
use App\Models\SuperData;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InvestmentSuperUpdateModal extends Component
{

    protected $listeners = ['edit', 'saved' => '$refresh', 'tablesTruncated' => 'render', 'updated', 'close'];

    public $min;
    public $max;
    public $inflation;
    public $fees;
    public $monthlyInvest;
    public $date;
    public $monthlyFee;

    public $is_open = false;

    protected $rules = [
        "min" => 'required|numeric',
        'max' => 'required|numeric',
        'inflation' => 'required',
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

    public $total_invested = 0;

    public $invest_super;

    public function edit(ProgramSuper $investSuper)
    {

        $this->total_invested = $investSuper->total_invested;
        $this->invest_super = $investSuper;

        $investSuperData = SuperData::all()->first();

        $this->investSuperData = $investSuperData;
        $this->min = $investSuperData->min;
        $this->max = $investSuperData->max;
        $this->inflation = $investSuperData->inflation * 100;
        $this->fees = $investSuperData->fees;
        $this->monthlyInvest = $investSuperData->monthly_invest;
        $this->date = $investSuper->next()->date;
        $this->monthlyFee = $investSuper->monthly_account_fee;

        $this->is_open = true;
    }

    public function save()
    {
        if ($this->invest_super !== null) {
            $this->invest_super->total_invested = $this->total_invested;
            $this->invest_super->save();
            $this->invest_super->refresh();

            $this->InputData();

            $this->emitTo('monthly-networths', 'rerender');
            $this->close();
            $this->emitTo('super', 'rerender');

        }
    }

    public function close()
    {
        $this->is_open = false;
        $this->dispatchBrowserEvent('closeModalOfInvestmentSuper');
    }
    public function InputData()
    {
        $data = $this->FormatVariable();
        $this->InvestPersonalData($data);

        $check = ProgramSuper::select('total_invested')->orderBy('id', 'DESC')->first();

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
        $data['inflation'] = $this->inflation / 100; // inflation
        $data['fees'] = $this->fees; // fees percentages
        $data['monthlyInvest'] = $this->monthlyInvest; // monthly_invest
        $data['monthlyFee'] = $this->monthlyFee;
        $data['date'] = $this->date;

        return $data;
    }

    public function InvestPersonalData($data)
    {
        $db_data = SuperData::get()->first();

        if (!$db_data) {

            SuperData::create([
                "min" => $data['min'],
                "max" => $data['max'],
                "monthly_invest" => $data['monthlyInvest'],
                "fees" => $data['fees'],
                "start_date" => $data['date'],
                "inflation" => $data['inflation'],
                "user_id" => Auth::user()->id
            ]);
        } else if ($db_data) {

            SuperData::truncate();
            SuperData::create([
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
        $to = HomeLoan::select('pay_date')->orderBy('id', 'DESC')->first();
        $dates = HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->orderBy('pay_date')->get();;
        $change = ProgramSuper::whereBetween('date', [$from, $to->pay_date])->get();

        $totalInvestSum = $this->total_invested;
        $interestSum = $this->invest_super->interest;
        foreach($dates as $key => $date)
        {
            $longTermInvestment = $change[$key];
            if ($longTermInvestment != null){
                $data['monthlyInvest'] = $data['monthlyInvest'] + $longTermInvestment->interest;
            }
            $data['inflation'] = $data['inflation'] / 100;

            $monthlyInvest = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']) / 12);
            $return_on_invest = rand($data['min'], $data['max']) / 100;
            $after_fees = (($data['fees'] * $monthlyInvest) / 12) + $data['monthlyFee'];

            $interestSum += ($return_on_invest * $data['monthlyInvest']) / 12;
            $interest = $interestSum;

            $totalInvestSum += $monthlyInvest + $interest - $after_fees;

            $change[$key]->fees = $data['fees'];
            $change[$key]->monthly_account_fee = $data['monthlyFee'];
            $change[$key]->inflation = $data['inflation'];
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
        ProgramSuper::truncate();
        SuperData::truncate();
    }

    public function render()
    {
        return view('livewire.investment-super-update-modal');
    }

}
