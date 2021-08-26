<div>
    <x-loader/>

    <div class="row">
        <div class="col-12">
            <form wire:submit.prevent="ModifyData">
                <div class="card">
                    <div class="card-header"><strong>Amortization Calculator</strong> <small>Input Data</small></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Home Value</label>
                                    <input wire:model.defer="home_value_mod" type="text" class="form-control numeric"
                                           placeholder="Enter home value">
                                    @error('home_value_mod')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Home App (%)</label>
                                    <input wire:model.defer="home_app_mod" class="form-control"
                                           placeholder="Enter home appreciation">
                                    @error('home_app_mod')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Cash (Years)</label>
                                    <input wire:model.defer="cash_mod" class="form-control" placeholder="Enter cash">
                                    @error('cash_mod')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Other Investments</label>
                                    <input wire:model.defer="other_invest_mod" class="form-control"
                                           placeholder="Enter other investments">
                                    @error('other_invest_mod') <span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Date Input</label>
                                    <input wire:model.defer="date_mod" class="form-control" id="date-input" type="date"
                                           name="date-input"
                                           placeholder="date">
                                    @error('date_mod')<span class="span-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit" wire:submit="ModifyData">Calculate</button>
                        <button class="btn btn-sm btn-danger" type="button" wire:click="ResetTables"> Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header"><i class="fa fa-align-justify"></i> Striped Table</div>
            <div class="card-body">
                <div class="tableFixHead">
                    <style>
                        .table-header-bold {
                            font-weight: 700;
                            border-bottom: none;
                            background-color: #f9f9f9;
                            border-radius: 10%;
                        }

                        .table-header {
                            border-top: none;
                            margin-left: 20px;
                            padding-left: 20px;
                            font-weight: 100;
                            border-top: none;
                        }

                    </style>
                    <livewire:home-loan-update-modal/>
                    <livewire:invest-personal-update-modal/>
                    <livewire:investment-super-update-modal/>
                    <livewire:long-term-investment-update-modal/>
                    <livewire:monthly-networth-cash-update-modal/>
                    <livewire:monthly-networth-other-investment-update-modal/>

                    @if($home_loans->count() > 0)
                    <table class="table table-responsive" style=" white-space: nowrap;">
                        <tr>
                            <th class="table-header-bold">Debt</th>

                        </tr>
                        <tr>
                            <th class="table-header">Date</th>
                            @foreach ($home_loans as $data)
                                <td>
                                    {{$data->formatDate()}}
{{--                                    <button data-toggle="modal" data-toggle="modal" data-target="#updateHomeLoan"--}}
{{--                                            wire:click="$emitTo('home-loan-update-modal', 'edit', '{{ $data->pay_date }}')"--}}
{{--                                            class="btn border btn-sm">--}}
{{--                                        --}}
{{--                                    </button>--}}
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <th class="table-header">House Loan</th>
                            @foreach ($home_loans as $data)
                                <td>
{{--                                    ${{ number_format($data['end_balance'], 2, '.', ',') }}--}}
                                    <button data-toggle="modal" data-toggle="modal" data-target="#updateHomeLoan"
                                            wire:click="$emitTo('home-loan-update-modal', 'edit', {{ $data['id'] }})"
                                            class="btn border btn-sm">
                                        ${{ number_format($data['end_balance'], 2, '.', ',') }}
                                    </button>

                                </td>
                            @endforeach
{{--                            --}}
                        </tr>

                        <tr>
                            <th></th>
                            @for ($i = 0; $i < 54; $i++)
                                <td>
                                </td>
                            @endfor
                        </tr>

                        <tr>
                            <th class="table-header-bold">Assets</th>
                        </tr>

                        <tr>
                            <th>Home</th>
                            @foreach ($home_values as $item)
                                <td>
                                    $ <span contenteditable="true" class="px-1">{{$item->home_value}}</span>
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <th>Investment Super</th>
                            @foreach ($investSupers as $data)
                                <td>
{{--                                    {{ $data->total_invested ? '$ ' . $data->formatNumber($data->total_invested) : '' }}--}}
                                    <button data-toggle="modal" data-toggle="modal" data-target="#updateInvestmentSuper"
                                            wire:click="$emitTo('investment-super-update-modal', 'edit', {{ $data['id'] }})"
                                            class="btn border btn-sm">
                                        ${{ $data->formatNumber($data->total_invested) }}
                                    </button>
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <th>Cash</th>
                            @foreach ($cashs as $data)
                                <td>
{{--                                    $ <span contenteditable="true" class="px-1">{{$data->cash}}</span>--}}
                                    <button data-toggle="modal" data-toggle="modal" data-target="#updateMonthlyNetworthCash"
                                            wire:click="$emitTo('monthly-networth-cash-update-modal', 'edit', '{{ $data->id }}')"
                                            class="btn border btn-sm">
                                        $ {{ $data->formatNumber($data->cash)  }}
                                    </button>
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <th>Investment Personal</th>
                            @foreach ($investPersonals as $data)
                                <td>
{{--                                    {{ $data->total_invested ? '$ ' . $data->formatNumber($data->total_invested) : '' }}--}}
                                    <button data-toggle="modal" data-toggle="modal" data-target="#updateInvestPersonal"
                                            wire:click="$emitTo('invest-personal-update-modal', 'edit', '{{ $data->id }}')"
                                            class="btn border btn-sm">
                                        $ {{ $data->formatNumber($data->total_invested)  }}
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Long Term Investment</th>
                            @foreach ($longTermInvests as $data)
                                <td>
{{--                                    {{ $data->total_invested ? '$ ' . $data->formatNumber($data->total_invested) : '' }}--}}
                                    <button data-toggle="modal" data-toggle="modal" data-target="#updateLongTermInvestment"
                                            wire:click="$emitTo('long-term-investment-update-modal', 'edit', '{{ $data->id }}')"
                                            class="btn border btn-sm">
                                        $ {{ $data->formatNumber($data->total_invested)  }}
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Other Investments</th>
                            @foreach ($other_invests as $data)
{{--                                <td>{{ $data->other_invest ? '$' . $data->formatNumber($data->other_invest) : '' }}</td>--}}
                                    <td>
                                        <button data-toggle="modal" data-toggle="modal" data-target="#updateMonthlyNetworthOtherInvestment"
                                                wire:click="$emitTo('monthly-networth-other-investment-update-modal', 'edit', '{{ $data->id }}')"
                                                class="btn border btn-sm">
                                            $ {{ $data->formatNumber($data->other_invest)  }}
                                        </button>
                                    </td>
                            @endforeach
                        </tr>

                        <tr>
                            <th></th>
                            @for ($i = 0; $i < 24; $i++)
                                <td>
                                </td>
                            @endfor
                        </tr>

                        <tr>
                            <th class="table-header-bold">Summary</th>
                        </tr>
                        <tr>
                            <th>Total Debt</th>
                            @foreach ($home_loans as $data)
                                <td>
                                    $
                                    <span contenteditable="true" class="px-1">
                                        {{ $data->end_balance ? $data->formatNumber($data->end_balance) : '' }}
                                    </span>

                                </td>
                            @endforeach
                        </tr>

                            <tr>
                                <th>Total Assets</th>
                                @foreach ($assets as $key => $data)
                                    <td>
{{--                                       <button data-toggle="modal" data-toggle="modal" data-target="#updateTotalAsset"--}}
{{--                                                wire:click="openUpdateTotalAssetModal({{ $dates[$key] }})"--}}
{{--                                                class="btn border btn-sm">--}}
{{--                                           $--}}
{{--                                        </button>--}}
                                        $
                                        <span contenteditable="true" class="px-1">
                                            {{ $data ? number_format($data, 2, '.', ',') : '' }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>



                        <tr>
                            <th>Difference</th>
                            @if (isset($difference))
                                @foreach ($difference as $data)
                                    <td>
                                        $
                                         <span contenteditable="true" class="px-1">
                                            {{ $data ? number_format($data, 2, '.', ',') : '' }}
                                        </span>
                                    </td>
                                @endforeach
                            @endif
                        </tr>

                        <tr>
                            <th>Difference - Super</th>
                            @if (isset($differenceSuper))
                                @foreach ($differenceSuper as $data)
                                    <td>
                                        $
                                         <span contenteditable="true" class="px-1">
                                            {{ $data ? number_format($data, 2, '.', ',') : '' }}
                                        </span>
                                    </td>
                                @endforeach
                            @endif
                        </tr>

                        <tr>
                            <th style="background-color: #e5fbff;">Running Diff - Cash + Equity</th>
                            @if (isset($runningDiff))
                                @foreach ($runningDiff as $data)
                                    <td>
                                        $
                                        <span contenteditable="true" class="px-1">
                                            {{ number_format($data, 2, '.', ',') }}
                                        </span>
                                    </td>
                                @endforeach
                            @endif
                        </tr>

                        <tr>
                            <th style="background-color: #e5fbff;">Running Diff - Overrall</th>
                            @if (isset($overallDiff))
                                @foreach ($overallDiff as $data)
                                    <td>
                                        $
                                        <span contenteditable="true" class="px-1">
                                            {{ number_format($data, 2, '.', ',')}}
                                        </span>
                                    </td>
                                @endforeach
                            @endif
                        </tr>

                    </table>
                    @else
                        <span class="text-danger">Please, add home loan data!</span>
                    @endif
                </div>

            </div>
        </div>


    </div>

</div>
