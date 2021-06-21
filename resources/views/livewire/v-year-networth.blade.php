<div>
    <div class="row">
        <div class="col-12">
            <form wire:submit.prevent="InputData">
                <div class="card">
                    <div class="card-header"><strong>Credit Card</strong> <small>Form</small></div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="ccnumber">House Loan</label>
                                    <input wire:model.prevent="houseLoan" class="form-control" id="ccnumber" type="text" placeholder="Enter your home loan">
                                    @error('date')<span class="span-error">{{ $message }}</span>@enderror

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="ccnumber">Home Worth</label>
                                    <input wire:model.prevent="homeWorth" class="form-control" id="ccnumber" type="text" placeholder="Enter your home worth">
                                    @error('homeWorth')<span class="span-error">{{ $message }}</span>@enderror

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="ccnumber">Investment Super</label>
                                    <input wire:model.prevent="investSuper" class="form-control" id="ccnumber" type="text" placeholder="Enter your investment super">
                                    @error('investSuper')<span class="span-error">{{ $message }}</span>@enderror

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="ccnumber">Cash</label>
                                    <input wire:model.prevent="cash" class="form-control" id="ccnumber" type="text" placeholder="Enter cash">
                                    @error('cash')<span class="span-error">{{ $message }}</span>@enderror

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="ccnumber">Invest Personal</label>
                                    <input wire:model.prevent="investPersonal" class="form-control" id="ccnumber" type="text" placeholder="Enter your investment personal">
                                    @error('investPersonal')<span class="span-error">{{ $message }}</span>@enderror

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="ccnumber">Long Term Investment</label>
                                            <input wire:model.prevent="longTermInvest" class="form-control" id="ccnumber" type="text" placeholder="Enter your long term investment">
                                            @error('longTermInvest')<span class="span-error">{{ $message }}</span>@enderror

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Date Input</label>
                                            <input class="form-control" id="date-input" type="date" wire:model.prevent="date" placeholder="date" >
                                            @error('date')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit"> Submit</button>
                        <button class="btn btn-sm btn-danger" type="button" wire:click="ResetTables"> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>

    <div class="card">
        <div class="card-header">
            Program Super Table
            <span class="float-right">
                <div class="form-group row">
                    <select wire:model="show"  class="form-control" id="ccyear">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                    </select>
                </div> 
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-responsive-xs  table-striped">
                    <thead>
                        <tr>
                            <th>Quick Home Loan Repay</th>
                            <th>House Loan</th>
                            <th>Home Worth</th>
                            <th>Investment Super</th>
                            <th>Cash</th>
                            <th>Investment Personal</th>
                            <th>Long Term Investment</th>
                            <th>Total Debt</th>
                            <th>Total Assets</th>
                            <th>Difference</th>
                            <th>Difference - Super</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        @php

                        foreach($home_loans as $key => $home_loan)
                        {
                            if(isset($monthlyNetworths[$key]->home_value) && isset($longTermInvests[$key]->total_invested) && isset($home_loans[$key]->beg_balance) && isset($investSupers[$key]->total_invested))
                            {
                                $total_assets = $monthlyNetworths[$key]->home_value + $longTermInvests[$key]->total_invested;
                                $difference = $total_assets - $home_loans[$key]->beg_balance;
                                $difference_super = $total_assets - $home_loans[$key]->beg_balance - $investSupers[$key]->total_invested;
                            }
                            
                            

                            echo ' <tr> ' ; 

                                if(isset($home_loans[$key]->pay_date))
                                    echo '<td> '. $home_loans[$key]->pay_date . ' (approx)</td>';
                                else
                                    echo '<td> No data (approx)</td>';
                                
                                if(isset($home_loans[$key]->beg_balance))
                                    echo '<td>' . $home_loans[$key]->beg_balance . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->home_value))
                                    echo '<td>' . $monthlyNetworths[$key]->home_value . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($investSupers[$key]->total_invested))
                                    echo '<td>' . $investSupers[$key]->total_invested . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->cash))
                                    echo '<td> ' . $monthlyNetworths[$key]->cash . '  </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($investPersonals[$key]->total_invested))   
                                    echo '<td>' . $investPersonals[$key]->total_invested . '</td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($longTermInvests[$key]->total_invested))
                                    echo '<td>' . $longTermInvests[$key]->total_invested . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($home_loans[$key]->beg_balance))
                                    echo '<td>' . $home_loans[$key]->beg_balance .  '</td>';
                                else    
                                    echo '<td> No data</td>';

                                if(isset($total_assets))
                                    echo '<td>' . $total_assets .  '</td>';
                                else    
                                    echo '<td> No data</td>';

                                if(isset($difference))
                                    echo '<td>' . $difference .  '</td>';
                                else    
                                    echo '<td> No data</td>';

                                if(isset($difference_super))
                                    echo '<td>' . $difference_super .  '</td>';
                                else    
                                    echo '<td> No data</td>';
                                    
                            echo '</tr> ';


                            if(isset($programVYear[$key]->home_worth) && isset($programVYear[$key]->long_term_invest) && isset($programVYear[$key]->house_loan) && isset($programVYear[$key]->invest_super))
                            {
                                $total_assets_real = $programVYear[$key]->home_worth + $programVYear[$key]->long_term_invest;
                                $difference_real = $total_assets_real - $programVYear[$key]->house_loan;
                                $difference_super_real = $total_assets_real - $programVYear[$key]->house_loan - $programVYear[$key]->invest_super;
                            }
                            
                            

                            echo ' <tr> ' ; 

                                if(isset($programVYear[$key]->date))
                                    echo '<td> '. $programVYear[$key]->date . '</td>';
                                else
                                    echo '<td> No data</td>';
                                
                                if(isset($programVYear[$key]->house_loan))
                                    echo '<td>' . $programVYear[$key]->house_loan . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->home_worth))
                                    echo '<td>' . $programVYear[$key]->home_worth . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->invest_super))
                                    echo '<td>' . $programVYear[$key]->invest_super . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($monthlyNetworths[$key]->cash))
                                    echo '<td> ' . $monthlyNetworths[$key]->cash . '  </td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->invest_personal))   
                                    echo '<td>' . $programVYear[$key]->invest_personal . '</td>';
                                else
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->long_term_invest))
                                    echo '<td>' . $programVYear[$key]->long_term_invest . '</td>';
                                else 
                                    echo '<td> No data</td>';

                                if(isset($programVYear[$key]->house_loan))
                                    echo '<td>' . $programVYear[$key]->house_loan .  '</td>';
                                else    
                                    echo '<td> No data</td>';

                                if(isset($total_assets_real))
                                    echo '<td>' . $total_assets_real .  '</td>';
                                else    
                                    echo '<td> No data</td>';

                                if(isset($difference_real))
                                    echo '<td>' . $difference_real .  '</td>';
                                else    
                                    echo '<td> No data</td>';

                                if(isset($difference_super_real))
                                    echo '<td>' . $difference_super_real .  '</td>';
                                else    
                                    echo '<td> No data</td>';
                                    
                            echo '</tr> ';                           

                        }

                        @endphp
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>