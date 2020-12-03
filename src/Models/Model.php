<?php

namespace Zareismail\Maintenable\Models;  
     
use Illuminate\Database\Eloquent\SoftDeletes;
use Zareismail\NovaContracts\Models\AuthorizableModel;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia; 

abstract class Model extends AuthorizableModel implements HasMedia
{  
    use HasMediaTrait, SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [ 
    ]; 

    public function registerMediaCollections(): void
    { 
        $this->addMediaCollection('attachments');
    }
}
