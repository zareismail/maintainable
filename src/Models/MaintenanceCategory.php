<?php

namespace Zareismail\Maintainable\Models;  
      
use Illuminate\Database\Eloquent\{Model, SoftDeletes}; 

class MaintenanceCategory extends Model
{      
    use SoftDeletes;
    
    /**
     * Query the related Letters.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issues()
    { 
        return $this->hasMany(MaintenanceIssue::class, 'category_id');
    }  
    
    /**
     * Get all of the actions for the project.
     */
    public function actions()
    {
        return $this->hasManyThrough(MaintenanceAction::class, MaintenanceIssue::class, 'category_id', 'issue_id');
    }

    /**
     * Query the related category.
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo       
     */
    public function category()
    {
    	return $this->belongsTo(static::class);
    }

    /**
     * Query the related category.
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany       
     */
    public function categories()
    {
        return $this->hasMany(static::class, 'category_id');
    }
}
