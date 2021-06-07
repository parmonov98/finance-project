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
                        <button class="btn btn-sm btn-danger" type="reset"> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>


    <div class="card">
        <div class="card-header"><i class="fa fa-align-justify"></i> Program Super Table</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-sm table-striped">
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

                        $check = false;

                        foreach($home_loans as $key => $home_loan)
                        {

                            $total_assets = $monthlyNetworths[$key]->home_value + $longTermInvests[$key]->total_invested;
                            $difference = $total_assets - $home_loans[$key]->beg_balance;
                            $difference_super = $total_assets - $home_loans[$key]->beg_balance - $investSupers[$key]->total_invested;

                            echo '
                                <tr>
                                    <td>'. $home_loan->pay_date . ' (approx)</td>
                                    <td>' . $home_loans[$key]->beg_balance . '</td>
                                    <td>' . $monthlyNetworths[$key]->home_value . '</td>
                                    <td>' . $investSupers[$key]->total_invested . '</td>
                                    <td> ' . $monthlyNetworths[$key]->cash . '  </td>
                                    <td>' . $investPersonals[$key]->total_invested . '</td>
                                    <td>' . $longTermInvests[$key]->total_invested . '</td>
                                    <td>' . $home_loans[$key]->beg_balance .  '</td>
                                    <td>' . $total_assets . '</td>
                                    <td>' . $difference . '</td>
                                    <td>' . $difference_super . '</td>
                                </tr>
                            ';


                            $total_assets_real = $programVYear[$key]->home_worth + $programVYear[$key]->long_term_invest;
                            $difference_real = $total_assets_real - $programVYear[$key]->house_loan;
                            $difference_super_real = $total_assets_real - $programVYear[$key]->house_loan - $programVYear[$key]->invest_super;

                            echo '
                                <tr>
                                    <td>' . $programVYear[$key]->date . '</td>
                                    <td>' . $programVYear[$key]->house_loan . '</td>
                                    <td>' . $programVYear[$key]->home_worth . '</td>
                                    <td>' . $programVYear[$key]->invest_super . '</td>
                                    <td>' . $programVYear[$key]->cash . '</td>
                                    <td>' . $programVYear[$key]->invest_personal . '  </td>
                                    <td>' . $programVYear[$key]->long_term_invest . '</td>
                                    <td>' . $programVYear[$key]->house_loan . '</td>
                                    <td>' . $total_assets_real.  '</td>
                                    <td>' . $difference_real . '</td>
                                    <td>' . $difference_super_real . '</td>
                                </tr>
                            ';


                        }

                        @endphp
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>