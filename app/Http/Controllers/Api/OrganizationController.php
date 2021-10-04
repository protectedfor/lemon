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
            return $this->sendError('Организаций не найдено');
        return $this->sendResponse(new OrganizationResource($request->user()->organizations()->first()), 'Организации пользователя.');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function show(Request $request, Organization $organization): JsonResponse
    {
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
