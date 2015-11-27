<?php

/*
 * Сервер домашней автоматизации.
 * author kitserg68@gmail.com
 *
 */

namespace AppBundle\Form;

/**
 * Description of AddData
 *
 * @author benjuchis
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddData extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('co2', 'number');
        $builder->add('t', 'number');
        $builder->add('h', 'number');
        $builder->add('VOC', 'number');
        $builder->add('VOCR', 'number');
        $builder->add('VOCold', 'number');
        $builder->add('save', 'submit', array('label' => 'Добавить'));
    }

    public function getName() {
        return 'add_data';
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'AppBundle\Entity\Thtable',
        );
    }

}
