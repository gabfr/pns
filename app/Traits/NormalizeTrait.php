<?php

namespace App\Traits;

trait NormalizeTrait
{

    /**
     * Bootable normalize trait. Call "normalize" methods for each
     * model key
     */
    public static function bootNormalizeTrait()
    {

        // Only array is accepted
        if( !isset(static::$normalize) || !is_array(static::$normalize) ) return;

        $cb = function($model) {
            foreach( static::$normalize as $normalizeKey => $normalizeValue ) {
                $model->{$normalizeKey} = static::{$normalizeValue}($model->{$normalizeKey});
            }
        };

        static::updating($cb);
        static::creating($cb);
    }

    /**
     * Return only numbers
     * @param  Mixed $value
     * @return String|null
     */
    public static function onlyNumbers($value)
    {
        if(empty($value)) return null;

        return preg_replace('/[^0-9]/','',$value);
    }
}