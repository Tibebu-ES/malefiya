<?php
namespace App\Validation;


/**
 * Collection of Malefiya validation rules
 */
class MalefiyaRules
{

    /**
     * check if the given password is valid
     * minimum of 8 characters at least one letter and one number
     * @param mixed $password
     * @return bool
     */
    public function valid_password ($password = null){
        $regExp = '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z*_!@#$%]{8,20}$/';
        if(preg_match($regExp,$password)){
            return true;
        }else{
            return false;
        }
    }
}