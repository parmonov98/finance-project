<div class="">

    <div class="modal {{ $is_open ? 'show ' : '' }} fade" id="updateInvestmentSuper" tabindex="-1"
         style="{{ $is_open ? 'display:block;' : 'display:none;' }}" aria-labelledby="updateInvestmentSuper"
         aria-hidden="true">
        <div class="modal-dialog modal-content">
            <form class="edit_investment_super_form " wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Investment Super</h5>
                    <button type="button" class="close" wire:click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Enter your total invest amount</label>
                                    <input wire:model.defer="total_invested" type="text" class="form-control numeric"
                                           placeholder="Total invested">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($errors->any())
                                {!! implode('', $errors->all('<span class="text text-danger">:message</span>')) !!}
                            @endif
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="close">Cancel</button>
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
