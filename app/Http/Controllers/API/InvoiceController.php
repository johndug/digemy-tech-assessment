<?php

namespace App\Http\Controllers\API;

use App\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'nullable|string',
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'order_by' => 'in:created_at,updated_at,title,total_amount,state',
            'order_direction' => 'nullable|string',
        ]);

        $invoices = Invoice::with('payments')
            ->search($request->search)
            ->orderBy($request->order_by ?? 'created_at', $request->order_direction ?? 'desc')
            ->paginate($request->per_page ?? 10);

        return response()->json($invoices, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric',
        ]);

        $invoice = Invoice::create($request->all());

        return response()->json([
            'data' => $invoice,
            'status' => Response::HTTP_CREATED,
        ]);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'data' => $invoice->load('payments'),
            'status' => Response::HTTP_OK,
        ]);
    }

    public function update(Request $request, Invoice $invoice): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric',
        ]);

        $invoice->update($request->all());

        return response()->json([
            'data' => $invoice,
            'status' => Response::HTTP_ACCEPTED,
        ]);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();
        return response()->json([
            'message' => 'Invoice deleted successfully',
            'status' => Response::HTTP_NO_CONTENT,
        ]);
    }

    public function restore(Invoice $invoice): JsonResponse
    {
        $invoice->restore();
        return response()->json([
            'message' => 'Invoice restored successfully',
            'status' => Response::HTTP_NO_CONTENT,
        ]);
    }
}
