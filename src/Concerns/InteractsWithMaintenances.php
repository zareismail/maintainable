<?php

namespace Zareismail\Maintainable\Concerns; 

use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Zareismail\Maintainable\Models\MaintainableReport;

trait InteractsWithMaintenances
{ 
	/**
	 * Query the related Environmentals.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOneOrMany
	 */
	public function reports(): HasOneOrMany
	{
		return $this->morphMany(MaintainableReport::class, 'maintainables');
	}
} 