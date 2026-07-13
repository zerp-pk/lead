<?php

namespace Zerp\Lead\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Confines every query on the model to the authenticated user's tenant.
 *
 * Controllers here historically authorised mutations with a capability check
 * alone (`can('edit-leads')`), which every tenant's staff passes — so an id from
 * another tenant resolved fine and was then edited or deleted. Guarding each
 * action individually left the next new action unguarded, so the boundary lives
 * on the model instead: a foreign id now resolves to null, and route-model
 * binding 404s before a controller ever runs.
 *
 * Rows that own themselves carry `created_by` (the tenant id, per creatorId()).
 * Child rows — calls, emails, files, activity logs — have no such column, so
 * they declare $tenantParent and inherit the boundary through that relation.
 *
 * With no authenticated user (console commands, seeders, queued jobs) there is
 * no tenant to scope to and the scope stands down; those paths are not
 * attacker-reachable. Code that legitimately acts on another tenant — company
 * provisioning in LeadUtility::defaultdata() — must opt out explicitly with
 * withoutGlobalScope('tenant').
 */
trait TenantScoped
{
    public static function bootTenantScoped(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (!Auth::check()) {
                return;
            }

            $model = $query->getModel();

            if (property_exists($model, 'tenantParent')) {
                $query->whereHas($model->tenantParent);

                return;
            }

            $query->where($model->getTable() . '.created_by', creatorId());
        });
    }
}
