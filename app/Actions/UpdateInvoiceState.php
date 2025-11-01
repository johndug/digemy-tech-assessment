<?php

namespace App\Actions;

use App\Models\Invoice;
use App\Modules\ModelStates\PartiallyPaid;
use App\Modules\ModelStates\FullyPaid;
use App\Modules\ModelStates\Created;

class UpdateInvoiceState
{
    public function handle(Invoice $invoice): void
    {
        // if there are no payments, set state to created
        if ($invoice->totalPaid() == 0) {
            $invoice->state->transitionTo(Created::class)->save();
        } else if (
            $invoice->totalPaid() < $invoice->total_amount
        ) {
            $invoice->state->transitionTo(PartiallyPaid::class)->save();
        } else if ($invoice->totalPaid() == $invoice->total_amount) {
            $invoice->state->transitionTo(FullyPaid::class)->save();
        }
    }
}
