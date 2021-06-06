<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VYearNetworth extends Component
{
    public $houseLoan;
    public $homeWorth;
    public $investSuper;
    public $cash;
    public $investPersonal;
    public $longTermInvest;

    protected $rules = [
        "houseLoan" => 'required|numeric|min:-2|max:0',
        'homeWorth' => 'required|numeric|min:0|max:7',
        'investSuper' => 'required|numeric|min:0|not_in:0',
        'cash' => 'required|numeric',
        'investPersonal' => 'required|date',
        'longTermInvest' => 'required|numeric|min:0|not_in:0',
    ];

    protected $messages = [
        '*.required' => 'This field is required',
        '*.numeric' => 'This field must be a number',
        '*.date' => 'This field must a date',
        '*.min:0' => 'This field must be greated than 0',
    ];

    protected $validationAttributes = [
        "houseLoan" => "house loan",
        "homeWorth" => "home worth",
        "investSuper" => "invest super",
        "cash" => "cash", 
        "investPersonal" => "invest personal",
        "longTermInvest" => "long term invest"
    ];


    public function render()
    {
        return view('livewire.v-year-networth');
    }


    public function InputData()
    {
        $this->validate();



    }
}
