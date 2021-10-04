<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Partner as PartnerResource;
use App\Models\Organization;
use App\Models\Partner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class OrganizationPartnerController extends BaseController
{

    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        abort_unless($organization->user->is($request->user()), 401);

        $partners = $organization->partners()->with('organization.user')->get();
        return $this->sendResponse(PartnerResource::collection($partners), 'Партнеры организации.');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Organization $organization): JsonResponse
    {
        $this->authorize('create', [Partner::class, $organization]);

        $validator = Validator::make($request->all(), [
            'inn' => [
                'required', 'digits:12',
                Rule::unique('partners')->where(function ($query) use ($request, $organization) {
                    return $query->where('inn', $request->inn)
                        ->where('organization_id', $organization->id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        //TODO get request from third-party service to get organization data

        $partner = $organization->partners()->create([
            'inn' => $request->inn,
        ]);

        return $this->sendResponse(new PartnerResource($partner), 'Партнер сохранен.');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Request $request, Organization $organization, Partner $partner): JsonResponse
    {
        $this->authorize('view', [Partner::class, $organization, $partner]);

        return $this->sendResponse(new PartnerResource($partner), 'Данные партнера');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Organization $organization, Partner $partner): JsonResponse
    {
        $this->authorize('update', [Partner::class, $organization, $partner]);

        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'max:125'],
            'bik'            => ['required', 'max:125'],
            'bank_address'   => ['required', 'max:125'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $partner->update([
            'account_number' => $request->account_number,
            'bik'            => $request->bik,
            'bank_address'   => $request->bank_address,
        ]);

        return $this->sendResponse([], 'Банковские реквизиты партнера успешно обновлены.');
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Partner $partner
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Organization $organization, Partner $partner): JsonResponse
    {
        $this->authorize('delete', [Partner::class, $organization, $partner]);

        $partner->delete();

        return $this->sendResponse([], 'Партнер успешно удален.');
    }
}
