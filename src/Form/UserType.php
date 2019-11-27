<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Пароль',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Права',
                'choices' => [
                    'ROLE_PRODUCT_UPDATE' => 'ROLE_PRODUCT_UPDATE',
                    'ROLE_PRODUCT_STORE' => 'ROLE_PRODUCT_STORE',
                    'ROLE_PRODUCT_DESTROY' => 'ROLE_PRODUCT_DESTROY',
                    'ROLE_USERS' => 'ROLE_USERS',
                    'ROLE_USER_UPDATE' => 'ROLE_USER_UPDATE',
                    'ROLE_USER_DESTROY' => 'ROLE_USER_DESTROY',
                ],
                'expanded' => true,
                'multiple' => true,
                'translation_domain' => 'user',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'PUT',
            'data_class' => User::class,
        ]);
    }
}
