<?php

namespace Zareismail\Maintainable\Nova\Metrics;

use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest; 
use Zareismail\Maintainable\Models\MaintenanceAction;

class Actions extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request, $resource)
    {
        return $this->countByDays($request, $this->newQuery($request, $resource));
    }

    /**
     * Build new query for calculation.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request  
     * @param  \Illuminate\Database\Eloqeunt\Builder $resource 
     * @return \Illuminate\Database\Eloqeunt\Builder                
     */
    public function newQuery(NovaRequest $request, $resource)
    {
        return MaintenanceAction::whereHas('issue', function($query) use ($resource) {
            return $query->where('category_id', $resource->getKey());
        });
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            90 => __('90 Days'),
        ];
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
        return 'maintainable-actions';
    }
}
