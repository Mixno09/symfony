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
use Symfony\Component\Validator\Constraints as Assert;

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
                'mapped' => false, // unmapped означает, что это поле не связано ни с каким свойством объекта
                'constraints' => [
                    new Assert\NotBlank(['groups' => ['create']]),
                    new Assert\Image([
                        'minWidth' => 400,
                        'maxWidth' => 800,
                        'minHeight' => 400,
                        'maxHeight' => 800,
                        'minRatio' => 1,
                        'maxRatio' => 1,
                        'mimeTypes' => ['image/jpeg']
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'translation_domain' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'PUT',
            'data_class' => Product::class,
            'attr' => ['novalidate' => 'novalidate'], // убирает валидацию html
        ]);
    }
}
