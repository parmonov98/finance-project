<div>
     <div class="row">
          <div class="col-sm-12 col-lg-6">
               <form wire:submit.prevent="ModifyData">
                    <div class="card">
                         <div class="card-header"><strong>Amortization Calculator</strong> <small>Input Data</small></div>
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Home Value</label>
                                             <input wire:model.defer="home_value_mod" type="text" class="form-control numeric" placeholder="Enter home value">
                                             @error('home_value_mod')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Home App (%)</label>
                                             <input wire:model.defer="home_app_mod" class="form-control" placeholder="Enter home appreciation">
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
                                             <input wire:model.defer="other_invest_mod" class="form-control" placeholder="Enter other investments">
                                             @error('other_invest_mod') <span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-6">
                                        <div class="form-group">
                                             <label>Date Input</label>
                                             <input wire:model.defer="date_mod" class="form-control" id="date-input" type="date" name="date-input" placeholder="date">
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
          <div class="col-sm-12 col-lg-6">
               <form wire:submit.prevent="InputData">
                    <div class="card">
                         <div class="card-header"><strong>Amortization Calculator</strong> <small>Input Data</small></div>
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Home Value</label>
                                             <input wire:model.defer="home_value" type="text" class="form-control numeric" placeholder="Enter home value">
                                             @error('home_value')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Home App (%)</label>
                                             <input wire:model.defer="home_app" class="form-control" placeholder="3" placeholder="Enter home appreciation">
                                             @error('home_app')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Cash (Years)</label>
                                             <input wire:model.defer="cash" class="form-control" placeholder="Enter cash" disabled>
                                             @error('cash')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Other Investments</label>
                                             <input wire:model.defer="other_invest" class="form-control" placeholder="Enter other investments" disabled>
                                             @error('other_invest') <span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-6">
                                        <div class="form-group">
                                             <label>Date Input</label>
                                             <input class="form-control" id="date-input" type="date" name="date-input" placeholder="date" disabled>
                                             @error('date')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer">
                              <button class="btn btn-sm btn-primary" type="submit" wire:submit="InputData">Calculate</button>
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
                         <!-- <table class="table table-responsive- table-striped">
                         <thead>
                              <tr>
                                   <th scope="row">Date</th>
                                   @for($i=0;$i<12;$i++) <th scope="row">2012/01/01</th>
                                        @endfor

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                   <td>2020-02-02</td>
                              </tr>
                              <td>2012/01/01</td>
                              <td>$235,000.00</td>
                              <td>$990.77</td>
                              <td>$1,200.00</td>
                              <td>$2,190.77</td>
                              <td>$1,630.27</td>
                              <td>$587.50</td>
                              <td>$233,369.73</td>
                              <td>No Data</td>

                              </tbody>
                         </table> -->
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

                         <table class="table table-responsive" style=" white-space: nowrap;">
                              <tr>
                                   <th class="table-header-bold">Debt</th>
                              </tr>
                              <tr>
                                   <th class="table-header">Date</th>
                                   @foreach($home_loan as $data)
                                   <td>{{ $data->formatDate() }}</td>
                                   @endforeach
                              </tr>
                              <tr>
                                   <th class="table-header">House Loan</th>
                                   @foreach($home_loan as $data)
                                   <td>{{ $data->end_balance }}</td>
                                   @endforeach
                              </tr>


                              <tr>
                                   <th></th>
                                   @for($i=0;$i<54;$i++) <td>
                                        </td>
                                        @endfor
                              </tr>

                              <tr>
                                   <th class="table-header-bold">Assets</th>
                              </tr>
                              <tr>
                                   <th>Home</th>
                                   @foreach($home_values as $data)
                                   <td>{{ $data->home_value ? '$ ' . $data->home_value : '' }}</td>
                                   @endforeach
                              </tr>
                              <tr>
                                   <th>Investment Super</th>
                                   @foreach($investSupers as $data)
                                   <td>{{ $data->total_invested ? '$ ' . $data->total_invested : '' }}</td>
                                   @endforeach
                              </tr>
                              <tr>
                                   <th>Cash</th>
                                   @foreach($cashs as $data)
                                   <td>{{ $data->cash ? '$ ' . $data->cash : '' }}</td>
                                   @endforeach
                              </tr>
                              <tr>
                                   <th>Investment Personal</th>
                                   @foreach($investPersonals as $data)
                                   <td>{{ $data->total_invested ? '$ ' .$data->total_invested : '' }}</td>
                                   @endforeach
                              </tr>
                              <tr>
                                   <th>Long Term Investment</th>
                                   @foreach($longTermInvests as $data)
                                   <td>{{ $data->total_invested ? '$ ' . $data->total_invested : '' }}</td>
                                   @endforeach
                              </tr>
                              <tr>
                                   <th>Other Investments</th>
                                   @foreach($other_invests as $data)
                                   <td>{{ $data->other_invest ? '$' . $data->other_invest : '' }}</td>
                                   @endforeach
                              </tr>

                              <tr>
                                   <th></th>
                                   @for($i=0;$i<24;$i++) <td>
                                        </td>
                                        @endfor
                              </tr>

                              <tr>
                                   <th class="table-header-bold">Summary</th>
                              </tr>
                              <tr>
                                   <th>Total Debt</th>
                                   @foreach($home_values as $data)
                                   <td>{{ $data->home_value ? '$ ' . $data->home_value : '' }}</td>
                                   @endforeach
                              </tr>

                              <tr>
                                   <th>Total Assets</th>
                                   @foreach($assets as $data) 
                                        <td>{{ $data ? '$ ' . $data : '' }}</td>
                                   @endforeach
                              </tr>

                              <tr>
                                   <th>Difference</th>
                                   @foreach($difference as $data) 
                                        <td>{{ $data ? '$ ' . $data : '' }}</td>
                                   @endforeach
                              </tr>

                              <tr>
                                   <th>Difference - Super</th>
                                   @foreach($differenceSuper as $data) 
                                        <td>{{ $data ? '$ ' . $data : '' }}</td>
                                   @endforeach
                              </tr>

                              <tr>
                                   <th style="background-color: #e5fbff;">Running Diff - Cash + Equity</th>
                                   @foreach($runningDiff as $data) 
                                        <td>{{ $data ? $data : ''  }}</td>
                                   @endforeach
                              </tr>

                              <tr>
                                   <th style="background-color: #e5fbff;">Running Diff - Overrall</th>
                                   @foreach($overallDiff as $data) 
                                        <td>{{ $data ? $data : '' }}</td>
                                   @endforeach
                              </tr>



                         </table>

                    </div>

               </div>
          </div>
     </div>
</div>