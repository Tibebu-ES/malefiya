<?php
namespace App\Validation;


use Config\Database;

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
    /**
     * check if the given locale is supported
     * @param string $locale
     * @return bool
     */
    public function locale (string $locale){
        $suportedLocales = \ResourceBundle::getLocales('');
        if(in_array($locale,$suportedLocales)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Checks the database to see if the given value is unique.
     * Can ignore a single record by field/value to make it useful during
     * record updates.
     * Can ignore records by field/value to filter (currently
     * accept only one filter).
     *
     * Example:
     *    is_unique[table.field,ignore_field,ignore_value,where_field,where_value]
     *    is_unique[users.email,id,5,client_id,3]
     */
    public function is_unique_with_filter(?string $str, string $field, array $data): bool
    {
        [$field, $ignoreField, $ignoreValue,$whereField, $whereValue] = array_pad(explode(',', $field), 5, null);

        sscanf($field, '%[^.].%[^.]', $table, $field);

        $row = Database::connect($data['DBGroup'] ?? null)
            ->table($table)
            ->select('1')
            ->where($field, $str)
            ->limit(1);

        if (! empty($ignoreField) && ! empty($ignoreValue) && ! preg_match('/^\{(\w+)\}$/', $ignoreValue)) {
            $row = $row->where("{$ignoreField} !=", $ignoreValue);
        }
        if (! empty($whereField) && ! empty($whereValue) && ! preg_match('/^\{(\w+)\}$/', $whereValue)) {
            $row = $row->where($whereField, $whereValue);
        }

        return $row->get()->getRow() === null;
    }
    /**
     * required for a boolean field
     * the system required function returns false if the field is false
     * this function solves the above issue
     *
     * @param mixed $value
     */
    public function required_boolean($value = null): bool
    {
        if ($value === null) {
            return false;
        }

        if ($value === true ||  $value === false){
            return true;
        }

        if (is_object($value)) {
            return true;
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return trim((string) $value) !== '';
    }

}