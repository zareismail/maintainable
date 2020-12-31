<?php

namespace Zareismail\Maintainable\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest; 
use Zareismail\Maintainable\Maintainable;

class IssuesPerRisk extends IssueMetric
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $risks = Maintainable::risks();

        return $this->count($request, $this->newQuery($request), 'risk')
                    ->label(function($risk) use ($risks) {
                        return $risks[$risk] ?? $risk; 
                    });
    } 
}
