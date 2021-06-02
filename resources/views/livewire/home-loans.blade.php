<div>
    <!-- First row of the two cards -->
    <div class="row">
        <!-- First Card -->
        <div class="col-sm-12 col-md-6">
            <form wire:submit.prevent="submit">
                <div class="card">
                    <div class="card-header"><strong>Amortization Calculator</strong> <small>Input Data</small></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Loan Amount</label>
                                    <input wire:model="loan" type="text" class="form-control numeric" placeholder="Enter your loan amount">
                                    @error('loan')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Annual Interest Rate (%)</label>
                                    <input wire:model="int_rate" class="form-control" placeholder="3">
                                    @error('int_rate')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Loan Period (Years)</label>
                                    <input wire:model="period" class="form-control" placeholder="30 (In Years)">
                                    @error('period')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Number Of Payments (Motnhs)</label>
                                    <input wire:model="nb_pay" class="form-control" placeholder="30 (Per year)">
                                    @error('nb_pay')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Date Input</label>
                                    <input wire:model="date" class="form-control" id="date-input" type="date" name="date-input" placeholder="date">
                                    @error('date')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Extra Payments</label>
                                        <input wire:model="ext_pay" class="form-control" placeholder="Optional">
                                        @error('ext_pay')<span class="span-error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit"> Calculate</button>
                        <button class="btn btn-sm btn-danger" type="reset"> Reset</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- Second Card -->
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header"><strong>Loan Summary</strong> <small>Output Data</small></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Scheduled Payment</label>
                                <input class="form-control" placeholder="No data" disabled>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="ccnumber">Scheduled No Of Pay</label>
                                <input class="form-control" placeholder="No Data" disabled>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="ccnumber">Actual No Of Payments</label>
                                <input class="form-control" placeholder="No Data" disabled>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="ccnumber">Total early Pay</label>
                                <input class="form-control" placeholder="No Data" disabled>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="ccnumber">Total Interest</label>
                                    <input class="form-control" placeholder="No Data" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Second row for the tables -->
    <div class="card">
        <div class="card-header"><i class="fa fa-align-justify"></i> Striped Table</div>
        <div class="card-body">
            <div class="tableFixHead">
                <table class="table table-responsive-sm table-striped">
                    <thead>
                        <tr>
                            <th>PMT No</th>
                            <th>PMT Date</th>
                            <th>Beg Balance</th>
                            <th>SCH Pay</th>
                            <th>EXT Pay</th>
                            <th>TOT Pay</th>
                            <th>Principal</th>
                            <th>Interest</th>
                            <th>End Balance</th>
                            <th>CUM INTRST</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=0; $i<30 ; $i++) <tr>
                            <td>1</td>
                            <td>2012/01/01</td>
                            <td>$235,000.00</td>
                            <td>$990.77</td>
                            <td>$1,200.00</td>
                            <td>$2,190.77</td>
                            <td>$1,630.27</td>
                            <td>$587.50</td>
                            <td>$233,369.73</td>
                            <td>No Data</td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>