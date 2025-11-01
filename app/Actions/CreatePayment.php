<?php

namespace App\Actions;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePayment
{
    public function handle(array $data): Payment|null
    {
        DB::beginTransaction();
        try {
            $payment = Payment::create($data);

            (new UpdateInvoiceState())->handle($payment->invoice);

            $payment->refresh();

            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage());
            return null;
        }
    }
}
