<?php

namespace Zareismail\Maintainable\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest; 
use Laravel\Nova\Nova; 

class IssuesPerProperty extends IssueMetric
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    { 
        return $this->count($request, $this->newQuery($request), 'maintainable_type')
                    ->label(function($resource) {
                        $resource = Nova::resourceForModel($resource);

                        return $resource ? $resource::label() : class_basename($resource);
                    });
    } 
}
