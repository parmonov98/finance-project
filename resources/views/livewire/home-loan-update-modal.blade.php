<div>

  <div class="modal {{ $is_open ? 'show' : '' }} fade" id="updateHomeLoan" tabindex="-1"
    style="{{ $is_open ? 'display:block;' : 'display:none;' }}" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form class="edit_home_loan_form modal-content" wire:submit.prevent="save">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Home Loan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">

            {{$home_loan}}
            {{-- <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="ccnumber">Sum</label>
                  <input wire:model="sum" class="form-control" id="ccnumber" type="text" placeholder="">
                </div>
              </div>
            </div> --}}

            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="form-group">
                  <label>Loan Amount</label>
                  <input wire:model.defer="end_balance" type="text" class="form-control numeric"
                    placeholder="Enter your loan amount">
                  @error('loan')<span class="span-error">{{ $message }}</span>@enderror
                </div>
              </div>
              {{-- <div class="col-sm-12 col-md-6">
                <div class="form-group">
                  <label>Annual Interest Rate (%)</label>
                  <input wire:model.defer="int_rate" class="form-control" placeholder="3">
                  @error('int_rate')<span class="span-error">{{ $message }}</span>@enderror
                </div>
              </div> --}}
            </div>
{{--
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
                  @error('nb_pay') <span class="span-error">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="form-group">
                  <label>Date Input</label>
                  <input wire:model.defer="date" class="form-control" id="date-input" type="date" name="date-input"
                    placeholder="date">
                  @error('date')<span class="span-error">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div class="form-group">
                  <div class="form-group">
                    <label>Extra Payments</label>
                    <input wire:model.defer="ext_pay" class="form-control" placeholder="Optional">
                    @error('ext_pay')<span class="span-error">{{ $message }}</span>@enderror
                  </div>
                </div>
              </div>
            </div> --}}
            {{-- <div class="card">
              <div class="card-header"><strong>Amortization Calculator</strong> <small>Input Data</small></div>
              <div class="card-body">
              </div>
              <div class="card-footer">
                <button class="btn btn-sm btn-primary" type="submit" wire:submit="Inputdata">Calculate</button>
                <button class="btn btn-sm btn-danger" type="button" wire:click="ResetTables"> Reset</button>
              </div>
            </div> --}}

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">OK</button>
        </div>
    </div>
    </form>
  </div>


  @push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  @endpush


</div>
