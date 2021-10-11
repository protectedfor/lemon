<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Slider as SliderResource;

class SliderController extends BaseController
{
    /**
     * @var Slider
     */
    private Slider $slider;

    /**
     * SliderController constructor.
     * @param Slider $slider
     */
    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(SliderResource::collection($this->slider->ordered()->get()), 'Картинки баннеров');
    }
}
