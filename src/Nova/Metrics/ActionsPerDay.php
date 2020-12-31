<?php

namespace Zareismail\Maintainable\Nova\Metrics;

use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Maintainable\Models\MaintenanceAction;
use Zareismail\Maintainable\Maintainable;
use Zareismail\NovaPolicy\Helper;

class ActionsPerDay extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, $this->newQuery($request));
    }

    /**
     * Build new query for the metrics.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request 
     * @return \Illuminate\Database\Eloquent\Builder               
     */
    public function newQuery(NovaRequest $request)
    {
        return MaintenanceAction::authenticate()->orWhereHas('issue', function($query) use ($request) {
            $maintainables = Maintainable::maintainables($request)->map(function($resource) {
                return $resource::$model;
            });

            $query->whereHasMorph('maintainable', $maintainables->all(), function($query, $type) {
                $query->when(Helper::isOwnable($type), function($query) {
                    $query->authenticate();
                });
            });
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
        return 'maintainable-actions-per-day';
    }
}
