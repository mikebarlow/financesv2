<div class="col-3">
    <div class="card">
        <div class="card-header">
            <div class="pull-left d-inline-block">Mass Transfer</div>

            <a href="{{ route('accounts.mass-transfers', ['id' => $account->id]) }}" class="btn-card btn-card-right btn-warning float-right">
                Manage
            </a>
        </div>

        <div class="card-body p-0">
            <div class="list-group">
                @foreach ($account->massTransfers as $transfer)
                    <button class="list-group-item list-group-item-action" @click.prevent="sendMassTransfer('{{ $transfer->id }}')">
                        {{ $transfer->name }}
                    </button>
                @endforeach

            </div>
        </div>
    </div>
</div>