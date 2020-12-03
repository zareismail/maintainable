<?php

namespace Zareismail\Maintenable\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Zareismail\Maintenable\Models\MaintenanceIssue;
use Zareismail\Maintenable\Nova\Action as ActionResource;

class SettleUp extends Action
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
        $issue = MaintenanceIssue::whereDoesntHave('action')->findOrFail(
            head($issues->modelKeys())
        ); 

        $action = $issue->action()->create();
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
        return [];
    }
}
