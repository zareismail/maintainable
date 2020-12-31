<?php

namespace Zareismail\Maintainable\Nova; 

use Illuminate\Http\Request;
use Laravel\Nova\Nova; 
use Laravel\Nova\Fields\{ID, Text, Trix, Badge, Currency, DateTime, BelongsTo, MorphTo, MorphMany};  
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Zareismail\NovaContracts\Nova\User;   
use Zareismail\Maintainable\Maintainable;  

class Action extends Resource
{  
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\Maintainable\Models\MaintenanceAction::class; 

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'issue.report';

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['auth', 'issue'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id'
    ];

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

            BelongsTo::make(__('Issue'), 'issue', Issue::class)
                ->withoutTrashed() 
                ->searchable() 
                ->sortable(),   

            BelongsTo::make(__('Raised By'), 'auth', User::class)
                ->withoutTrashed()
                ->default($request->user()->getKey())
                ->searchable() 
                ->sortable(),    

            DateTime::make(__('Confirmed At'), 'created_at')
                ->readonly() 
                ->exceptOnForms(),

            Badge::make(__('Status'), function() { 
                return $this->isCompleted() ? 'success' : 'info'; 
            })->labels([
                'success'   => __('Completed'),
                'info'      => __('In Progress'), 
            ]), 

            DateTime::make(__('Completed At'), 'completed_at')
                ->readonly() 
                ->exceptOnForms(),

            Trix::make(__('Solving Details'), 'details')
                ->nullable(),

            Currency::make(__('Cost'), 'cost')
                ->nullable(),

            Medialibrary::make(__('Images'), 'image')
                ->autouploading()
                ->hideFromIndex()
                ->nullable()
                ->rules('max:3'),

            // MorphMany::make(__('Replies'), 'replies', static::class),
    	];
    }

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\Completed::make()
                ->onlyOnTableRow() 
                ->canSee(function($request) { 
                    if($this->resource->exists) {
                        $action = $this->resource;
                    } else {
                        $action = $request->findModelQuery($request->resources)->with('auth')->firstOrFail();
                    }  

                    return $action->inProgress() && $request->user()->is($action->auth);
                }),
        ];
    }

    /**
     * Get the cards available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            Metrics\Costs::make(), 

            Metrics\Revenues::make(), 
            
            Metrics\ActionsPerDay::make(), 
        ];
    }
}