<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Rules\PhonePlusPrefix;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class PartnersController extends BaseController
{
    public function index(Request $request)
    {
        // TODO get all partners of organization retrieved from request
        return $request->user();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inn'  => ['required', 'digits:12'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        //TODO get request from third-party service to get organization data
    }
}
