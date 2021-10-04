<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use App\Models\Partner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Invoice as InvoiceResource;

class OrganizationInvoiceController extends BaseController
{
    /**
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        $this->authorize('view', [Organization::class, $organization]);

        return $this->sendResponse(InvoiceResource::collection($organization->invoices), 'Счета организации');
    }
}
