<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Actions\CreatePayment;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::create([
            'title' => 'Test Invoice',
            'description' => 'Test Invoice Description',
            'total_amount' => 100,
        ]);

        $invoice2 = Invoice::create([
            'title' => 'Test Invoice 2',
            'description' => 'Test Invoice 2 Description',
            'total_amount' => 200,
        ]);
        (new CreatePayment())->handle([
            'invoice_id' => $invoice2->id,
            'amount' => 100,
        ]);
        (new CreatePayment())->handle([
            'invoice_id' => $invoice2->id,
            'amount' => 50,
        ]);

        $invoice3 = Invoice::create([
            'title' => 'Test Invoice 3',
            'description' => 'Test Invoice 3 Description',
            'total_amount' => 300,
        ]);
        (new CreatePayment())->handle([
            'invoice_id' => $invoice3->id,
            'amount' => 300,
        ]);


        Invoice::create([
            'title' => 'Test Invoice 4',
            'description' => 'Test Invoice 4 Description',
            'total_amount' => 400,
        ]);

        $invoice5 = Invoice::create([
            'title' => 'Test Invoice 5',
            'description' => 'Test Invoice 5 Description',
            'total_amount' => 500,
        ]);
        (new CreatePayment())->handle([
            'invoice_id' => $invoice5->id,
            'amount' => 100,
        ]);
    }
}
