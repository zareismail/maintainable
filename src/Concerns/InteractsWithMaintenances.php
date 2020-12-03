<?php

namespace Zareismail\Maintenable\Concerns; 

use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Zareismail\Maintenable\Models\MaintenableReport;

trait InteractsWithMaintenances
{ 
	/**
	 * Query the related Environmentals.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOneOrMany
	 */
	public function reports(): HasOneOrMany
	{
		return $this->morphMany(MaintenableReport::class, 'maintenables');
	}
} 