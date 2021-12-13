<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Resources\Invoice as InvoiceResource;
use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationInvoiceController extends BaseController
{
    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        $this->authorize('view', [Organization::class, $organization]);

        return $this->sendResponse(InvoiceResource::collection($organization->invoices), 'Счета организации');
    }

    public function getPdf(Request $request, Invoice $invoice)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('invoices.pdf', compact('invoice'));
//        $pdf->setOptions(['defaultFont' => 'sans-serif']);
        return $request->has('html') ? view('invoices.pdf', compact('invoice')) : $pdf->stream();
    }
}
