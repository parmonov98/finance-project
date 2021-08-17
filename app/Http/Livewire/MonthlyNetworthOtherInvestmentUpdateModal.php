<?php

namespace App\Http\Livewire;

use App\Models\MonthlyNetworth;
use Livewire\Component;

class MonthlyNetworthOtherInvestmentUpdateModal extends Component
{
    protected $listeners = ['edit', 'saved' => '$refresh', 'updated', 'close'];
    public $is_open = false;
    public $other_invest = 0;
    public $monthly_networth = null;
    public function edit(MonthlyNetworth $monthlyNetworth)
    {
        $this->monthly_networth = $monthlyNetworth;
        $this->is_open = true;
        $this->other_invest = $monthlyNetworth->other_invest;
    }
    public function close()
    {
        $this->is_open = false;
        $this->dispatchBrowserEvent('closeModalMonthlyNetworthOtherInvestment');
    }

    public function save()
    {
        if ($this->monthly_networth !== null) {
            $this->monthly_networth->other_invest = $this->other_invest;
            $this->monthly_networth->save();
            $this->monthly_networth->refresh();

            // reload monthly-netwoths component
            $this->emitUp('rerender');
            $this->close();
        }
    }
    public function render()
    {
        return view('livewire.monthly-networth-other-investment-update-modal');
    }
}
