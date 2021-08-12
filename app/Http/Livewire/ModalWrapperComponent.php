<?php

namespace App\Http\Livewire;

use App\Models\HomeLoan;
use App\Models\HomeLoanData;
use Livewire\Component;

class ModalWrapperComponent extends Component
{

    public $home_loan;

    protected $listeners = ['open' => 'openUpdateHomeLoanModal', 'close',  'saved', 'rerender'];

    public function rerender($home_loan)
    {
        $this->home_loan = $home_loan;
//        dd($this->home_loan);
//        $this->mount($home_loan);
        $this->render();
    }

    public function mount($home_loan){
        $this->home_loan = $home_loan;
    }

    public function render()
    {
        return view('livewire.modal-wrapper-component',
        [
            'home_loan' => $this->home_loan
        ]);
    }
}
