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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddData extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('room', 'entity', [
            'class' => 'AppBundle:Rooms',
            'property' => 'name',
            'empty_value' => 'Выберите комнату'
        ]);
        $builder->add('co2', 'number');
        $builder->add('t', 'number');
        $builder->add('h', 'number');
        $builder->add('voc', 'number');
        $builder->add('vocr', 'number');
        $builder->add('vocold', 'number');
        $builder->add('CheckFields', 'number');
        $builder->add('save', 'submit', array('label' => 'Добавить'));
    }

    public function getName() {
        return 'add_data';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

}
