<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Invoice as InvoiceResource;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Partner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class OrganizationPartnerInvoiceController extends BaseController
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        return $this->sendResponse(trans('invoiceOptions'), 'Возможные значения полей для счета на оплату');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Organization $organization, Partner $partner): JsonResponse
    {
        $this->authorize('create', [Invoice::class, $organization, $partner]);

        $validator = Validator::make($request->all(), [
            'currency'         => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.currencies')))],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.title'    => ['max:125'],
            'items.*.type'     => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.types')))],
            'items.*.quantity' => ['required'],
            'items.*.unit'     => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.units')))],
            'items.*.price'    => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $invoice = $organization->invoices()->create([
            'currency'   => $request->currency,
            'partner_id' => $partner->id,
        ]);

        $invoice->items()->createMany($request->items);

        return $this->sendResponse(new InvoiceResource($invoice), 'Счет на оплату успешно создан');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @param Invoice $invoice
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Organization $organization, Partner $partner, Invoice $invoice): JsonResponse
    {
        $this->authorize('update', [Invoice::class, $organization, $partner, $invoice]);

        $validator = Validator::make($request->all(), [
            'currency'         => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.currencies')))],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.title'    => ['max:125'],
            'items.*.type'     => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.types')))],
            'items.*.quantity' => ['required'],
            'items.*.unit'     => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.units')))],
            'items.*.price'    => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $invoice->update([
            'currency'   => $request->currency,
            'partner_id' => $partner->id,
        ]);

        $invoice->items()->delete();

        $invoice->items()->createMany($request->items);

        $invoice->refresh();

        return $this->sendResponse(new InvoiceResource($invoice), 'Счет на оплату успешно обновлен');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @param Invoice $invoice
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Request $request, Organization $organization, Partner $partner, Invoice $invoice): JsonResponse
    {
        $this->authorize('view', [Invoice::class, $organization, $partner, $invoice]);

        return $this->sendResponse(new InvoiceResource($invoice), 'Данные счета на оплату');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @param Invoice $invoice
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Organization $organization, Partner $partner, Invoice $invoice): JsonResponse
    {
        $this->authorize('delete', [Invoice::class, $organization, $partner, $invoice]);

        $invoice->delete();

        return $this->sendResponse([], 'Счет успешно удален');
    }
}
