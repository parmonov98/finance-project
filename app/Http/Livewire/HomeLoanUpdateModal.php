<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HomeLoan;

class HomeLoanUpdateModal extends Component
{
    protected $listeners = ['edit', 'saved' => '$refresh'];
    public $is_open = false;
    public $sum = 0;
    public $home_loan = null;
    public function edit(HomeLoan $home_loan)
    {
        $this->home_loan = $home_loan;
        $this->sum = $home_loan->end_balance;
        $this->is_open = true;
    }

    public function close()
    {
        $this->is_open = false;
        // $this->emitSelf('saved');
        $this->dispatchBrowserEvent('closeModalOfHomeLoan');
    }

    public function save()
    {
        // dd($this->home_loan);
        if ($this->home_loan !== null) {
            $this->home_loan->end_balance = $this->sum;
            $this->home_loan->save();
            $this->emitTo('monthly-networths', 'saved', $this->home_loan->id);
            $this->close();
            return true;
        }
    }

    public function render()
    {
        return view('livewire.home-loan-update-modal');
    }
}
