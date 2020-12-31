<?php

namespace Zareismail\Maintainable\Nova\Metrics;
 
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Maintainable\Models\MaintenanceAction;
use Zareismail\Maintainable\Maintainable;
use Zareismail\NovaPolicy\Helper;

abstract class ActionValue extends Value
{   
    /**
     * Build new query for the metrics.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request 
     * @return \Illuminate\Database\Eloquent\Builder               
     */
    public function newCostQuery(NovaRequest $request)
    {
        return MaintenanceAction::completed()->whereHas('issue', function($query) use ($request) {
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
     * Build new query for the metrics.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request 
     * @return \Illuminate\Database\Eloquent\Builder               
     */
    public function newRevenueQuery(NovaRequest $request)
    {
        return MaintenanceAction::completed()->authenticate($request->user());
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
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
        return 'maintainable-'.parent::uriKey();
    }
}
