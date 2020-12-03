<?php

namespace Zareismail\Maintenable\Nova; 

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Text, MorphMany};   

class Category extends Resource
{  
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\Maintenable\Models\MaintenanceCategory::class; 

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

            Text::make(__('Label'), 'label')
                ->required()
                ->rules('required'), 

            MorphMany::make(__('Issues'), 'issues', Issue::class),
    	];
    }
}