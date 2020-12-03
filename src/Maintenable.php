<?php

namespace Zareismail\Maintenable;

use Illuminate\Http\Request;
use Laravel\Nova\Nova;

class Maintenable 
{  
    /**
     * The SAFE situation value.
     * 
     * @var  string
     */
    public const SAFE = 'safe';

    /**
     * The WARNING situation value.
     * 
     * @var  string
     */
    public const WARNING = 'warning';

    /**
     * The DANGEROUS situation value.
     * 
     * @var  string
     */
    public const DANGEROUS = 'dangerous';

    /**
     * The SAFE situation value.
     * 
     * @var  string
     */
    public const EMERGENCY = 'emergency';

    /**
     * Return Nova's Maintenable resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Nova\ResourceCollection
     */
    public static function maintenables(Request $request)
    {
        return Nova::authorizedResources($request)->filter(function($resource) {
            return $resource::newModel() instanceof Contracts\Maintenable;
        });
    }

    /**
     * Return risk situations.
     * 
     * @return array
     */
    public static function risks()
    {
        return [
            static::SAFE    => __('Safe'),
            static::WARNING => __('Warning'),
            static::DANGEROUS => __('Dangerous'),
            static::EMERGENCY => __('Emergency'),
        ];
    }
}
