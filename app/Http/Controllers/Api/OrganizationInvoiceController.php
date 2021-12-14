<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Resources\Invoice as InvoiceResource;
use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @return Application|Factory|View|Response
     */
    public function getPdf(Request $request, Invoice $invoice)
    {
        $pdf = App::make('dompdf.wrapper');
        $executor_requisites = [];
        $invoice->organization->organization_title ? array_push($executor_requisites, $invoice->organization->organization_title) : null;
        $invoice->organization->inn ? array_push($executor_requisites, 'ИНН ' . $invoice->organization->inn) : null;
        $invoice->organization->address ? array_push($executor_requisites, $invoice->organization->address) : null;
        $invoice->organization->bik ? array_push($executor_requisites, 'БИК ' . $invoice->organization->bik) : null;
        $invoice->organization->account_number ? array_push($executor_requisites, 'р/с: ' . $invoice->organization->account_number) : null;
        $invoice->organization->bank_address ? array_push($executor_requisites, 'в ' . $invoice->organization->bank_address) : null;

        $customer_requisites = [];
        $invoice->partner->organization_title ? array_push($customer_requisites, $invoice->partner->organization_title) : null;
        $invoice->partner->inn ? array_push($customer_requisites, 'ИНН ' . $invoice->partner->inn) : null;
        $invoice->partner->address ? array_push($customer_requisites, $invoice->partner->address) : null;
        $invoice->partner->bik ? array_push($customer_requisites, 'БИК ' . $invoice->partner->bik) : null;
        $invoice->partner->account_number ? array_push($customer_requisites, 'р/с: ' . $invoice->partner->account_number) : null;
        $invoice->partner->bank_address ? array_push($customer_requisites, 'в ' . $invoice->partner->bank_address) : null;
        $pdf->loadView('invoices.pdf', compact('invoice', 'executor_requisites', 'customer_requisites'));
        return $request->has('html') ? view('invoices.pdf', compact('invoice')) : $pdf->download('Счет на оплату №' . $invoice->id . ' от ' . $invoice->human_created_at . '.pdf');
    }
}
