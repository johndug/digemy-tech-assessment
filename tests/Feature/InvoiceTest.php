<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Invoice;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_create_invoice(): void
    {
        $response = $this->actingAs($this->user)->post('/api/invoices', [
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $response->assertStatus(200);
    }

    public function test_can_get_invoices(): void
    {
        Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($this->user)->get('/api/invoices');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'total_amount',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => '100.00',
        ]);
    }

    public function test_can_get_invoice_by_id(): void
    {
        $invoice = Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);
        $response = $this->actingAs($this->user)->get('/api/invoices/' . $invoice->id);
        $response->assertStatus(200);
    }

    public function test_can_update_invoice(): void
    {
        $invoice = Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);
        $response = $this->actingAs($this->user)->put('/api/invoices/' . $invoice->id, [
            'title' => 'Updated Test Invoice',
            'description' => 'Updated Test Description',
            'total_amount' => 200,
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Updated Test Invoice',
            'description' => 'Updated Test Description',
            'total_amount' => '200.00',
        ]);
    }

    public function test_can_delete_invoice(): void
    {
        $invoice = Invoice::create([
            'id' => 1,
            'title' => 'Test Invoice',
            'description' => 'Test Description',
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($this->user)->delete('/api/invoices/' . $invoice->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Invoice deleted successfully',
        ]);
        $invoice = Invoice::withTrashed()->find($invoice->id);
        $this->assertNotNull($invoice->deleted_at);
    }
}
