<?php

namespace Zareismail\Maintainable\Models;  
      

class MaintenanceAction extends Model
{    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [ 
        'created_at'    => 'datetime',
        'completed_at'  => 'datetime',
    ]; 

    /**
     * Query the related MaintenanceIssue`s.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function issue()
    { 
        return $this->belongsTo(MaintenanceIssue::class, 'issue_id');
    }   

    /**
     * Determine if the action completed.
     * 
     * @return boolean
     */
    public function isCompleted(): bool
    {
        return ! is_null($this->completed_at);
    } 

    /**
     * Determine if the action in progress.
     * 
     * @return boolean
     */
    public function inProgress(): bool
    {
        return $this->exists && ! $this->isCompleted();
    }
}
