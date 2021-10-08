<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceTransactionController extends Controller
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
     * @return void
     */
    public function store(Request $request, Invoice $invoice)
    {
        //
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
     * @return void
     */
    public function destroy(Invoice $invoice, Transaction $transaction)
    {
        //
    }
}
