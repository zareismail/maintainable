<?php

namespace Zareismail\Maintenable\Models;  
      
use Illuminate\Database\Eloquent\{Model, SoftDeletes}; 

class MaintenanceCategory extends Model
{      
    /**
     * Query the related Letters.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Issues()
    { 
        return $this->hasMany(MaintenanceIssue::class, 'category_id');
    }  
}
