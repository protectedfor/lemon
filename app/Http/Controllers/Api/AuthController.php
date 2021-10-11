<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\User as UserResource;
use App\Models\User;
use App\Rules\PhonePlusPrefix;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function signin(Request $request): JsonResponse
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
        } else {
            if (Carbon::now()->diffInSeconds($user->code_requested_at) < 60) {
                $left = 60 - Carbon::now()->diffInSeconds($user->code_requested_at);
                return $this->sendError('Подождите ' . $left . ' секунд чтобы отправить код заново', [
                    'secondsLeft'        => $left,
                    'waitUntilTimestamp' => Carbon::now()->addSeconds($left)->timestamp,
                    'waitUntil'          => Carbon::now()->addSeconds($left)->format('d.m.Y H:i:s'),
                ]);
            }
        }

        $user->code_requested_at = Carbon::now();
        $user->confirmation_code = confirmation_code();
        $user->save();

        $this->sendNikitaSMS($request->phone, 'Ваш код: ' . $user->confirmation_code . '. Никому не сообщайте ваш код.');

        return $this->sendResponse([], 'Код отправлен на номер +' . $request->phone);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyPhone(Request $request): JsonResponse
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

        $user->code_requested_at = Carbon::now();
        $user->save();

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        return $this->sendResponse($success, 'Вы вошли');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return $this->sendResponse(new UserResource($request->user()->loadMissing('organizations.invoices')), 'Данные пользователя.');
    }

    /**
     * @return JsonResponse
     */
    public function signout(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tokens()->delete();

        $user->confirmation_code = null;
        $user->save();

        return $this->sendResponse([], 'Выход выполнен');
    }
}
