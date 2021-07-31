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
            $this->to_year = $selected_year + 1;
            $this->from_year = $selected_year;

            $dates = DB::table('monthly_networths')
            ->select("*", DB::raw("MONTH(date) as month"))
            ->distinct()
            ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])
            ->orderBy(DB::raw("MONTH(date)"))
            ->groupBy(DB::raw("MONTH(date)"))
            ->pluck('date');
            $data['years'] = $dates->toArray();

            // SELECT DISTINCT *, MONTHNAME(date) as monthname, MONTH(date) as month from monthly_networths WHERE year(date) = '2021' GROUP BY MONTHNAME(date) ORDER BY MONTH(date);
            $total_debtsRecords = MonthlyNetworth::query()
                ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
                ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])
                ->get();
                // ->pluck('home_value');

            // dd($total_debtsRecords);
            $total_debts = [];
            $total_debtsRecords->each(function($item) use (&$total_debts){
                // dd($item->home_value);
                if(!isset($total_debts[$item->month])){
                    $total_debts[$item->month] = $item->home_value;
                }else{
                    $total_debts[$item->month] += $item->home_value;
                }
            });

            $dates = MonthlyNetworth::select('date')
                            ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])
                            ->get();


            // ASSETS
            $home_values = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
            $cashs = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
            $investPersonals = InvestPersonal::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
            $longTermInvests = LongTermInvestment::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
            $investSupers = ProgramSuper::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
            $other_invests = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();

            $dates = MonthlyNetworth::select('date')->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
            $value1 = null; $value2 = null; $value3 = null; $value4 = null; $value5 = null; $value6 = null;


            $value1 = null; $value2 = null; $value3 = null; $value4 = null; $value5 = null; $value6 = null;

            foreach ($dates as $date) {
                $home_value = MonthlyNetworth::select('home_value')->Where('date', $date->date)->first();
                $cash = MonthlyNetworth::select('cash')->Where('date', $date->date)->first();
                $investPersonal = InvestPersonal::select('total_invested')->Where('date', $date->date)->first();
                $longTermInvest = LongTermInvestment::select('total_invested')->Where('date', $date->date)->first();
                $investSuper = ProgramSuper::select('total_invested')->Where('date', $date->date)->first();
                $other_invest = MonthlyNetworth::select('other_invest')->Where('date', $date->date)->first();


                $value1 += $home_value->home_value ? $home_value->home_value : 0;
                $value2 += $cash->cash ? $cash->cash : 0;
                $value3 += $investPersonal ? $investPersonal->total_invested : 0;
                $value4 += $longTermInvest ? $longTermInvest->total_invested : 0;
                $value5 += $investSuper ? $investSuper->total_invested : 0;
                $value6 += $other_invest->other_invest ? $other_invest->other_invest : 0;

                $assets[] =  $value1 + $value2 + $value3 + $value4 + $value5 + $value6;
            }

             // DIFFERENCE
            foreach ($home_values as $key => $record)
                $differences[] = $assets[$key] - $record->home_value;
            // End DIFFERENCE


            // DIFFERENCE SUPER
            foreach ($investSupers as $key => $record)
                $differences_vs_super[] = $differences[$key] - $record->total_invested;

            if (isset($differences_vs_super)) {
                // RUNNING DIFF
                foreach ($differences_vs_super as $key => $record) {
                    if (!$key == 0)
                        $runningDiff[] = $differences_vs_super[$key] - $differences_vs_super[$key - 1];
                    else
                        $runningDiff[] = $differences_vs_super[$key];
                }
                // End RUNNING DIFF

                foreach ($differences as $key => $record) {
                    if ($key != 0)
                        $overallDiff[] = $differences[$key] - $differences[$key - 1];
                    else
                        $overallDiff[] = $differences[$key];
                }
            }

            $data['total_debts'] = $total_debts;
            $data['total_assets'] = $assets;
            $data['differences'] = $differences;
            $data['differences_vs_super'] = $differences_vs_super;
            $data['runningDiff_minus_cash_plus_equity'] = $runningDiff;
            $data['differences_minus_overall'] = $overallDiff;


            $this->dispatchBrowserEvent('updatedChart', $data);
            $this->render();
        }
    }

    public function render()
    {

        // dd(1);
        // $expenses = Expense::whereIn('type', $this->types)->get();
        // $home_loans = HomeLoan::select('id', 'pay_date', 'end_balance')->whereBetween('pay_date', [$from, $to])->get();
        // $home_loans = HomeLoan::select('id', 'pay_date', 'end_balance', 'beg_balance')->get();
        $home_loans = HomeLoan::all();

        $dates = DB::table('monthly_networths')
        ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
        ->distinct()
        ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])
        ->orderBy(DB::raw("MONTH(date)"))
        ->groupBy(DB::raw("MONTH(date)"))
        ->pluck('monthname');
        // ddd($dates);

        $yearText = "['" . implode("','", $dates->toArray()) . "']";

        $total_debtsRecords = MonthlyNetworth::query()
                ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
                ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])
                ->get();
                // ->pluck('home_value');

        // dd($total_debtsRecords);
        $total_debts = [];
        $total_debtsRecords->each(function($item) use (&$total_debts){
            // dd($item->home_value);
            if(!isset($total_debts[$item->month])){
                $total_debts[$item->month] = $item->home_value;
            }else{
                $total_debts[$item->month] += $item->home_value;
            }
        });
        // dd($total_debts);

        $total_debts = "['" . implode("','", $total_debts) . "']";

        $total_assets = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->pluck('home_value');

        // ASSETS
        $home_values = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
        $cashs = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
        $investPersonals = InvestPersonal::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
        $longTermInvests = LongTermInvestment::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
        $investSupers = ProgramSuper::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
        $other_invests = MonthlyNetworth::whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();

        $dates = MonthlyNetworth::select('date')->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year])->get();
        $value1 = null; $value2 = null; $value3 = null; $value4 = null; $value5 = null; $value6 = null;

        foreach ($dates as $date) {
            $home_value = MonthlyNetworth::select('home_value')->Where('date', $date->date)->first();
            $cash = MonthlyNetworth::select('cash')->Where('date', $date->date)->first();
            $investPersonal = InvestPersonal::select('total_invested')->Where('date', $date->date)->first();
            $longTermInvest = LongTermInvestment::select('total_invested')->Where('date', $date->date)->first();
            $investSuper = ProgramSuper::select('total_invested')->Where('date', $date->date)->first();
            $other_invest = MonthlyNetworth::select('other_invest')->Where('date', $date->date)->first();


            $value1 += $home_value->home_value ? $home_value->home_value : 0;
            $value2 += $cash->cash ? $cash->cash : 0;
            $value3 += $investPersonal ? $investPersonal->total_invested : 0;
            $value4 += $longTermInvest ? $longTermInvest->total_invested : 0;
            $value5 += $investSuper ? $investSuper->total_invested : 0;
            $value6 += $other_invest->other_invest ? $other_invest->other_invest : 0;

            $assets[] =  $value1 + $value2 + $value3 + $value4 + $value5 + $value6;
        }
        $total_assets = "['" . implode("','", $assets) . "']";
        // End ASSETS


        // DIFFERENCE
        foreach ($home_values as $key => $record)
            $differences[] = $assets[$key] - $record->home_value;
        $differencesText = "['" . implode("','", $differences) . "']";
        // End DIFFERENCE




            // DIFFERENCE SUPER
        foreach ($investSupers as $key => $record)
            $differences_vs_super[] = $differences[$key] - $record->total_invested;

        // dd($differences_vs_super);
        $differences_vs_superText = "['" . implode("','", $differences_vs_super) . "']";

        // End DIFFERENCE SUPER

        if (isset($differences_vs_super)) {
            // RUNNING DIFF
            foreach ($differences_vs_super as $key => $record) {
                if (!$key == 0)
                    $runningDiff[] = $differences_vs_super[$key] - $differences_vs_super[$key - 1];
                else
                    $runningDiff[] = $differences_vs_super[$key];
            }
            // End RUNNING DIFF

            foreach ($differences as $key => $record) {
                if ($key != 0)
                    $overallDiff[] = $differences[$key] - $differences[$key - 1];
                else
                    $overallDiff[] = $differences[$key];
            }
        }

        $runningDiff_minus_cash_plus_equityText = "['" . implode("','", $runningDiff) . "']";
        $runningDiff_minus_overallText = "['" . implode("','", $overallDiff) . "']";

        // dd($years);
        // $years = MonthlyNetworth::select('date')->distinct('date')->get();
        return view('livewire.chart-dashboard')->with([
            "total_debts" => $total_debts,
            'yearText' => $yearText,
            'total_assets' => $total_assets,
            'differences' => $differencesText,
            'differences_vs_super' => $differences_vs_superText,
            'runningDiff_minus_cash_plus_equity' => $runningDiff_minus_cash_plus_equityText,
            'runningDiff_minus_overall' => $runningDiff_minus_overallText,
        ]);
    }
}
