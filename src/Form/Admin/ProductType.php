<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    private CategoryRepository $categoryRepository;

    /**
     * ProductType constructor.
     * @param \App\Repository\CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => false])
            ->add('slug', TextType::class, ['required' => false])
            ->add('categories', ChoiceType::class, [
                'choices' => $this->getCategories(),
                'required' => false,
                'multiple' => true,
            ])
            ->add('description', TextareaType::class, ['required' => false])
            ->add('image', FileType::class, ['required' => false])
        ;
    }

    private function getCategories(): array
    {
        /** @var \App\Entity\Category[] $categories */
        $categories = $this->categoryRepository->findBy([], ['title.value' => 'ASC']);
        $choices = [];
        foreach ($categories as $category) {
            $key = (string) $category->getTitle();
            $choices[$key] = $category->getId()->toString();
        }
        return $choices;
    }
}
