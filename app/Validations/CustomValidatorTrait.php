<?php 

namespace App\Validations;

trait CustomValidatorTrait {
    public static function isValid($val)
    {
        $instance = new static();
        return $instance->validate($val);
    }
}
