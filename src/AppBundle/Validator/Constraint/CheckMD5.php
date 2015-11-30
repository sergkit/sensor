<?php

/*
 * Сервер домашней автоматизации.
 * author kitserg68@gmail.com
 * http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 */

namespace AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
/**
 * Description of CheckMD5
 * проверка, что сумма полей соответсвует контрольной сумме
 * @author benjuchis
 */
/**
 * @Annotation
 */
class CheckMD5 extends Constraint{
    //put your code here
    public $message="Сумма не верна";

}
