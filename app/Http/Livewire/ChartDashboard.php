<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use App\Models\HomeLoan;
use App\Models\MonthlyNetworth;
use App\Models\InvestPersonal;
use App\Models\LongTermInvestment;
use App\Models\ProgramSuper;
use Illuminate\Support\Facades\DB;

class ChartDashboard extends Component
{
    public $firstRun = true;

    public $showDataLabels = false;

    public $from_year = "2021";
    public $to_year = "2022";

    public $years = [];
    public $year = "2021";

    public function mount()
    {
        $result = MonthlyNetworth::select(DB::raw('YEAR(date) as year'))->distinct()->get();
        $this->years = $result->pluck('year');

    }

    public function updatedYear($selected_year)
    {
        // dd($selected_year);
        $this->year = $selected_year;
        if (is_numeric($selected_year)) {
            $this->from_year = $selected_year;
            $this->to_year = $selected_year + 1;

            $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
            $yearText = "['" . implode("','", $months) . "']";

            $total_debtsRecords = HomeLoan::query()
                ->select("*", DB::raw("MONTH(pay_date) as month"), DB::raw("MONTHNAME(pay_date) as monthname"))
                ->where(DB::raw('YEAR(pay_date)'), [$this->from_year])
                ->get();
            // ->pluck('home_value');

//            dd($total_debtsRecords);
            $_debtsLabel = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $total_debts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $total_debtsRecords->each(function($item) use (&$total_debts){

                if(!isset($total_debts[$item->month-1])){
                    $total_debts[$item->month-1] = $item->end_balance;
                }else{
                    $total_debts[$item->month-1] = $item->end_balance;
                }
            });
            $total_debtsText = "['" . implode("','", $total_debts) . "']";
//            dd($total_debtsText);

            $data['total_debts'] = $total_debts;
            $data['months'] = $months;
//            $data['total_assets'] = $assets;
//            $data['differences'] = $differences;
//            $data['differences_vs_super'] = $differences_vs_super;
//            $data['runningDiff_minus_cash_plus_equity'] = $runningDiff;
//            $data['differences_minus_overall'] = $overallDiff;


            $this->dispatchBrowserEvent('updatedChart', $data);
            $this->render();
        }
    }

    public function render()
    {

//        dd($this->from_year, $this->to_year);
        // dd(1);
        // $expenses = Expense::whereIn('type', $this->types)->get();
        // $home_loans = HomeLoan::select('id', 'pay_date', 'end_balance')->whereBetween('pay_date', [$from, $to])->get();
        // $home_loans = HomeLoan::select('id', 'pay_date', 'end_balance', 'beg_balance')->get();
//        $home_loans = HomeLoan::all();


        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $yearText = "['" . implode("','", $months) . "']";

        // TOTAL DEBTS
        $total_debtsRecords = HomeLoan::query()
                ->select("*", DB::raw("MONTH(pay_date) as month"), DB::raw("MONTHNAME(pay_date) as monthname"))
                ->where(DB::raw('YEAR(pay_date)'), [$this->from_year])
                ->get();
                // ->pluck('home_value');

//        dd($total_debtsRecords);

        $_debtsLabel = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $total_debts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $total_debtsRecords->each(function($item) use (&$total_debts){

            if(!isset($total_debts[$item->month-1])){
                $total_debts[$item->month-1] = $item->end_balance;
            }else{
                $total_debts[$item->month-1] = $item->end_balance;
            }
        });
        $total_debtsText = "['" . implode("','", $total_debts) . "']";
        // TOTAL DEBTS

        // SUPER
        $total_debtsRecords = HomeLoan::query()
            ->select("*", DB::raw("MONTH(pay_date) as month"), DB::raw("MONTHNAME(pay_date) as monthname"))
            ->where(DB::raw('YEAR(pay_date)'), [$this->from_year])
            ->get();
        // ->pluck('home_value');

        $_debtsLabel = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $total_debts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $total_debtsRecords->each(function($item) use (&$total_debts){

            if(!isset($total_debts[$item->month-1])){
                $total_debts[$item->month-1] = $item->beg_balance;
            }else{
                $total_debts[$item->month-1] = $item->beg_balance;
            }
        });
        $total_debtsText = "['" . implode("','", $total_debts) . "']";
        // SUPER


        // $years = MonthlyNetworth::select('date')->distinct('date')->get();
        return view('livewire.chart-dashboard')->with([
            "total_debts" => $total_debtsText,
            'yearText' => $yearText,
//            'total_assets' => $total_assets,
//            'differences' => $differencesText,
//            'differences_vs_super' => $differences_vs_superText,
//            'runningDiff_minus_cash_plus_equity' => $runningDiff_minus_cash_plus_equityText,
//            'runningDiff_minus_overall' => $runningDiff_minus_overallText,
        ]);
    }
}
