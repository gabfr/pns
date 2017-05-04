<?php 

namespace App\Validations\Contracts;

interface CustomValidatorContract
{
    public function validate($value);
    public static function isValid($value);
}
