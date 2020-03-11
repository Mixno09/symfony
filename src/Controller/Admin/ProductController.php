<?php

declare(strict_type=1);

namespace App\Controller\Admin;

use App\Form\Admin\ProductCreateType;
use App\UseCase\Product\CreateProduct\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/create", name="product_create", methods={"GET", "POST"})
     */
    public function create(): Response
    {
        $command = new Command();
        $form = $this->createForm(ProductCreateType::class, $command);
        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}