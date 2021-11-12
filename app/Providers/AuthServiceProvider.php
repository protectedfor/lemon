<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Partner;
use App\Models\Report;
use App\Models\Transaction;
use App\Policies\InvoicePolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\PartnerPolicy;
use App\Policies\ReportPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Organization::class => OrganizationPolicy::class,
        Partner::class      => PartnerPolicy::class,
        Invoice::class      => InvoicePolicy::class,
        Report::class       => ReportPolicy::class,
        Transaction::class  => TransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        if(!request()->isJson())
            $this->policies = [];
        foreach ($this->policies() as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return $this->policies;
    }
}
