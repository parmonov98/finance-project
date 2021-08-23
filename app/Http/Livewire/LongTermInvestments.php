<?php

namespace App\Http\Livewire;

use App\Models\ProgramSuper;
use Livewire\Component;
use App\Models\HomeLoan;
use App\Models\InvestPersonal;
use App\Models\InvestPersonalData;
use App\Models\LongTermInvestment;
use Illuminate\Support\Facades\Auth;
use App\Models\LongTermInvestmentsData;

class LongTermInvestments extends Component
{
    protected $listeners = ['tablesTruncated' => 'render', 'saved', 'rerender' => '$refresh'];

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

    public function openUpdateLongTermInvestmentModal(LongTermInvestment $longTermInvestment)
    {
        $this->emitTo('long-term-investment-update-modal', 'edit', $longTermInvestment);
    }

    public function render()
    {
        $datas = LongTermInvestment::orderBy('date')->get();

        return view('livewire.long-term-investments', [
            "datas" => $datas,
        ]);
    }

    public function InputData()
    {
        $data = $this->FormatVariable();
        $this->InvestPersonalData($data);

        $check = LongTermInvestment::select('total_invested')->orderBy('id', 'DESC')->first();

        if (!$check)
            $this->Calculate($data);
        else
            $this->Recalculate($data);
    }

    public function FormatVariable()
    {
        $this->validate();
        $data['min'] = $this->min;
        $data['max'] = $this->max;
        $data['inflation'] = $this->inflation; // inflation
        $data['fees'] = $this->fees; // fees percentages
        $data['monthlyInvest'] = $this->monthlyInvest; // monthly_invest
        $data['monthlyFee'] = $this->monthlyFee;
        $data['date'] = $this->date;

        return $data;
    }

    public function InvestPersonalData($data)
    {
//        dd($data);
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

    public function Calculate($data)
    {
        $dates = HomeLoan::select('pay_date')->orderBy('pay_date')->get();
        if($dates->count() == 0){
            session()->flash('message', 'You have to add Home Loan data!');
            return $this->redirect(route('homeloan.show'));
        }

        $totalInvestSum = 0;
        $totalInterest = 0;

        $data['inflation'] = ($data['inflation'] / 100) / 12;

        $prevMonthlyInvest = $data['monthlyInvest'];
        foreach($dates as $index => $date)
        {
            $randomReturnPercent = rand($data['min'], $data['max']);
            if ($index === 0){
                $data['monthlyInvest'] = ($data['monthlyInvest'] * $data['inflation']) + $data['monthlyInvest'];
                $prevMonthlyInvest = $data['monthlyInvest'];
                $data['after_fees'] = ((($data['fees'] / 100) / 12 * $data['monthlyInvest']) + $data['monthlyFee']);
                $data['return_on_invest'] = ($randomReturnPercent / 100) / 12;
                $data['interest'] = ($data['return_on_invest'] * $data['monthlyInvest']);
                $totalInterest += $data['interest'];
                $totalInvestSum += $data['monthlyInvest'] +  $data['interest'] - $data['after_fees'];
            }

            if ($index > 0){
                $data['monthlyInvest'] = ($prevMonthlyInvest * $data['inflation']) + $prevMonthlyInvest;
                $prevMonthlyInvest = $data['monthlyInvest'];
                $data['after_fees'] = ((($data['fees'] / 100) / 12 * $data['monthlyInvest']) + $data['monthlyFee']);
                $data['return_on_invest'] = ($randomReturnPercent / 100) / 12;
                $data['interest'] = ($data['return_on_invest'] * ($data['monthlyInvest'] + $totalInvestSum));
                $totalInterest += $data['interest'];
                $totalInvestSum += $data['monthlyInvest'] +  $data['interest'] - $data['after_fees'];
            }

            LongTermInvestment::create([
                "user_id" => Auth::user()->id,
                "fees" => $data['fees'],
                "monthly_account_fee" => $data['monthlyFee'],
                "inflation" => $data['inflation'] * 100 * 12,
                "monthly_invest" => $data['monthlyInvest'],
                "interest" => $data['interest'],
                "total_interest" => $totalInterest,
                "after_fees" => $data['after_fees'],
                "total_invested" => $totalInvestSum,
                "date" => $date->pay_date,
                "return_on_invest" => $randomReturnPercent
            ]);
        }
    }

    public function Recalculate($data)
    {

        $from = $data['date'];
        $to = HomeLoan::select('pay_date')->orderBy('id', 'DESC')->first();
        $dates = HomeLoan::whereBetween('pay_date', [$from, $to->pay_date])->orderBy('pay_date')->get();;
        $change = LongTermInvestment::whereBetween('date', [$from, $to->pay_date])->get();

        $totalInvestedSum = 0;
        $interestSum = 0;
        foreach ($dates as $key => $date) {

            $monthlyInvest = $data['monthlyInvest'] + (($data['monthlyInvest'] * $data['inflation']) / 12);
            $return_on_invest = rand($data['min'], $data['max']) / 100;
            $interestSum += ($return_on_invest * $data['monthlyInvest']) / 12;
            $interest = $interestSum;

            $after_fees = (($data['fees'] * $monthlyInvest) / 12) + $data['monthlyFee'];

            $totalInvestedSum += $monthlyInvest + $interest - $after_fees;

            $change[$key]->fees = $data['fees'];
            $change[$key]->monthly_account_fee = $data['monthlyFee'];
            $change[$key]->inflation = $data['inflation'];
            $change[$key]->monthly_invest = $monthlyInvest;
            $change[$key]->interest = $interest;
            $change[$key]->after_fees = $after_fees;
            $change[$key]->total_invested = $totalInvestedSum;
            $change[$key]->date = $date->pay_date;
            $change[$key]->return_on_invest = $return_on_invest;
            $change[$key]->save();
        }

    }

    public function ResetTables()
    {
        LongTermInvestment::truncate();
        LongTermInvestmentsData::truncate();
    }


}
