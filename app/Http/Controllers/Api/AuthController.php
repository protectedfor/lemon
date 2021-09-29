<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Rules\PhonePlusPrefix;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', Rule::phone()->country(['KG']), new PhonePlusPrefix],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $user = User::firstWhere('phone', $request->phone);

        if (!$user) {
            $user = User::create(['phone' => $request->phone]);
        }

        $user->confirmation_code = confirmation_code();
        $user->save();

        $this->sendNikitaSMS($request->phone, 'Ваш код: ' . $user->confirmation_code . '. Никому не сообщайте ваш код.');

        return $this->sendResponse([], 'Код отправлен на номер +' . $request->phone);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', Rule::phone()->country(['KG']), new PhonePlusPrefix],
            'code'  => ['required', 'digits:4'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $user = User::firstWhere('phone', $request->phone);

        if (!$user)
            return $this->sendError('Пользователь не найден');

        if ($user->confirmation_code != $request->code) {
            return $this->sendError('Неверный код');
        }

        $user->phone_verified_at = Carbon::now();
        $user->save();

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        return $this->sendResponse($success, 'Вы вошли');
    }

    public function me(Request $request)
    {
        return $this->sendResponse($request->user(), 'Данные организации');
    }

    /**
     * @return JsonResponse
     */
    public function signout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse([], 'Выход выполнен');
    }
}
