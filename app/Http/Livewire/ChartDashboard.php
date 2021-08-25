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
    public $year = 1;

    public function mount()
    {
        $result = MonthlyNetworth::select(DB::raw('YEAR(date) as year'))->distinct()->get();
        $this->years = [1, 3, 5];

    }

    public function updatedYear($selected_year)
    {
        // dd($selected_year);
        $this->year = $selected_year;
        if (is_numeric($selected_year)) {

            $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];

            if ($this->year != 1)
                $this->to_year = $this->from_year + $this->year;
            else
                $this->to_year = $this->from_year + 1;

            $homeLoans = HomeLoan::query()
                ->select("*", DB::raw("MONTH(pay_date) as month"), DB::raw("MONTHNAME(pay_date) as monthname"))
                ->whereBetween(DB::raw('YEAR(pay_date)'), [$this->from_year, $this->to_year - 1])
                ->get();
            $homeLoansLabels = $homeLoans->pluck('pay_date')->toArray();

            $totalDebts = $homeLoans->pluck('end_balance');

            // TOTAL ASSETS
            $monthlyNetworths = MonthlyNetworth::query()
                ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
                ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year - 1])
                ->get();
            $superInvests = ProgramSuper::query()
                ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
                ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year - 1])
                ->get();
            $personalInvests = InvestPersonal::query()
                ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
                ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year - 1])
                ->get();
            $longtermInvests = LongTermInvestment::query()
                ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
                ->whereBetween(DB::raw('YEAR(date)'), [$this->from_year, $this->to_year - 1])
                ->get();

            // TOTAL ASSETS CALC
            $totalAssets = [];
            $differences = [];
            $differenceSupers = [];
            foreach ($monthlyNetworths as $key => $item){
                $currentAssets = $item->home_value + $item->cash + $item->other_invest
                    + $superInvests[$key]->total_invested + $personalInvests[$key]->total_invested
                    + $longtermInvests[$key]->total_invested;
                $totalAssets[] = $currentAssets;
                $differences[] = $currentAssets - $homeLoans[$key]->end_balance;
                $differenceSupers[] = $currentAssets - $homeLoans[$key]->end_balance - $superInvests[$key]->total_invested;
            }

            $data['total_debts'] = $totalDebts->toArray();
            $data['total_assets'] = $totalAssets;
            $data['differences'] = $differences;
            $data['differences_vs_super'] = $differenceSupers;
            $data['months'] = $homeLoansLabels;

            $this->dispatchBrowserEvent('updatedChart', $data);
            $this->render();
        }
    }

    public function render()
    {
        // TOTAL DEBTS
        $homeLoans = HomeLoan::query()
                ->select("*", DB::raw("MONTH(pay_date) as month"), DB::raw("MONTHNAME(pay_date) as monthname"))
                ->where(DB::raw('YEAR(pay_date)'), $this->from_year)
                ->get();
                // ->pluck('home_value');
        $totalDebtsText = "['" . implode("','", $homeLoans->pluck('end_balance')->toArray()) . "']";

        $homeLoansLabels = $homeLoans->pluck('pay_date')->toArray();
        $yearText = "['" . implode("','", $homeLoansLabels) . "']";



        // TOTAL ASSETS
        $monthlyNetworths = MonthlyNetworth::query()
            ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
            ->where(DB::raw('YEAR(date)'), $this->from_year)
            ->get();
        $superInvests = ProgramSuper::query()
            ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
            ->where(DB::raw('YEAR(date)'), $this->from_year)
            ->get();
        $personalInvests = InvestPersonal::query()
            ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
            ->where(DB::raw('YEAR(date)'), $this->from_year)
            ->get();
        $longtermInvests = LongTermInvestment::query()
            ->select("*", DB::raw("MONTH(date) as month"), DB::raw("MONTHNAME(date) as monthname"))
            ->where(DB::raw('YEAR(date)'), $this->from_year)
            ->get();

        // TOTAL ASSETS CALC
        $totalAssets = [];
        $differences = [];
        $differenceSupers = [];
        foreach ($monthlyNetworths as $key => $item){
             $currentAssets = $item->home_value + $item->cash + $item->other_invest
                + $superInvests[$key]->total_invested + $personalInvests[$key]->total_invested
                + $longtermInvests[$key]->total_invested;
            $totalAssets[] = $currentAssets;
            $differences[] = $currentAssets - $homeLoans[$key]->end_balance;
            $differenceSupers[] = $currentAssets - $homeLoans[$key]->end_balance - $superInvests[$key]->total_invested;
        }

        $totalAssetsText = "['" . implode("','", $totalAssets) . "']";
        $differencesText = "['" . implode("','", $differences) . "']";
        $differenceSupersText = "['" . implode("','", $differenceSupers) . "']";
//        dd($totalDebtsText, $totalAssetsText, $differencesText, $differenceSupersText);
//        dd($monthlyNetworths, $superInvests, $personalInvests, $longtermInvests);


        // $years = MonthlyNetworth::select('date')->distinct('date')->get();
        return view('livewire.chart-dashboard')->with([
            "total_debts" => $totalDebtsText,
            'yearText' => $yearText,
            'total_assets' => $totalAssetsText,
            'differences' => $differencesText,
            'differences_vs_super' => $differenceSupersText
        ]);
    }
}
