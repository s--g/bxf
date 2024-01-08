<?php

namespace BxF;

use BxF\Validator\Status;
use BxF\Validator\ValidationResult;

class Validator
{
    public static function validate(Model $object)
    {
        return new ValidationResult(Status::Success);
    }
}