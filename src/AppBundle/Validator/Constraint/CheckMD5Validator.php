<?php

/*
 * Сервер домашней автоматизации.
 * author kitserg68@gmail.com
 *
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of CheckMD5Validator
 *
 * @author benjuchis
 */
class CheckMD5Validator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            // If you're using the new 2.5 validation API (you probably are!)
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $value)
                    ->addViolation();

        }
    }

}
