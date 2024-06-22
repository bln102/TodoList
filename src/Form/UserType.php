<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, array('label' => "Nom d'utilisateur", 'attr' => array('class' => 'form-control','style' => 'margin:5px')))
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => array('autocomplete' => 'new-password', 'class' => 'form-control','style' => 'margin:5px'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insérez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit être composé de {{ limit }} caractères au minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'mapped' => false,
                'attr' => array('autocomplete' => 'new-password', 'class' => 'form-control','style' => 'margin:5px'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Confirmez le mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit être composé de {{ limit }} caractères au minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('email', EmailType::class, array('label' => 'Adresse email', 'attr' => array('class' => 'form-control','style' => 'margin:5px')))
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'label' => 'Rôle',
                    'choices' => ['Utilisateur' => 'ROLE_USER', 'Administrateur' => 'ROLE_ADMIN'],
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                ]
            )
                ->add('save', SubmitType::class, array('label' => 'Soumettre', 'attr' => array('class' => 'btn btn-primary','style' => 'margin:5px')))
        ;
        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            function ($roles) {
                return ($roles[0] ?? null);
            },
            function ($roles) {
                return [$roles];
            }));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
