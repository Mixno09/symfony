<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название',
                'attr' => ['placeholder' => 'Введите название...'],
                'translation_domain' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
                'attr' => ['placeholder' => 'Введите описание...'],
                'translation_domain' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'Изображение',
                'attr' => ['placeholder' => 'Выберите изображение...'],
                'translation_domain' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'translation_domain' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
