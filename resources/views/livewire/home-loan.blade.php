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
                                             <input wire:model.defer="loan" type="text" class="form-control numeric" placeholder="Enter your loan amount">
                                             @error('loan')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Annual Interest Rate (%)</label>
                                             <input wire:model.defer="int_rate" class="form-control" placeholder="3">
                                             @error('int_rate')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Loan Period (Years)</label>
                                             <input wire:model.defer="period" class="form-control" placeholder="30 (In Years)">
                                             @error('period')<span class="span-error">{{ $message }}</span>@enderror
                                        </div>
                                   </div>
                                   <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                             <label>Number Of Payments (Months)</label>
                                             <input wire:model.defer="nb_pay" class="form-control" placeholder="30 (Per year)">
                                             @error('nb_pay')    <span class="span-error">{{ $message }}</span>@enderror
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
                                                  <input @change="removeValidationError(errors.{{$date}})" wire:model="ext_pay" class="form-control" placeholder="Optional">
                                                  @error('ext_pay')<span class="span-error">{{ $message }}</span>@enderror
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer">
                              <button class="btn btn-sm btn-primary" type="submit"> Calculate</button>
                              <!-- <button class="btn btn-sm btn-primary" wire:click="Recalculate"> Changes values</button> -->
                              <button class="btn btn-sm btn-danger" type="button" wire:click="ResetTables"> Reset</button>
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
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="name">Scheduled Payment</label>
                                        <input class="form-control" placeholder="{{ $sch_payment }}" disabled>
                                   </div>
                              </div>

                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="name">Interest savings</label>
                                        <input class="form-control" placeholder="{{ $savings }}" disabled>
                                   </div>
                              </div>

                         </div>
                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Scheduled No Of Pay</label>
                                        <input class="form-control" placeholder="{{ $sch_no_pay }}" disabled>
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Actual No Of Payments</label>
                                        <input class="form-control" placeholder="{{ $actual_no_pay }}" disabled>
                                   </div>
                              </div>

                         </div>

                         <div class="row">
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <label for="ccnumber">Total early Pay</label>
                                        <input class="form-control" placeholder="{{ $total_early_pay }}" disabled>
                                   </div>
                              </div>
                              <div class="col-sm-12 col-md-6">
                                   <div class="form-group">
                                        <div class="form-group">
                                             <label for="ccnumber">Cumulative Interest</label>
                                             <input class="form-control" placeholder="{{ $cum_interest }}" disabled>
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
                              @foreach($datas as $data) <tr>
                                   <td>{{ $data->pmt_no }}</td>
                                   <td>{{ $data->formatDate() }}</td>
                                   <td>{{ $data->beg_balance }}</td>
                                   <td>{{ $data->sch_payment }}</td>
                                   <td>{{ $data->ext_payment }}</td>
                                   <td>{{ $data->tot_payment }}</td>
                                   <td>{{ $data->principal }}</td>
                                   <td>{{ $data->interest }}</td>
                                   <td>{{ $data->end_balance }}</td>
                                   <td>{{ $data->cum_interest }}</td>
                                   </tr>
                              @endforeach
                         </tbody>
                    </table>
               </div>
          </div>
     </div>
</div>