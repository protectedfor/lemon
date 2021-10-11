<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Http\Resources\Invoice as InvoiceResource;

class InvoiceTransactionController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Invoice $invoice
     * @return void
     */
    public function index(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Invoice $invoice
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Invoice $invoice)
    {
        $this->authorize('create', [Transaction::class, $invoice]);

        $validator = Validator::make($request->all(), [
            'amount'           => ['required', 'integer', 'min:1', 'max:' . $invoice->dept],
            'transaction_type' => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.transaction_types')))]
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $transaction = $invoice->transactions()->create([
            'amount'           => $request->amount,
            'transaction_type' => $request->transaction_type,
        ]);

        $invoice->refresh();

        return $this->sendResponse(new InvoiceResource($invoice), 'Оплата по счета успешно проведена');
    }

    /**
     * Display the specified resource.
     *
     * @param Invoice $invoice
     * @param Transaction $transaction
     * @return void
     */
    public function show(Invoice $invoice, Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Invoice $invoice
     * @param Transaction $transaction
     * @return void
     */
    public function update(Request $request, Invoice $invoice, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Invoice $invoice
     * @param Transaction $transaction
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorize('delete', [Transaction::class, $transaction]);

        $transaction->delete();

        $transaction->invoice->refresh();

        return $this->sendResponse(new InvoiceResource($transaction->invoice), 'Транзакция успешно удалена.');
    }
}
