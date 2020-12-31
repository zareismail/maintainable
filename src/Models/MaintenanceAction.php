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

    /**
     * Query where "completed at" not null.
     * 
     * @param  \Illuminate\Database\Elqoemt\Builder $query 
     * @return \Illuminate\Database\Elqoemt\Builder        
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull($this->getQualifiedCompletedAtColumn());
    }

    /**
     * Query where "completed at" not null.
     * 
     * @param  \Illuminate\Database\Elqoemt\Builder $query 
     * @return \Illuminate\Database\Elqoemt\Builder        
     */
    public function scopeProgressing($query)
    {
        return $query->whereNull($this->getQualifiedCompletedAtColumn());
    }

    /**
     * Returns "completed at" column qulified for query.
     * 
     * @return string
     */
    public function getQualifiedCompletedAtColumn()
    {
        return $query->qualifyColumn('completed_at');
    }
}
