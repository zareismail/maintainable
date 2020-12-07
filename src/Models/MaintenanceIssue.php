<?php

namespace Zareismail\Maintainable\Models;  
      

class MaintenanceIssue extends Model
{    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [ 
        'created_at' => 'datetime'
    ]; 

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['action'];

    /**
     * Query the related MaintenanceIssue`s.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    { 
        return $this->belongsTo(MaintenanceCategory::class);
    } 

    /**
     * Query the related MaintenanceIssue`s.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function action()
    { 
        return $this->hasOne(MaintenanceAction::class, 'issue_id');
    }  

    /**
     * Query the related maintainables.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maintainable()
    { 
        return $this->morphTo();
    }  

    public function confirmed()
    {
        $this->relationLoaded('action') || $this->load('action');

        return ! is_null($this->action);
    }
}
