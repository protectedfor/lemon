<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Partner as PartnerResource;
use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class PartnersController extends BaseController
{
    /**
     * PartnersController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Partner::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $partners = $request->user()->partners()->with('user')->get();
        return $this->sendResponse(PartnerResource::collection($partners), 'Партнеры организации.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'inn' => ['required', 'digits:12'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        //TODO get request from third-party service to get organization data

        $partner = $request->user()->partners()->create([
            'inn' => $request->inn,
        ]);

        return $this->sendResponse(new PartnerResource($partner), 'Партнер сохранен.');
    }

    /**
     * @param Request $request
     * @param Partner $partner
     * @return JsonResponse
     */
    public function update(Request $request, Partner $partner)
    {
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
}
