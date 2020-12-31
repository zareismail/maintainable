<?php

namespace Zareismail\Maintainable\Nova\Metrics;
 
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Maintainable\Models\MaintenanceIssue;

abstract class IssueMetric extends Partition
{   
    /**
     * Build new vquery for the metrics.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request 
     * @return \Illuminate\Database\Eloquent\Builder               
     */
    public function newQuery(NovaRequest $request)
    {
        return (new MaintenanceIssue)->newQuery()->tap(function($query) use ($request) {
            if($request->user()->cant('forceDelete', MaintenanceIssue::class)) {
                $query->authenticate($request->user());
            }
        });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'maintainable-'.parent::uriKey();
    }
}
