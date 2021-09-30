<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Organization as OrganizationResource;
use Illuminate\Http\Request;
use Validator;

class OrganizationsController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'max:125'],
            'bik'            => ['required', 'max:125'],
            'bank_address'   => ['required', 'max:125'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $organization = $request->user()->update([
            'account_number' => $request->account_number,
            'bik'            => $request->bik,
            'bank_address'   => $request->bank_address,
        ]);

        return $this->sendResponse([], 'Банковские реквизиты организации успешно обновлены.');
    }
}
