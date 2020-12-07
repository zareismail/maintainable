<?php

namespace Zareismail\Maintainable\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields; 
use Laravel\Nova\Fields\{Trix, Currency, Image};  
use Zareismail\Maintainable\Models\MaintenanceAction;
use Zareismail\Maintainable\Nova\Action as ActionResource;

class Completed extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given issues.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $issues
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $issues)
    {
        $action = MaintenanceAction::findOrFail(head($issues->modelKeys())); 

        $action->forceFill([
            'cost' => $fields->cost,
            'details' => $fields->details,
            'completed_at' => now(),
        ])->save();

        $action->addMedia($fields->image)->toMediaCollection('image')->save();

        $uriKey = ActionResource::uriKey();

        return Action::push("/resources/{$uriKey}/{$action->id}");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Currency::make(__('Cost'), 'cost')
                ->required()
                ->rules('required'),

            Trix::make(__('Details'), 'details')
                ->required()
                ->rules('required'),

            Image::make(__('Images'), 'image'),
        ];
    }
}
