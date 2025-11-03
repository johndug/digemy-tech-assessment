<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Payment;
use Illuminate\Http\Response;
use App\Actions\CreatePayment;
use App\Actions\UpdateInvoiceState;
use App\Models\Invoice;

class PaymentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
        ]);

        $payment = (new CreatePayment())->handle($request->all());

        return response()->json([
            'data' => $payment,
            'status' => Response::HTTP_CREATED,
        ]);
    }

    public function show(Payment $payment): JsonResponse
    {
        return response()->json([
            'data' => $payment,
            'status' => Response::HTTP_OK,
        ]);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $invoice = Invoice::findOrFail($payment->invoice_id);

        $payment->delete();

        (new UpdateInvoiceState())->handle($invoice);

        return response()->json([
            'message' => 'Payment deleted successfully',
            'status' => Response::HTTP_NO_CONTENT,
        ]);
    }
}
