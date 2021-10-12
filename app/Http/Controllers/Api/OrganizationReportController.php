<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Organization;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class OrganizationReportController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Organization $organization)
    {
        $this->authorize('create', [Report::class, $organization]);

        $rules = $this->validationRules();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Ошибки валидации', $validator->errors());
        }

        $date = new Carbon;

        $month_quarter = [
            1 => 1,
            2 => 4,
            3 => 7,
            4 => 10,
        ];

        $date->month($month_quarter[$request->quarter]);

        $quarter_start = $date->startOfQuarter()->addDays(0);
        $quarter_end = $date->clone()->endOfQuarter()->addDays(0);

        $data = array_merge([
            'quarter_from' => $quarter_start->format('Y-m-d'),
            'quarter_to'   => $quarter_end->format('Y-m-d'),
            'status'       => 'not_sent',
        ], $validator->safe()->toArray());

        $report = $organization->reports()->create($data);

        return $this->sendResponse($report, 'Отчет создан');

//        return $this->sendResponse([
//            'current_quarter_start' => $quarter_start->format('d.m.Y'),
//            'current_quarter_end'   => $quarter_end->format('d.m.Y'),
//        ], '');

    }

    /**
     * @param $fields
     * @return string[]
     */
    private function validationRules()
    {
        $rules = [
            'type' => ['required', 'in:' . implode(',', array_keys(trans('invoiceOptions.tax_report_types')))]
        ];
        switch (request('type')) {
            case 'sales_tax':
                for ($i = 50; $i <= 85; $i++) {
                    $rules['field_0' . $i] = ['required', 'integer'];
                }
                break;
            case 'incoming_tax':
                for ($i = 50; $i <= 99; $i++) {
                    $rules['field_0' . $i] = ['required', 'integer'];
                }
                break;
            case 'income_tax':
                for ($i = 203; $i <= 215; $i++) {
                    $rules['field_' . $i] = ['required', 'integer'];
                }
                break;
            default:
                return $rules;
        }
        return $rules;
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @return Response
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @return Response
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @return Response
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
