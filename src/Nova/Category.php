<?php

namespace Zareismail\Maintainable\Nova; 

use Illuminate\Http\Request; 
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, BelongsTo, MorphMany};   

class Category extends Resource
{  
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\Maintainable\Models\MaintenanceCategory::class; 

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
    	return [
    		ID::make(),  

            BelongsTo::make(__('Parent Category'), 'category', static::class)
                ->nullable(),

            Text::make(__('Label'), 'label')
                ->required()
                ->rules('required'), 

            MorphMany::make(__('Issues'), 'issues', Issue::class),
    	];
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableMaintenanceCategories(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query)->whereKeyNot($request->resourceId);
    }
}