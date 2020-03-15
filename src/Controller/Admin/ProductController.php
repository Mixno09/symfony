<?php

declare(strict_type=1);

namespace App\Controller\Admin;

use App\Form\Admin\ProductType;
use App\UseCase\CreateProduct\Command;
use App\UseCase\CreateProduct\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    /**
     * @var \App\UseCase\CreateProduct\Handler
     */
    private $handler;

    /**
     * ProductController constructor.
     * @param \App\UseCase\CreateProduct\Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/product/create", name="product_create", methods={"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function create(Request $request): Response
    {
        $command = new Command();
        $form = $this->createForm(ProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->execute($command);
            return $this->redirectToRoute('product_create');
        }
        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}