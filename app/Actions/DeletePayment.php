<?php

namespace App\Actions;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeletePayment
{
    public function handle(Payment $payment): void
    {
        DB::beginTransaction();
        try {
            $invoice = $payment->invoice;

            $payment->delete();

            (new UpdateInvoiceState())->handle($invoice);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment deletion failed: ' . $e->getMessage());
        }
    }
}
