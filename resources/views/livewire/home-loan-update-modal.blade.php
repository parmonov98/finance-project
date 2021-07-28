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

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="ccnumber">Sum</label>
                  <input wire:model="sum" class="form-control" id="ccnumber" type="text" placeholder="">
                </div>
              </div>
            </div>

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
