<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Invoice;
use App\Models\Payment;
use App\Modules\ModelStates\PartiallyPaid;
use App\Modules\ModelStates\FullyPaid;
use App\Actions\CreatePayment;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_invoice_can_be_state_created(): void
    {
        $invoice = Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $this->assertEquals('created', $invoice->state->value());
        $this->assertEquals('Orange', $invoice->state->color_description());
        $this->assertEquals(100, $invoice->total_amount);
    }

    public function test_invoice_can_be_state_partially_paid()
    {
        $invoice = Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $payment = (new CreatePayment())->handle([
            'invoice_id' => $invoice->id,
            'amount' => 50,
        ]);

        $this->assertEquals('partially_paid', $payment->invoice->state->value());
        $this->assertEquals('Yellow', $payment->invoice->state->color_description());
        $this->assertEquals(50, $payment->invoice->payments->sum('amount'));
        $this->assertEquals(100, $payment->invoice->total_amount);
    }

    public function test_invoice_can_be_state_fully_paid()
    {
        $invoice = Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $payment = (new CreatePayment())->handle([
            'invoice_id' => $invoice->id,
            'amount' => 100,
        ]);

        $this->assertEquals('fully_paid', $payment->invoice->state->value());
        $this->assertEquals('Green', $payment->invoice->state->color_description());
        $this->assertEquals(100, $payment->invoice->payments->sum('amount'));
        $this->assertEquals(100, $payment->invoice->total_amount);
    }
}
