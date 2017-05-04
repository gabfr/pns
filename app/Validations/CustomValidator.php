<?php 

namespace App\Validations;

use Illuminate\Validation\Validator;

class CustomValidator extends Validator {
    public function validateCnpj($attribute, $value, $parameters)
    {
        return CNPJValidator::isValid($value);
    }
    
    public function validateCpf($attribute, $value, $parameters)
    {
        return CPFValidator::isValid($value);
    }

    public function validateCpfOrCnpj($attribute, $value, $parameters)
    {
       	if(CPFValidator::isValid($value)) return true;
       	if(CNPJValidator::isValid($value)) return true;
		return false;
    }
}
