<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, array('label' => "Title", 'attr' => array('class' => 'form-control','style' => 'margin:5px')))
            ->add('content', TextareaType::class, array('label' => "Content", 'attr' => array('class' => 'form-control','style' => 'margin:5px')))
            // ->add('user') ===> must be the user authenticated
        ;
    }
}
