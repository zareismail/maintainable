<?php

namespace Zareismail\Maintainable\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Maintainable\Models\MaintenanceCategory; 

class IssuesPerCategory extends IssueMetric
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $categories = MaintenanceCategory::withTrashed()->get()->pluck('label', 'id');

        return $this->count($request, $this->newQuery($request), 'category_id')
                    ->label(function($category) use ($categories) {
                        return $categories->get($category) ?? $category;
                    });
    } 
}
