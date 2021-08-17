<?php

namespace App\Http\Livewire;

use App\Models\MonthlyNetworth;
use Livewire\Component;

class MonthlyNetworthCashUpdateModal extends Component
{
    protected $listeners = ['edit', 'saved' => '$refresh', 'updated', 'close'];
    public $is_open = false;
    public $cash = 0;
    public $monthly_networth = null;
    public function edit(MonthlyNetworth $monthlyNetworth)
    {
        $this->monthly_networth = $monthlyNetworth;
        $this->is_open = true;
        $this->cash = $monthlyNetworth->cash;
    }
    public function close()
    {
        $this->is_open = false;
        $this->dispatchBrowserEvent('closeModalMonthlyNetworthCash');
    }

    public function save()
    {
        if ($this->monthly_networth !== null) {
            $this->monthly_networth->cash = $this->cash;
            $this->monthly_networth->save();
            $this->monthly_networth->refresh();

            // reload monthly-netwoths component
            $this->emitUp('rerender');
            $this->close();
        }
    }

    public function render()
    {
        return view('livewire.monthly-networth-cash-update-modal');
    }
}
