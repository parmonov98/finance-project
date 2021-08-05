<div>
    @foreach ($home_loan as $data)
        <td>
            <button data-toggle="modal" data-toggle="modal" data-target="#updateHomeLoan"
                    wire:click="$emitTo('home-loan-update-modal', 'edit', {{ $data['id'] }})"
                    class="btn border btn-sm">
                ${{ number_format($data['end_balance'], 2, '.', ',') }}
            </button>

        </td>
    @endforeach
    <livewire:home-loan-update-modal/>
</div>
