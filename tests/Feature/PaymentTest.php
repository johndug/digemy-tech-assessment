<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Payment;
use Tests\TestCase;
use App\Actions\CreatePayment;

class PaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_create_payment(): void
    {
        Invoice::create([
            'id' => 1,
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($this->user)->post('/api/payments', [
            'invoice_id' => 1,
            'amount' => 100,
        ]);

        $response->assertStatus(200);
    }

    public function test_can_get_payment_by_id(): void
    {
        $invoice = Invoice::create([
            'id' => 1,
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 100,
        ]);
        $response = $this->actingAs($this->user)->get('/api/payments/' . $payment->id);
        $response->assertStatus(200);
    }

    public function test_can_make_partial_payment(): void
    {
        $invoice = Invoice::create([
            'id' => 1,
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($this->user)->post('/api/payments', [
            'invoice_id' => $invoice->id,
            'amount' => 50,
        ]);
        $response->assertStatus(200);
        $invoice->refresh();
        $this->assertEquals('partially_paid', $invoice->state->value());
    }

    public function test_can_make_full_payment(): void
    {
        $invoice = Invoice::create([
            'id' => 1,
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($this->user)->post('/api/payments', [
            'invoice_id' => $invoice->id,
            'amount' => 100,
        ]);
        $response->assertStatus(200);
        $invoice->refresh();
        $this->assertEquals('fully_paid', $invoice->state->value());
    }
    public function test_can_delete_payment(): void
    {
        $invoice = Invoice::create([
            'id' => 1,
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $payment = (new CreatePayment())->handle([
            'invoice_id' => $invoice->id,
            'amount' => 100,
        ]);


        $this->assertEquals('fully_paid', $payment->invoice->state->value());
        $response = $this->actingAs($this->user)->delete('/api/payments/' . $payment->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Payment deleted successfully',
        ]);

        $payment->refresh();
        $this->assertNotNull($payment->deleted_at);
        $invoice->refresh();
        $this->assertEquals('created', $invoice->state->value());
    }
}
