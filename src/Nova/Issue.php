<?php

namespace Zareismail\Maintenable\Nova; 

use DateTimeInterface;
use Illuminate\Http\Request;
use Laravel\Nova\Nova; 
use Laravel\Nova\Panel; 
use Laravel\Nova\Fields\{ID, Badge, Text, Select, Trix, DateTime, BelongsTo, MorphTo, MorphMany}; 
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Zareismail\NovaContracts\Nova\User;   
use Zareismail\Maintenable\Maintenable;  

class Issue extends Resource
{  
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\Maintenable\Models\MaintenanceIssue::class; 

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'report';

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['auth', 'category', 'maintenable', 'action'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'report'
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

            BelongsTo::make(__('Raised By'), 'auth', User::class)
                ->withoutTrashed()
                ->default($request->user()->getKey())
                ->searchable()
                ->readonly()
                ->sortable(),  

            BelongsTo::make(__('Category'), 'category', Category::class)
                ->withoutTrashed() 
                ->sortable()
                ->hideFromIndex()
                ->showCreateRelationButton(function($request) {
                    return $request->user()->can('create', Category::newModel());
                }), 

            MorphTo::make(__('Building'), 'maintenable')
                ->types($maintenables = Maintenable::maintenables($request)->all())
                ->withoutTrashed()
                ->searchable(), 

            Select::make(__('Risk'), 'risk')
                ->options(Maintenable::risks())
                ->default(Maintenable::SAFE)
                ->displayUsingLabels()
                ->hideFromIndex()
                ->sortable(),  

            Badge::make(__('Status'), function() {
                if($this->resource->confirmed()) {
                    return $this->resource->action->isCompleted() ? 'success' : 'info';
                }

                return 'danger';
            })->labels([
                'success'   => __('Completed'),
                'info'      => __('In Progress'),
                'danger'    => __('Pending'),
            ]),

            Text::make(__('Report'), 'report')
                ->required()
                ->rules('required'),

            DateTime::make(__('Raised At'), 'created_at')
                ->readonly()
                ->default((string) now())
                ->exceptOnForms()
                ->sortable(),

            Trix::make(__('Issue Details'), 'details')
                ->withFiles('public'),

            Medialibrary::make(__('Images'), 'image')
                ->autouploading()
                ->hideFromIndex()
                ->nullable()
                ->rules('max:3'),    
 
            $this->when($request->isResourceDetailRequest() && $this->confirmed(), function() use ($request) {
                return new Panel(__('Setteling'), $this->confirmDetails($request));
            }), 
    	];
    }

    public function confirmed()
    {
        return $this->resource->confirmed();
    }

    public function confirmDetails(Request $request)
    {
        $fields = call_user_func([new Action($this->action), 'detailFields'], $request);

        return  collect($fields)->where('attribute', '!=', 'issue')->values() 
                    ->each->resolve($this->action)
                    ->each(function($field) {
                        $field->withMeta(['value' => $field->value]);
                    })
                    ->each->resolveUsing(function($value) {
                        return $value;
                    });
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
            Actions\SettleUp::make()
                ->onlyOnTableRow()
                ->confirmText(__('Are you sure you want to solve this issue?'))
                ->confirmButtonText(__('Yes'))
                ->canSee(function($request) {
                    return $request->user()->can('create', Action::newModel()) && ! $this->confirmed();
                }),
        ];
    }
}