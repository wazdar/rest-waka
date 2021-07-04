<?php


namespace App\Domain\User;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class UserValidator
{
    protected array $errors;

    public function validate($data, array $rules)
    {
        foreach ($rules as $field => $rule) {

            try {
                if (isset($data[$field])) {
                    $rule->setName($field)->assert($data[$field]);
                } else {
                    $this->errors[$field] = 'Is required';
                }
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages()[$field];
            }

        }
        return $this;
    }


    public function failed()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}