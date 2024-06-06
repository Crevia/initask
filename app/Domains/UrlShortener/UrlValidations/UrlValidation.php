<?php

namespace App\Domains\UrlShortener\UrlValidations;

use InvalidArgumentException;

class UrlValidation implements UrlValidationInterface
{
    private $validators;


    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    public function validate(string $url): bool
    {
        foreach ($this->validators as $validator) {
            if (!$validator instanceof UrlValidationInterface) {
                throw new InvalidArgumentException("All validators must implement UrlValidationInterface.");
            }
            if (!$validator->validate($url)) {
                return false;
            }
        }
        return true;
    }
}
