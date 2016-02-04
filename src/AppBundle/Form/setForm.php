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

class setForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
       // \dump($options['data']);//\appbundle\controller
        $builder->add("time_period", 'choice', [
            'choices' => [
                "360D" => "Весь период",
                "30D" => "Месяц",
                "7D" => "Неделя",
                "T30H" => "День",
            ],
            'choices_as_values' => false,
            'label'=>"Период: ",
            'data'=> $options['data']["interval"],
        ]);
        $builder->add("room", 'choice', [
            'choices' => $options['data']["rooms"],
            'choices_as_values' => false,
            'label'=>"Комната: ",
            'data'=> $options['data']["room"],
        ]);
       // $builder->add('save', 'submit', array('label' => 'Обновить'));
    }

    public function getName() {
        return 'set_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

}

/*
            <input id="r0" onclick="change(this)" checked="{% if time_period=="360D" %}checked{% endif %}" type="radio" value="360D" name="time_period">
           <label for="r0">Весь период</label>
           <input id="r1" onclick="change(this)" checked="{% if time_period=="30D" %}checked{% endif %}" type="radio" value="30D" name="time_period">
           <label for="r1">Месяц</label>
           <input id="r2" onclick="change(this)" checked="{% if time_period=="T30H" %}checked{% endif %}" type="radio" value="T30H" name="time_period">
           <label for="r2">День</label>
*/