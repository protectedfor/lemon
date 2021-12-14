<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Organization as OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class OrganizationController extends BaseController
{

    /**
     * OrganizationsController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->organizations()->first())
            return $this->sendError('Организаций не найдено', [], 404);
        return $this->sendResponse(new OrganizationResource($request->user()->organizations()->first()), 'Организации пользователя.');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function show(Request $request, Organization $organization): JsonResponse
    {
        $cash_rate = $request->user()->tax_rate_cash;
        $cashless_rate = $request->user()->tax_rate_cashless;

        $cash = $organization->transactions->filter(function ($item) {
            return $item['transaction_type'] == 'cash';
        })->sum('amount');

        $cashless = $organization->transactions->filter(function ($item) {
            return $item['transaction_type'] == 'cashless';
        })->sum('amount');

        $cash_tax_amount = $cash_rate / 100 * $cash;
        $cashless_tax_amount = $cashless_rate / 100 * $cashless;

        $organization->taxes = [
            'in_cash_form'     => [
                'income'     => $cash,
                'cash_rate'  => $cash_rate . '%',
                'tax_amount' => round($cash_tax_amount),
            ],
            'in_cashless_form' => [
                'income' => $cashless,
                'cash_rate'  => $cashless_rate . '%',
                'tax_amount' => round($cashless_tax_amount),
            ],
            'total_tax'        => round($cash_tax_amount + $cashless_tax_amount),
        ];
        $organization->issued_invoices_total_usd = $organization->invoices->filter(function($item) {
            return $item->currency == 'usd';
        })->sum('total');
        $organization->issued_invoices_total_som = $organization->invoices->filter(function($item) {
            return $item->currency == 'som';
        })->sum('total');
        $organization->paid_invoices_total_usd = $organization->transactions->filter(function($item){
            return $item->invoice->currency == 'usd';
        })->sum('amount');
        $organization->paid_invoices_total_som = $organization->transactions->filter(function($item){
            return $item->invoice->currency == 'som';
        })->sum('amount');
        return $this->sendResponse(new OrganizationResource($organization->load('invoices')), 'Данные организации');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'inn' => [
                'required',
                'digits:12',
                Rule::unique('organizations')->where(function ($query) use ($request) {
                    return $query->where('inn', $request->inn)
                        ->where('user_id', $request->user()->id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        if ($request->user()->organizations()->count() > 0) {
            return $this->sendError('Невозможно создать больше одной организации');
        }

        $organization = $request->user()->organizations()->create([
            'inn' => $request->inn,
        ]);

        return $this->sendResponse(new OrganizationResource($organization), 'Данные организации');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'inn' => ['required', 'digits:12'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        //TODO search organization data by inn from third-party service

        return $this->sendResponse([], 'Результаты поиска');

    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function update(Request $request, Organization $organization): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'max:125'],
            'bik'            => ['required', 'max:125'],
            'bank_address'   => ['required', 'max:125'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $organization->update([
            'account_number' => $request->account_number,
            'bik'            => $request->bik,
            'bank_address'   => $request->bank_address,
        ]);

        return $this->sendResponse([], 'Банковские реквизиты организации успешно обновлены.');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function destroy(Request $request, Organization $organization): JsonResponse
    {
        $organization->delete();

        return $this->sendError('Организация успешно удалена');
    }
}
