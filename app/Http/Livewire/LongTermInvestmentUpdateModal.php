<?php

namespace App\Http\Livewire;

use App\Models\HomeLoan;
use App\Models\InvestPersonalData;
use App\Models\LongTermInvestment;
use App\Models\LongTermInvestmentsData;
use App\Models\ProgramSuper;
use App\Models\SuperData;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LongTermInvestmentUpdateModal extends Component
{
    protected $listeners = ['edit', 'saved' => '$refresh', 'tablesTruncated' => 'render', 'updated', 'close'];

    public $min;
    public $max;
    public $inflation;
    public $fees;
    public $monthlyInvest;
    public $date;
    public $monthlyFee;

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

    public $total_invested = 0;


    public $longterm_investment;

    public function edit(LongTermInvestment $longTermInvestment)
    {
        $this->total_invested = $longTermInvestment->total_invested;
        $this->longterm_investment = $longTermInvestment;

        $longTermInvestmentData = LongTermInvestmentsData::all()->first();
//        dd($longTermInvestmentData);
        $this->invest_personal = $longTermInvestmentData;
        $this->min = $longTermInvestmentData->min;
        $this->max = $longTermInvestmentData->max;
        $this->inflation = $longTermInvestmentData->inflation;
        $this->fees = $longTermInvestmentData->fees;
        $this->monthlyInvest = $longTermInvestment->monthly_invest;
        $this->date = $longTermInvestment->next()->date;
        $this->monthlyFee = $longTermInvestment->monthly_account_fee;

        $this->is_open = true;

    }

    public function save()
    {
        if ($this->longterm_investment !== null) {
            $longTermInvestmentsData = LongTermInvestmentsData::firstWhere('user_id', Auth::id());
            $return_on_invest = rand($longTermInvestmentsData->min, $longTermInvestmentsData->max);
            $this->longterm_investment->total_invested = $this->total_invested;
            $this->longterm_investment->total_interest = ( $return_on_invest / 100 / 12) * $this->total_invested;

            $this->longterm_investment->save();
            $this->longterm_investment->refresh();

            $this->InputData();

            $this->emitUp('rerender');
            $this->close();
            $this->emitTo('long-term-investments', 'rerender');

        }
    }

    public function close()
    {
        $this->is_open = false;
        $this->dispatchBrowserEvent('closeModalOfLongTermInvestment');
    }

    public function InputData()
    {
        $data = $this->FormatVariable();
        $this->InvestPersonalData($data);

        $check = LongTermInvestment::select('total_invested')->orderBy('id', 'DESC')->first();

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
        $db_data = LongTermInvestmentsData::get()->first();

        if (!$db_data) {

            LongTermInvestmentsData::create([
                "min" => $data['min'],
                "max" => $data['max'],
                "monthly_invest" => $data['monthlyInvest'],
                "fees" => $data['fees'],
                "start_date" => $data['date'],
                "inflation" => $data['inflation'],
                "user_id" => Auth::user()->id
            ]);
        } else if ($db_data) {

            LongTermInvestmentsData::truncate();
            LongTermInvestmentsData::create([
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
        $change = LongTermInvestment::whereBetween('date', [$from, $to->pay_date])->get();

        $totalInvestSum = $this->total_invested;
        $interestSum = 0;

        $data['inflation'] = ($data['inflation'] / 100) / 12;
        $data['fees'] = ($data['fees'] / 12);

        $index = 0;
        foreach($dates as $key => $date)
        {
            $data['monthlyInvest'] = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']));

            $data['return_on_invest'] = rand($data['min'], $data['max']) / 100;

            $data['after_fees'] = ($data['monthlyInvest'] * ($data['fees'] /100)) + $data['monthlyFee'];

            $data['interest'] = ($data['return_on_invest'] * $data['monthlyInvest']) / 12;

            $interestSum += $data['interest'];


            $totalInvestSum += $data['monthlyInvest'] + $interestSum - $data['after_fees'];
            $data['total_invested'] = $totalInvestSum;

            $change[$key]->fees = $data['fees'];
            $change[$key]->monthly_account_fee = $data['monthlyFee'];
            $change[$key]->inflation = $data['inflation'] * 100 * 12 ;
            $change[$key]->monthly_invest = $data['monthlyInvest'];
            $change[$key]->interest = $data['interest'];
            $change[$key]->after_fees = $data['after_fees'];
            $change[$key]->total_invested = $totalInvestSum;
            $change[$key]->date = $date->pay_date;
            $change[$key]->return_on_invest = $data['return_on_invest'];
            $change[$key]->save();
        }

    }

    public function ResetTables()
    {
        LongTermInvestment::truncate();
        LongTermInvestmentsData::truncate();
    }
    public function render()
    {
        return view('livewire.long-term-investment-update-modal');
    }
}
