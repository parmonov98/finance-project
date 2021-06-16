<div>
    <form wire:submit.prevent="InputData">
        <div class="card">
            <div class="card-header"><strong>Long Term Investment</strong> <small>Form</small></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4 mb-2">
                        <label for="ccnumber">Return on invest (Min)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">0</span>
                                <span class="input-group-text">%</span>
                            </div>
                            <input wire:model.defer="min" class="form-control" min="-2" max="0">
                        </div>
                        @error('min')<span class="span-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-4 mb-2">
                        <label>Return on invest (Max)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">0</span>
                                <span class="input-group-text">%</span>
                            </div>
                            <input wire:model.defer="max" class="form-control" min="0" max="10">
                        </div>
                        @error('max')<span class="span-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-4 mb-2">
                        <label>Inflation</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">0</span>
                                <span class="input-group-text">%</span>
                            </div>
                            <input wire:model.defer="inflation" class="form-control" min="0" max="10">
                        </div>
                        @error('inflation')<span class="span-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-4">
                        <label>Fees</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">0</span>
                                <span class="input-group-text">%</span>
                            </div>
                            <input wire:model.defer="fees" class="form-control">
                        </div>
                        @error('fees')<span class="span-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Monthly Invest</label>
                                    <input class="form-control" wire:model.defer="monthlyInvest" type="text" placeholder=500>
                                    @error('monthlyInvest')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Monthly Acc Fee</label>
                                    <input class="form-control" wire:model.defer="monthlyFee" type="text" placeholder=500>
                                    @error('monthlyInvest')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label>Date Input</label>
                            <input wire:model.defer="date" class="form-control" id="date-input" type="date" name="date-input" placeholder="date">
                            @error('date')<span class="span-error">{{ $message }}</span>@enderror
                        </div>
                    </div>



                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-sm btn-primary" wire:submit="InputData"> Calculate</button>
                <button class="btn btn-sm btn-danger" type="button" wire:click="ResetTables"> Reset</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header"><i class="fa fa-align-justify"></i>Long Term Investment Table</div>
        <div class="card-body">
            <table class="table table-responsive-sm table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Return on Invest</th>
                        <th>Fees/Taxes</th>
                        <th>Monthly Acc Fee</th>
                        <th>Inflation</th>
                        <th>Monthly Invest</th>
                        <th>Interest</th>
                        <th>Fees/Taxes</th>
                        <th>Total Invested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $data) <tr>
                        <td>{{ $data->formatDate() }}</td>
                        <td>{{ $data->return_on_invest*100 }}%</td>
                        <td>{{ $data->fees }}%</td>
                        <td>$ {{ $data->formatNumber($data->monthly_account_fee) }}</td>
                        <td>{{ $data->inflation*100}}%</td>
                        <td>$ {{ $data->formatNumber($data->monthly_invest) }}</td>
                        <td>$ {{ $data->formatNumber($data->interest) }}</td>
                        <td>$ {{ $data->formatNumber($data->after_fees) }}</td>
                        <td>$ {{ $data->formatNumber($data->total_invested)  }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>